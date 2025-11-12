<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Activity_log - ConectCorretores
 * 
 * Gerenciamento de logs de atividade
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 11/11/2025 23:29
 */
class Activity_log_model extends CI_Model {

    protected $table = 'activity_logs';

    /**
     * Criar novo log de atividade
     * 
     * @param array $data Dados do log
     * @return int|bool ID do log criado ou false
     */
    public function create($data) {
        // Adicionar informações automáticas se não fornecidas
        if (!isset($data['user_id']) && $this->session->userdata('user_id')) {
            $data['user_id'] = $this->session->userdata('user_id');
        }
        
        if (!isset($data['user_name']) && $this->session->userdata('nome')) {
            $data['user_name'] = $this->session->userdata('nome');
        }
        
        if (!isset($data['ip_address'])) {
            $data['ip_address'] = $this->input->ip_address();
        }
        
        if (!isset($data['user_agent'])) {
            $data['user_agent'] = substr($this->input->user_agent(), 0, 255);
        }
        
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        
        return false;
    }

    /**
     * Buscar logs com filtros
     * 
     * @param array $filters Filtros (user_id, module, action, date_from, date_to)
     * @param int $limit Limite de registros
     * @param int $offset Offset para paginação
     * @return array Lista de logs
     */
    public function get_all($filters = [], $limit = 50, $offset = 0) {
        $this->db->select('activity_logs.*, users.email as user_email');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = activity_logs.user_id', 'left');
        
        // Aplicar filtros
        if (!empty($filters['user_id'])) {
            $this->db->where('activity_logs.user_id', $filters['user_id']);
        }
        
        if (!empty($filters['module'])) {
            $this->db->where('activity_logs.module', $filters['module']);
        }
        
        if (!empty($filters['action'])) {
            $this->db->where('activity_logs.action', $filters['action']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('activity_logs.created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('activity_logs.created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('activity_logs.description', $filters['search']);
            $this->db->or_like('activity_logs.user_name', $filters['search']);
            $this->db->group_end();
        }
        
        $this->db->order_by('activity_logs.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Contar logs com filtros
     * 
     * @param array $filters Filtros
     * @return int Total de logs
     */
    public function count_all($filters = []) {
        $this->db->from($this->table);
        
        if (!empty($filters['user_id'])) {
            $this->db->where('user_id', $filters['user_id']);
        }
        
        if (!empty($filters['module'])) {
            $this->db->where('module', $filters['module']);
        }
        
        if (!empty($filters['action'])) {
            $this->db->where('action', $filters['action']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('description', $filters['search']);
            $this->db->or_like('user_name', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Buscar logs de um usuário específico
     * 
     * @param int $user_id ID do usuário
     * @param int $limit Limite de registros
     * @return array Lista de logs
     */
    public function get_by_user($user_id, $limit = 20) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Buscar logs recentes
     * 
     * @param int $limit Limite de registros
     * @return array Lista de logs
     */
    public function get_recent($limit = 10) {
        $this->db->select('activity_logs.*, users.email as user_email');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = activity_logs.user_id', 'left');
        $this->db->order_by('activity_logs.created_at', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Obter estatísticas de logs
     * 
     * @param string $period Período (today, week, month, year)
     * @return object Estatísticas
     */
    public function get_statistics($period = 'today') {
        $stats = new stdClass();
        
        // Definir data inicial
        switch ($period) {
            case 'today':
                $date_from = date('Y-m-d');
                break;
            case 'week':
                $date_from = date('Y-m-d', strtotime('-7 days'));
                break;
            case 'month':
                $date_from = date('Y-m-01');
                break;
            case 'year':
                $date_from = date('Y-01-01');
                break;
            default:
                $date_from = date('Y-m-d');
        }
        
        // Total de ações
        $this->db->where('created_at >=', $date_from . ' 00:00:00');
        $stats->total_actions = $this->db->count_all_results($this->table);
        
        // Ações por tipo
        $this->db->select('action, COUNT(*) as count');
        $this->db->where('created_at >=', $date_from . ' 00:00:00');
        $this->db->group_by('action');
        $this->db->order_by('count', 'DESC');
        $query = $this->db->get($this->table);
        $stats->actions_by_type = $query->result();
        
        // Usuários mais ativos
        $this->db->select('user_id, user_name, COUNT(*) as count');
        $this->db->where('created_at >=', $date_from . ' 00:00:00');
        $this->db->where('user_id IS NOT NULL');
        $this->db->group_by('user_id');
        $this->db->order_by('count', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get($this->table);
        $stats->top_users = $query->result();
        
        return $stats;
    }

    /**
     * Limpar logs antigos
     * 
     * @param int $days Dias para manter (padrão: 90)
     * @return int Número de logs deletados
     */
    public function clean_old_logs($days = 90) {
        $date_limit = date('Y-m-d', strtotime("-{$days} days"));
        $this->db->where('created_at <', $date_limit . ' 00:00:00');
        $this->db->delete($this->table);
        
        return $this->db->affected_rows();
    }
}
