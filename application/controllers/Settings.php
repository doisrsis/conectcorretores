<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Settings - ConectCorretores
 * 
 * Gerenciamento de configurações do sistema (Admin)
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 08/11/2025
 */
class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // Verificar se é admin
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Acesso negado. Apenas administradores podem acessar esta área.');
            redirect('dashboard');
        }

        $this->load->model('Settings_model');
        $this->load->helper('settings');
    }

    /**
     * Página principal de configurações
     */
    public function index() {
        // Redirecionar para aba de assinaturas por padrão
        redirect('settings/assinaturas');
    }

    /**
     * Configurações de Assinaturas
     */
    public function assinaturas() {
        if ($this->input->post()) {
            $this->_save_settings('assinaturas');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('assinaturas', true);
        $data['title'] = 'Configurações de Assinaturas - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'assinaturas';

        $this->load->view('admin/settings/assinaturas', $data);
    }

    /**
     * Configurações do Site
     */
    public function site() {
        if ($this->input->post()) {
            $this->_save_settings('site');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('site', true);
        $data['title'] = 'Configurações do Site - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'site';

        $this->load->view('admin/settings/site', $data);
    }

    /**
     * Configurações de Email
     */
    public function email() {
        if ($this->input->post()) {
            $this->_save_settings('email');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('email', true);
        $data['title'] = 'Configurações de Email - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'email';

        $this->load->view('admin/settings/email', $data);
    }

    /**
     * Configurações de Segurança
     */
    public function seguranca() {
        if ($this->input->post()) {
            $this->_save_settings('seguranca');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('seguranca', true);
        $data['title'] = 'Configurações de Segurança - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'seguranca';

        $this->load->view('admin/settings/seguranca', $data);
    }

    /**
     * Configurações de Imóveis
     */
    public function imoveis() {
        if ($this->input->post()) {
            $this->_save_settings('imoveis');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('imoveis', true);
        $data['title'] = 'Configurações de Imóveis - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'imoveis';

        $this->load->view('admin/settings/imoveis', $data);
    }

    /**
     * Configurações do Sistema
     */
    public function sistema() {
        if ($this->input->post()) {
            $this->_save_settings('sistema');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('sistema', true);
        $data['title'] = 'Configurações do Sistema - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'sistema';

        $this->load->view('admin/settings/sistema', $data);
    }

    /**
     * Todas as configurações (visualização)
     */
    public function todas() {
        $data['grouped_settings'] = $this->Settings_model->get_grouped(false);
        $data['title'] = 'Todas as Configurações - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'todas';

        $this->load->view('admin/settings/todas', $data);
    }

    /**
     * Limpar cache de configurações
     */
    public function limpar_cache() {
        $this->Settings_model->clear_cache();
        
        $this->session->set_flashdata('success', 'Cache de configurações limpo com sucesso!');
        redirect($this->input->server('HTTP_REFERER') ?: 'settings');
    }

    /**
     * Salvar configurações
     * 
     * @param string $grupo Grupo das configurações
     */
    private function _save_settings($grupo) {
        $settings = $this->Settings_model->get_all($grupo, true);
        $updates = [];
        $errors = [];

        foreach ($settings as $setting) {
            $chave = $setting->chave;
            $valor = $this->input->post($chave);

            // Tratar checkbox (bool)
            if ($setting->tipo === 'bool') {
                $valor = $valor ? '1' : '0';
            }

            // Validar valor
            if (!$this->_validate_setting($valor, $setting->tipo)) {
                $errors[] = "Valor inválido para '{$setting->chave}'";
                continue;
            }

            $updates[$chave] = $valor;
        }

        if (!empty($errors)) {
            $this->session->set_flashdata('error', implode('<br>', $errors));
            redirect('settings/' . $grupo);
            return;
        }

        // Salvar todas as configurações
        if ($this->Settings_model->update_batch($updates)) {
            $this->session->set_flashdata('success', 'Configurações salvas com sucesso!');
            
            // Log da alteração
            log_message('info', "Configurações do grupo '{$grupo}' atualizadas pelo admin ID: " . $this->session->userdata('user_id'));
        } else {
            $this->session->set_flashdata('error', 'Erro ao salvar configurações.');
        }

        redirect('settings/' . $grupo);
    }

    /**
     * Validar valor de configuração
     * 
     * @param mixed $valor Valor a validar
     * @param string $tipo Tipo esperado
     * @return bool Válido
     */
    private function _validate_setting($valor, $tipo) {
        switch ($tipo) {
            case 'int':
                return is_numeric($valor) && (int)$valor == $valor && (int)$valor >= 0;
            
            case 'float':
                return is_numeric($valor) && (float)$valor >= 0;
            
            case 'bool':
                return in_array($valor, ['0', '1'], true);
            
            case 'json':
                if (empty($valor)) return true;
                json_decode($valor);
                return json_last_error() === JSON_ERROR_NONE;
            
            default:
                return true;
        }
    }

    /**
     * API: Obter valor de configuração (AJAX)
     */
    public function get_value() {
        $chave = $this->input->get('chave');
        
        if (!$chave) {
            echo json_encode(['success' => false, 'error' => 'Chave não informada']);
            return;
        }

        $valor = $this->Settings_model->get($chave);
        
        echo json_encode([
            'success' => true,
            'chave' => $chave,
            'valor' => $valor
        ]);
    }

    /**
     * API: Definir valor de configuração (AJAX)
     */
    public function set_value() {
        $chave = $this->input->post('chave');
        $valor = $this->input->post('valor');
        
        if (!$chave) {
            echo json_encode(['success' => false, 'error' => 'Chave não informada']);
            return;
        }

        $success = $this->Settings_model->set($chave, $valor);
        
        echo json_encode([
            'success' => $success,
            'chave' => $chave,
            'valor' => $valor
        ]);
    }
}
