<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Imóveis - ConectCorretores
 * 
 * Gerencia operações relacionadas aos imóveis cadastrados
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Imovel_model extends CI_Model {

    protected $table = 'imoveis';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Criar novo imóvel
     * 
     * @param array $data Dados do imóvel
     * @return int|bool ID do imóvel criado ou false
     */
    public function create($data) {
        // Calcular valor por m² se não fornecido
        if (!isset($data['valor_m2']) && isset($data['preco']) && isset($data['area_privativa'])) {
            if ($data['area_privativa'] > 0) {
                $data['valor_m2'] = $data['preco'] / $data['area_privativa'];
            }
        }

        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Buscar imóvel por ID
     * 
     * @param int $id ID do imóvel
     * @param int $user_id ID do usuário (opcional, para verificar permissão)
     * @return object|null Dados do imóvel
     */
    public function get_by_id($id, $user_id = null) {
        $this->db->select('imoveis.*, users.nome as corretor_nome, users.email as corretor_email, users.telefone as corretor_telefone');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = imoveis.user_id');
        $this->db->where('imoveis.id', $id);

        if ($user_id) {
            $this->db->where('imoveis.user_id', $user_id);
        }

        return $this->db->get()->row();
    }

    /**
     * Listar imóveis
     * 
     * @param array $filters Filtros opcionais
     * @param int $limit Limite de resultados
     * @param int $offset Offset para paginação
     * @return array Lista de imóveis
     */
    public function get_all($filters = [], $limit = null, $offset = 0) {
        $this->db->select('imoveis.*, users.nome as corretor_nome, users.email as corretor_email');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = imoveis.user_id');

        // Filtro por usuário
        if (isset($filters['user_id'])) {
            $this->db->where('imoveis.user_id', $filters['user_id']);
        }

        // Filtro por tipo de negócio
        if (isset($filters['tipo_negocio'])) {
            $this->db->where('imoveis.tipo_negocio', $filters['tipo_negocio']);
        }

        // Filtro por tipo de imóvel
        if (isset($filters['tipo_imovel'])) {
            $this->db->where('imoveis.tipo_imovel', $filters['tipo_imovel']);
        }

        // Filtro por estado
        if (isset($filters['estado'])) {
            $this->db->where('imoveis.estado', $filters['estado']);
        }

        // Filtro por cidade
        if (isset($filters['cidade'])) {
            $this->db->where('imoveis.cidade', $filters['cidade']);
        }

        // Filtro por bairro
        if (isset($filters['bairro'])) {
            $this->db->like('imoveis.bairro', $filters['bairro']);
        }

        // Filtro por quartos
        if (isset($filters['quartos'])) {
            $this->db->where('imoveis.quartos >=', $filters['quartos']);
        }

        // Filtro por vagas
        if (isset($filters['vagas'])) {
            $this->db->where('imoveis.vagas >=', $filters['vagas']);
        }

        // Filtro por preço mínimo
        if (isset($filters['preco_min'])) {
            $this->db->where('imoveis.preco >=', $filters['preco_min']);
        }

        // Filtro por preço máximo
        if (isset($filters['preco_max'])) {
            $this->db->where('imoveis.preco <=', $filters['preco_max']);
        }

        // Filtro por área mínima
        if (isset($filters['area_min'])) {
            $this->db->where('imoveis.area_privativa >=', $filters['area_min']);
        }

        // Filtro por área máxima
        if (isset($filters['area_max'])) {
            $this->db->where('imoveis.area_privativa <=', $filters['area_max']);
        }

        // Busca textual
        if (isset($filters['search'])) {
            $this->db->group_start();
            $this->db->like('imoveis.descricao', $filters['search']);
            $this->db->or_like('imoveis.endereco', $filters['search']);
            $this->db->or_like('imoveis.bairro', $filters['search']);
            $this->db->or_like('imoveis.cidade', $filters['search']);
            $this->db->group_end();
        }

        // Apenas ativos
        if (!isset($filters['include_inactive']) || !$filters['include_inactive']) {
            $this->db->where('imoveis.ativo', 1);
        }

        // Apenas destaques
        if (isset($filters['destaque']) && $filters['destaque']) {
            $this->db->where('imoveis.destaque', 1);
        }

        // Ordenação
        $order_by = isset($filters['order_by']) ? $filters['order_by'] : 'created_at';
        $order_dir = isset($filters['order_dir']) ? $filters['order_dir'] : 'DESC';
        $this->db->order_by('imoveis.' . $order_by, $order_dir);

        // Paginação
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    /**
     * Contar imóveis
     * 
     * @param array $filters Filtros opcionais
     * @return int Total de imóveis
     */
    public function count_all($filters = []) {
        // Aplicar os mesmos filtros do get_all
        if (isset($filters['user_id'])) {
            $this->db->where('user_id', $filters['user_id']);
        }

        if (isset($filters['tipo_negocio'])) {
            $this->db->where('tipo_negocio', $filters['tipo_negocio']);
        }

        if (isset($filters['tipo_imovel'])) {
            $this->db->where('tipo_imovel', $filters['tipo_imovel']);
        }

        if (isset($filters['estado'])) {
            $this->db->where('estado', $filters['estado']);
        }

        if (isset($filters['cidade'])) {
            $this->db->where('cidade', $filters['cidade']);
        }

        if (isset($filters['search'])) {
            $this->db->group_start();
            $this->db->like('descricao', $filters['search']);
            $this->db->or_like('endereco', $filters['search']);
            $this->db->or_like('bairro', $filters['search']);
            $this->db->or_like('cidade', $filters['search']);
            $this->db->group_end();
        }

        if (!isset($filters['include_inactive']) || !$filters['include_inactive']) {
            $this->db->where('ativo', 1);
        }

        return $this->db->count_all_results($this->table);
    }

    /**
     * Atualizar imóvel
     * 
     * @param int $id ID do imóvel
     * @param array $data Dados para atualizar
     * @param int $user_id ID do usuário (para verificar permissão)
     * @return bool Sucesso
     */
    public function update($id, $data, $user_id = null) {
        // Recalcular valor por m² se preço ou área mudou
        if ((isset($data['preco']) || isset($data['area_privativa'])) && !isset($data['valor_m2'])) {
            $imovel = $this->get_by_id($id);
            $preco = isset($data['preco']) ? $data['preco'] : $imovel->preco;
            $area = isset($data['area_privativa']) ? $data['area_privativa'] : $imovel->area_privativa;
            
            if ($area > 0) {
                $data['valor_m2'] = $preco / $area;
            }
        }

        $this->db->where('id', $id);
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }

        return $this->db->update($this->table, $data);
    }

    /**
     * Deletar imóvel
     * 
     * @param int $id ID do imóvel
     * @param int $user_id ID do usuário (para verificar permissão)
     * @return bool Sucesso
     */
    public function delete($id, $user_id = null) {
        $where = ['id' => $id];
        
        if ($user_id) {
            $where['user_id'] = $user_id;
        }

        return $this->db->delete($this->table, $where);
    }

    /**
     * Ativar/Desativar imóvel
     * 
     * @param int $id ID do imóvel
     * @param bool $ativo Status
     * @param int $user_id ID do usuário (para verificar permissão)
     * @return bool Sucesso
     */
    public function toggle_status($id, $ativo, $user_id = null) {
        return $this->update($id, ['ativo' => $ativo ? 1 : 0], $user_id);
    }

    /**
     * Marcar/Desmarcar como destaque
     * 
     * @param int $id ID do imóvel
     * @param bool $destaque Status
     * @param int $user_id ID do usuário (para verificar permissão)
     * @return bool Sucesso
     */
    public function toggle_destaque($id, $destaque, $user_id = null) {
        return $this->update($id, ['destaque' => $destaque ? 1 : 0], $user_id);
    }

    /**
     * Obter tipos de imóveis únicos
     * 
     * @return array Lista de tipos
     */
    public function get_tipos_imoveis() {
        $this->db->distinct();
        $this->db->select('tipo_imovel');
        $this->db->where('ativo', 1);
        $this->db->order_by('tipo_imovel', 'ASC');

        $result = $this->db->get($this->table)->result();
        return array_column($result, 'tipo_imovel');
    }

    /**
     * Obter estados únicos
     * 
     * @return array Lista de estados
     */
    public function get_estados() {
        $this->db->distinct();
        $this->db->select('estado');
        $this->db->where('ativo', 1);
        $this->db->order_by('estado', 'ASC');

        $result = $this->db->get($this->table)->result();
        return array_column($result, 'estado');
    }

    /**
     * Obter cidades por estado
     * 
     * @param string $estado UF do estado
     * @return array Lista de cidades
     */
    public function get_cidades_by_estado($estado) {
        $this->db->distinct();
        $this->db->select('cidade');
        $this->db->where('estado', $estado);
        $this->db->where('ativo', 1);
        $this->db->order_by('cidade', 'ASC');

        $result = $this->db->get($this->table)->result();
        return array_column($result, 'cidade');
    }

    /**
     * Obter estatísticas de imóveis
     * 
     * @param int $user_id ID do usuário (opcional)
     * @return object Estatísticas
     */
    public function get_stats($user_id = null) {
        $stats = new stdClass();

        // Total de imóveis
        $filters = ['include_inactive' => false];
        if ($user_id) {
            $filters['user_id'] = $user_id;
        }
        $stats->total = $this->count_all($filters);

        // Por tipo de negócio
        $filters['tipo_negocio'] = 'compra';
        $stats->venda = $this->count_all($filters);

        $filters['tipo_negocio'] = 'aluguel';
        $stats->aluguel = $this->count_all($filters);

        // Valor médio
        $this->db->select_avg('preco');
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        $this->db->where('ativo', 1);
        $result = $this->db->get($this->table)->row();
        $stats->preco_medio = $result->preco ? (float)$result->preco : 0;

        return $stats;
    }
}
