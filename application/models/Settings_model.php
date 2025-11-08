<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Configurações - ConectCorretores
 * 
 * Gerencia configurações do sistema com cache
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 08/11/2025
 */
class Settings_model extends CI_Model {

    protected $table = 'settings';
    private $settings_cache = [];
    private $cache_loaded = false;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Obter valor de uma configuração
     * 
     * @param string $chave Chave da configuração
     * @param mixed $default Valor padrão se não encontrar
     * @return mixed Valor da configuração
     */
    public function get($chave, $default = null) {
        // Carregar cache se ainda não foi carregado
        if (!$this->cache_loaded) {
            $this->load_cache();
        }

        // Retornar do cache
        if (isset($this->settings_cache[$chave])) {
            return $this->_convert_value($this->settings_cache[$chave]);
        }

        return $default;
    }

    /**
     * Definir valor de uma configuração
     * 
     * @param string $chave Chave da configuração
     * @param mixed $valor Valor da configuração
     * @param string $tipo Tipo do valor (string, int, bool, json, float)
     * @return bool Sucesso
     */
    public function set($chave, $valor, $tipo = null) {
        // Buscar configuração existente
        $setting = $this->db->get_where($this->table, ['chave' => $chave])->row();

        // Converter valor para string baseado no tipo
        if (!$tipo && $setting) {
            $tipo = $setting->tipo;
        }

        $valor_string = $this->_value_to_string($valor, $tipo);

        $data = [
            'valor' => $valor_string,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($tipo) {
            $data['tipo'] = $tipo;
        }

        if ($setting) {
            // Atualizar existente
            $this->db->where('chave', $chave);
            $success = $this->db->update($this->table, $data);
        } else {
            // Criar nova
            $data['chave'] = $chave;
            if (!$tipo) {
                $data['tipo'] = 'string';
            }
            $success = $this->db->insert($this->table, $data);
        }

        // Limpar cache
        if ($success) {
            $this->clear_cache();
        }

        return $success;
    }

    /**
     * Obter todas as configurações
     * 
     * @param string $grupo Filtrar por grupo (opcional)
     * @param bool $only_editable Apenas editáveis
     * @return array Lista de configurações
     */
    public function get_all($grupo = null, $only_editable = false) {
        if ($grupo) {
            $this->db->where('grupo', $grupo);
        }

        if ($only_editable) {
            $this->db->where('editavel', 1);
        }

        $this->db->order_by('grupo', 'ASC');
        $this->db->order_by('chave', 'ASC');

        return $this->db->get($this->table)->result();
    }

    /**
     * Obter configurações agrupadas
     * 
     * @param bool $only_editable Apenas editáveis
     * @return array Configurações agrupadas
     */
    public function get_grouped($only_editable = false) {
        $settings = $this->get_all(null, $only_editable);
        $grouped = [];

        foreach ($settings as $setting) {
            if (!isset($grouped[$setting->grupo])) {
                $grouped[$setting->grupo] = [];
            }
            $grouped[$setting->grupo][] = $setting;
        }

        return $grouped;
    }

    /**
     * Obter lista de grupos
     * 
     * @return array Lista de grupos
     */
    public function get_groups() {
        $this->db->select('grupo, COUNT(*) as total');
        $this->db->group_by('grupo');
        $this->db->order_by('grupo', 'ASC');
        
        return $this->db->get($this->table)->result();
    }

    /**
     * Atualizar múltiplas configurações
     * 
     * @param array $settings Array de chave => valor
     * @return bool Sucesso
     */
    public function update_batch($settings) {
        $success = true;

        foreach ($settings as $chave => $valor) {
            if (!$this->set($chave, $valor)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Deletar configuração
     * 
     * @param string $chave Chave da configuração
     * @return bool Sucesso
     */
    public function delete($chave) {
        $success = $this->db->delete($this->table, ['chave' => $chave]);
        
        if ($success) {
            $this->clear_cache();
        }

        return $success;
    }

    /**
     * Verificar se configuração existe
     * 
     * @param string $chave Chave da configuração
     * @return bool Existe
     */
    public function exists($chave) {
        return $this->db->where('chave', $chave)->count_all_results($this->table) > 0;
    }

    /**
     * Carregar cache de configurações
     * 
     * @return void
     */
    private function load_cache() {
        // Verificar se cache está habilitado
        $cache_enabled = $this->db->get_where($this->table, ['chave' => 'cache_enabled'])->row();
        
        if ($cache_enabled && $cache_enabled->valor == '1') {
            // Tentar carregar do cache do CodeIgniter
            $this->load->driver('cache', ['adapter' => 'file']);
            $cache_driver = $this->cache->file;
            $cached = $cache_driver->get('system_settings');
            
            if ($cached !== false) {
                $this->settings_cache = $cached;
                $this->cache_loaded = true;
                return;
            }
        }

        // Carregar do banco de dados
        $settings = $this->db->get($this->table)->result();
        
        foreach ($settings as $setting) {
            $this->settings_cache[$setting->chave] = [
                'valor' => $setting->valor,
                'tipo' => $setting->tipo
            ];
        }

        // Salvar no cache se habilitado
        if ($cache_enabled && $cache_enabled->valor == '1') {
            $duration = $this->db->get_where($this->table, ['chave' => 'cache_duration_minutes'])->row();
            $minutes = $duration ? (int)$duration->valor : 60;
            $cache_driver->save('system_settings', $this->settings_cache, $minutes * 60);
        }

        $this->cache_loaded = true;
    }

    /**
     * Limpar cache de configurações
     * 
     * @return void
     */
    public function clear_cache() {
        $this->settings_cache = [];
        $this->cache_loaded = false;
        
        // Limpar cache do CodeIgniter
        $this->load->driver('cache', ['adapter' => 'file']);
        $cache_driver = $this->cache->file;
        if ($cache_driver) {
            $cache_driver->delete('system_settings');
        }
    }

    /**
     * Converter valor baseado no tipo
     * 
     * @param array $cached_value Array com valor e tipo
     * @return mixed Valor convertido
     */
    private function _convert_value($cached_value) {
        $valor = $cached_value['valor'];
        $tipo = $cached_value['tipo'];

        switch ($tipo) {
            case 'int':
                return (int)$valor;
            
            case 'float':
                return (float)$valor;
            
            case 'bool':
                return $valor === '1' || $valor === 'true' || $valor === true;
            
            case 'json':
                return json_decode($valor, true);
            
            default:
                return $valor;
        }
    }

    /**
     * Converter valor para string baseado no tipo
     * 
     * @param mixed $valor Valor a converter
     * @param string $tipo Tipo do valor
     * @return string Valor como string
     */
    private function _value_to_string($valor, $tipo) {
        switch ($tipo) {
            case 'bool':
                return $valor ? '1' : '0';
            
            case 'json':
                return json_encode($valor);
            
            default:
                return (string)$valor;
        }
    }

    /**
     * Resetar configurações para valores padrão
     * 
     * @return bool Sucesso
     */
    public function reset_to_defaults() {
        // Esta função deve ser usada com cuidado
        // Recarrega os valores padrão da migration
        
        $this->clear_cache();
        
        // Aqui você pode implementar lógica para recarregar valores padrão
        // Por enquanto, apenas limpa o cache
        
        return true;
    }
}
