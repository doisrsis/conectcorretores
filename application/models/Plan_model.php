<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model de Planos - ConectCorretores
 * 
 * Gerencia operações relacionadas aos planos de assinatura
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Plan_model extends CI_Model {

    protected $table = 'plans';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Criar novo plano
     * 
     * @param array $data Dados do plano
     * @return int|bool ID do plano criado ou false
     */
    public function create($data) {
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Buscar plano por ID
     * 
     * @param int $id ID do plano
     * @return object|null Dados do plano
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    /**
     * Listar todos os planos
     * 
     * @param bool $only_active Apenas planos ativos
     * @return array Lista de planos
     */
    public function get_all($only_active = true) {
        if ($only_active) {
            $this->db->where('ativo', 1);
        }

        $this->db->order_by('preco', 'ASC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Buscar planos por tipo
     * 
     * @param string $tipo Tipo do plano (mensal, trimestral, etc)
     * @return array Lista de planos
     */
    public function get_by_tipo($tipo) {
        $this->db->where('tipo', $tipo);
        $this->db->where('ativo', 1);
        return $this->db->get($this->table)->result();
    }

    /**
     * Atualizar plano
     * 
     * @param int $id ID do plano
     * @param array $data Dados para atualizar
     * @return bool Sucesso
     */
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Deletar plano
     * 
     * @param int $id ID do plano
     * @return bool Sucesso
     */
    public function delete($id) {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /**
     * Ativar/Desativar plano
     * 
     * @param int $id ID do plano
     * @param bool $ativo Status
     * @return bool Sucesso
     */
    public function toggle_status($id, $ativo) {
        return $this->update($id, ['ativo' => $ativo ? 1 : 0]);
    }

    /**
     * Contar total de planos
     * 
     * @param bool $only_active Apenas ativos
     * @return int Total
     */
    public function count_all($only_active = false) {
        if ($only_active) {
            $this->db->where('ativo', 1);
        }
        return $this->db->count_all_results($this->table);
    }

    /**
     * Buscar plano mais popular (com mais assinaturas)
     * 
     * @return object|null Plano mais popular
     */
    public function get_most_popular() {
        $this->db->select('plans.*, COUNT(subscriptions.id) as total_subscriptions');
        $this->db->from($this->table);
        $this->db->join('subscriptions', 'subscriptions.plan_id = plans.id', 'left');
        $this->db->where('plans.ativo', 1);
        $this->db->group_by('plans.id');
        $this->db->order_by('total_subscriptions', 'DESC');
        $this->db->limit(1);

        return $this->db->get()->row();
    }

    /**
     * Calcular duração em dias baseado no tipo
     * 
     * @param string $tipo Tipo do plano
     * @return int Dias de duração
     */
    public function get_duration_days($tipo) {
        $durations = [
            'mensal' => 30,
            'trimestral' => 90,
            'semestral' => 180,
            'anual' => 365
        ];

        return isset($durations[$tipo]) ? $durations[$tipo] : 30;
    }

    /**
     * Calcular data de expiração
     * 
     * @param string $tipo Tipo do plano
     * @param string $start_date Data de início (Y-m-d)
     * @return string Data de expiração (Y-m-d)
     */
    public function calculate_expiration_date($tipo, $start_date = null) {
        if (!$start_date) {
            $start_date = date('Y-m-d');
        }

        $days = $this->get_duration_days($tipo);
        return date('Y-m-d', strtotime($start_date . ' + ' . $days . ' days'));
    }
    
    /**
     * Buscar plano por stripe_product_id
     * 
     * @param string $stripe_product_id ID do produto no Stripe
     * @return object|null Dados do plano
     */
    public function get_by_stripe_product_id($stripe_product_id) {
        return $this->db->get_where($this->table, ['stripe_product_id' => $stripe_product_id])->row();
    }
}
