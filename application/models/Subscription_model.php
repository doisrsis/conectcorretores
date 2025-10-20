<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Assinaturas - ConectCorretores
 * 
 * Gerencia operações relacionadas às assinaturas dos corretores
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Subscription_model extends CI_Model {

    protected $table = 'subscriptions';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Criar nova assinatura
     * 
     * @param array $data Dados da assinatura
     * @return int|bool ID da assinatura criada ou false
     */
    public function create($data) {
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Buscar assinatura por ID
     * 
     * @param int $id ID da assinatura
     * @return object|null Dados da assinatura
     */
    public function get_by_id($id) {
        $this->db->select('subscriptions.*, plans.nome as plan_nome, plans.tipo as plan_tipo, plans.preco as plan_preco, plans.descricao as plan_descricao, plans.limite_imoveis as plan_limite_imoveis, plans.stripe_price_id as plan_stripe_price_id');
        $this->db->from($this->table);
        $this->db->join('plans', 'plans.id = subscriptions.plan_id');
        $this->db->where('subscriptions.id', $id);

        return $this->db->get()->row();
    }

    /**
     * Buscar assinatura ativa do usuário
     * 
     * @param int $user_id ID do usuário
     * @return object|null Assinatura ativa
     */
    public function get_active_by_user($user_id) {
        $this->db->select('subscriptions.*, plans.nome as plan_nome, plans.tipo as plan_tipo, plans.preco as plan_preco, plans.descricao as plan_descricao, plans.limite_imoveis as plan_limite_imoveis, plans.stripe_price_id as plan_stripe_price_id');
        $this->db->from($this->table);
        $this->db->join('plans', 'plans.id = subscriptions.plan_id');
        $this->db->where('subscriptions.user_id', $user_id);
        $this->db->where('subscriptions.status', 'ativa');
        $this->db->where('subscriptions.data_fim >=', date('Y-m-d'));
        $this->db->order_by('subscriptions.data_fim', 'DESC');
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    /**
     * Buscar todas as assinaturas do usuário
     * 
     * @param int $user_id ID do usuário
     * @return array Lista de assinaturas
     */
    public function get_by_user($user_id) {
        $this->db->select('subscriptions.*, plans.nome as plan_nome, plans.tipo as plan_tipo, plans.preco as plan_preco, plans.descricao as plan_descricao, plans.limite_imoveis as plan_limite_imoveis, plans.stripe_price_id as plan_stripe_price_id');
        $this->db->from($this->table);
        $this->db->join('plans', 'plans.id = subscriptions.plan_id');
        $this->db->where('subscriptions.user_id', $user_id);
        $this->db->order_by('subscriptions.created_at', 'DESC');

        return $this->db->get()->result();
    }

    /**
     * Buscar assinatura por Stripe Subscription ID
     * 
     * @param string $stripe_subscription_id ID da assinatura no Stripe
     * @return object|null Assinatura
     */
    public function get_by_stripe_id($stripe_subscription_id) {
        return $this->db->get_where($this->table, [
            'stripe_subscription_id' => $stripe_subscription_id
        ])->row();
    }

    /**
     * Listar todas as assinaturas
     * 
     * @param array $filters Filtros opcionais
     * @param int $limit Limite de resultados
     * @param int $offset Offset para paginação
     * @return array Lista de assinaturas
     */
    public function get_all($filters = [], $limit = null, $offset = 0) {
        $this->db->select('subscriptions.*, plans.nome as plan_nome, plans.tipo as plan_tipo, plans.preco as plan_preco, plans.descricao as plan_descricao, plans.limite_imoveis as plan_limite_imoveis, plans.stripe_price_id as plan_stripe_price_id, users.nome as user_nome, users.email as user_email');
        $this->db->from($this->table);
        $this->db->join('plans', 'plans.id = subscriptions.plan_id');
        $this->db->join('users', 'users.id = subscriptions.user_id');

        // Aplicar filtros
        if (isset($filters['status'])) {
            // Se for array, usar where_in
            if (is_array($filters['status'])) {
                $this->db->where_in('subscriptions.status', $filters['status']);
            } else {
                $this->db->where('subscriptions.status', $filters['status']);
            }
        }

        if (isset($filters['plan_id'])) {
            $this->db->where('subscriptions.plan_id', $filters['plan_id']);
        }

        if (isset($filters['user_id'])) {
            $this->db->where('subscriptions.user_id', $filters['user_id']);
        }

        // Ordenação
        $this->db->order_by('subscriptions.created_at', 'DESC');

        // Paginação
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    /**
     * Contar assinaturas
     * 
     * @param array $filters Filtros opcionais
     * @return int Total de assinaturas
     */
    public function count_all($filters = []) {
        if (isset($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }

        if (isset($filters['plan_id'])) {
            $this->db->where('plan_id', $filters['plan_id']);
        }

        if (isset($filters['user_id'])) {
            $this->db->where('user_id', $filters['user_id']);
        }

        return $this->db->count_all_results($this->table);
    }

    /**
     * Atualizar assinatura
     * 
     * @param int $id ID da assinatura
     * @param array $data Dados para atualizar
     * @return bool Sucesso
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Cancelar assinatura
     * 
     * @param int $id ID da assinatura
     * @return bool Sucesso
     */
    public function cancel($id) {
        return $this->update($id, [
            'status' => 'cancelada',
            'cancelada_em' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Ativar assinatura
     * 
     * @param int $id ID da assinatura
     * @return bool Sucesso
     */
    public function activate($id) {
        return $this->update($id, [
            'status' => 'ativa'
        ]);
    }

    /**
     * Verificar se assinatura está expirada
     * 
     * @param int $id ID da assinatura
     * @return bool True se expirada
     */
    public function is_expired($id) {
        $subscription = $this->get_by_id($id);
        
        if (!$subscription) {
            return true;
        }

        return strtotime($subscription->data_fim) < time();
    }

    /**
     * Expirar assinaturas vencidas (executar via cron)
     * 
     * @return int Número de assinaturas expiradas
     */
    public function expire_old_subscriptions() {
        $this->db->where('status', 'ativa');
        $this->db->where('data_fim <', date('Y-m-d'));
        $this->db->update($this->table, ['status' => 'expirada']);

        return $this->db->affected_rows();
    }

    /**
     * Obter assinaturas que vão expirar em X dias
     * 
     * @param int $days Número de dias
     * @return array Lista de assinaturas
     */
    public function get_expiring_soon($days = 7) {
        $date_limit = date('Y-m-d', strtotime('+' . $days . ' days'));

        $this->db->select('subscriptions.*, users.nome, users.email, plans.nome as plan_nome, plans.tipo as plan_tipo, plans.preco as plan_preco');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = subscriptions.user_id');
        $this->db->join('plans', 'plans.id = subscriptions.plan_id');
        $this->db->where('subscriptions.status', 'ativa');
        $this->db->where('subscriptions.data_fim <=', $date_limit);
        $this->db->where('subscriptions.data_fim >=', date('Y-m-d'));

        return $this->db->get()->result();
    }

    /**
     * Calcular receita total
     * 
     * @param string $status Status das assinaturas (null = todas)
     * @return float Receita total
     */
    public function calculate_revenue($status = 'ativa') {
        $this->db->select_sum('plans.preco');
        $this->db->from($this->table);
        $this->db->join('plans', 'plans.id = subscriptions.plan_id');

        if ($status) {
            $this->db->where('subscriptions.status', $status);
        }

        $result = $this->db->get()->row();
        return $result->preco ? (float)$result->preco : 0;
    }

    /**
     * Obter últimas assinaturas
     * 
     * @param int $limit Limite
     * @return array Lista de assinaturas
     */
    public function get_recent($limit = 10) {
        return $this->get_all([], $limit, 0);
    }

    /**
     * Deletar assinatura
     * 
     * @param int $id ID da assinatura
     * @return bool Sucesso
     */
    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}
