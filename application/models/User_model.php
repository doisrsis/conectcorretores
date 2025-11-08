<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Usuários - ConectCorretores
 * 
 * Gerencia operações relacionadas aos usuários (corretores e admin)
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class User_model extends CI_Model {

    protected $table = 'users';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Criar novo usuário
     * 
     * @param array $data Dados do usuário
     * @return int|bool ID do usuário criado ou false
     */
    public function create($data) {
        // Hash da senha
        if (isset($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        // Definir role padrão
        if (!isset($data['role'])) {
            $data['role'] = 'corretor';
        }

        // Inserir
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }

        return false;
    }

    /**
     * Buscar usuário por ID
     * 
     * @param int $id ID do usuário
     * @return object|null Dados do usuário
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    /**
     * Buscar usuário por email
     * 
     * @param string $email Email do usuário
     * @return object|null Dados do usuário
     */
    public function get_by_email($email) {
        return $this->db->get_where($this->table, ['email' => $email])->row();
    }

    /**
     * Verificar se email já existe
     * 
     * @param string $email Email para verificar
     * @param int $exclude_id ID para excluir da verificação (útil em updates)
     * @return bool True se existe
     */
    public function email_exists($email, $exclude_id = null) {
        $this->db->where('email', $email);
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }

        return $this->db->count_all_results($this->table) > 0;
    }

    /**
     * Autenticar usuário
     * 
     * @param string $email Email
     * @param string $senha Senha
     * @return object|bool Dados do usuário ou false
     */
    public function authenticate($email, $senha) {
        $user = $this->get_by_email($email);

        if ($user && password_verify($senha, $user->senha)) {
            // Remover senha do objeto antes de retornar
            unset($user->senha);
            return $user;
        }

        return false;
    }

    /**
     * Atualizar usuário
     * 
     * @param int $id ID do usuário
     * @param array $data Dados para atualizar
     * @return bool Sucesso
     */
    public function update($id, $data) {
        // Se estiver atualizando senha, fazer hash
        if (isset($data['senha'])) {
            $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }

        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Deletar usuário
     * 
     * @param int $id ID do usuário
     * @return bool Sucesso
     */
    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /**
     * Listar todos os usuários
     * 
     * @param array $filters Filtros opcionais
     * @param int $limit Limite de resultados
     * @param int $offset Offset para paginação
     * @return array Lista de usuários
     */
    public function get_all($filters = [], $limit = null, $offset = 0) {
        $this->db->select('id, nome, email, cpf, telefone, whatsapp, role, ativo, created_at');
        
        // Aplicar filtros
        if (isset($filters['role'])) {
            $this->db->where('role', $filters['role']);
        }

        if (isset($filters['ativo'])) {
            $this->db->where('ativo', $filters['ativo']);
        }

        if (isset($filters['search'])) {
            $this->db->group_start();
            $this->db->like('nome', $filters['search']);
            $this->db->or_like('email', $filters['search']);
            $this->db->group_end();
        }

        // Ordenação
        $this->db->order_by('created_at', 'DESC');

        // Paginação
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get($this->table)->result();
    }

    /**
     * Contar usuários
     * 
     * @param array $filters Filtros opcionais
     * @return int Total de usuários
     */
    public function count_all($filters = []) {
        if (isset($filters['role'])) {
            $this->db->where('role', $filters['role']);
        }

        if (isset($filters['ativo'])) {
            $this->db->where('ativo', $filters['ativo']);
        }

        if (isset($filters['search'])) {
            $this->db->group_start();
            $this->db->like('nome', $filters['search']);
            $this->db->or_like('email', $filters['search']);
            $this->db->group_end();
        }

        return $this->db->count_all_results($this->table);
    }

    /**
     * Ativar/Desativar usuário
     * 
     * @param int $id ID do usuário
     * @param bool $ativo Status
     * @return bool Sucesso
     */
    public function toggle_status($id, $ativo) {
        return $this->update($id, ['ativo' => $ativo ? 1 : 0]);
    }

    /**
     * Verificar se usuário tem assinatura ativa
     * Considera 'ativa' e 'pendente' (período de graça) como ativas
     * 
     * @param int $user_id ID do usuário
     * @return bool True se tem assinatura ativa
     */
    public function has_active_subscription($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where_in('status', ['ativa', 'pendente']); // Incluir pendente (período de graça)
        $this->db->where('data_fim >=', date('Y-m-d'));
        
        return $this->db->count_all_results('subscriptions') > 0;
    }

    /**
     * Obter estatísticas do usuário
     * 
     * @param int $user_id ID do usuário
     * @return object Estatísticas
     */
    public function get_stats($user_id) {
        $stats = new stdClass();

        // Total de imóveis
        $this->db->where('user_id', $user_id);
        $this->db->where('ativo', 1);
        $stats->total_imoveis = $this->db->count_all_results('imoveis');

        // Imóveis para venda
        $this->db->where('user_id', $user_id);
        $this->db->where('tipo_negocio', 'compra');
        $this->db->where('ativo', 1);
        $stats->imoveis_venda = $this->db->count_all_results('imoveis');

        // Imóveis para aluguel
        $this->db->where('user_id', $user_id);
        $this->db->where('tipo_negocio', 'aluguel');
        $this->db->where('ativo', 1);
        $stats->imoveis_aluguel = $this->db->count_all_results('imoveis');

        // Assinatura ativa
        $stats->has_subscription = $this->has_active_subscription($user_id);

        return $stats;
    }

    /**
     * Buscar corretores (apenas role corretor)
     * 
     * @param int $limit Limite
     * @param int $offset Offset
     * @return array Lista de corretores
     */
    public function get_corretores($limit = null, $offset = 0) {
        return $this->get_all(['role' => 'corretor'], $limit, $offset);
    }

    /**
     * Contar corretores
     * 
     * @return int Total de corretores
     */
    public function count_corretores() {
        return $this->count_all(['role' => 'corretor']);
    }
}
