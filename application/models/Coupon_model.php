<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Coupon - ConectCorretores
 * 
 * Gerenciamento de cupons de desconto
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 10/11/2025
 */
class Coupon_model extends CI_Model {

    protected $table = 'coupons';
    protected $usage_table = 'coupon_usage';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Buscar todos os cupons
     * 
     * @param bool $only_active Apenas cupons ativos
     * @return array
     */
    public function get_all($only_active = false) {
        $this->db->select('coupons.*, COUNT(coupon_usage.id) as total_usos');
        $this->db->from($this->table);
        $this->db->join($this->usage_table, 'coupon_usage.coupon_id = coupons.id', 'left');
        
        if ($only_active) {
            $this->db->where('coupons.ativo', 1);
            $this->db->group_start();
            $this->db->where('coupons.valido_ate >=', date('Y-m-d'));
            $this->db->or_where('coupons.valido_ate IS NULL');
            $this->db->group_end();
        }
        
        $this->db->group_by('coupons.id');
        $this->db->order_by('coupons.created_at', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Buscar cupom por ID
     * 
     * @param int $id
     * @return object|null
     */
    public function get_by_id($id) {
        $this->db->select('coupons.*, COUNT(coupon_usage.id) as total_usos');
        $this->db->from($this->table);
        $this->db->join($this->usage_table, 'coupon_usage.coupon_id = coupons.id', 'left');
        $this->db->where('coupons.id', $id);
        $this->db->group_by('coupons.id');
        
        return $this->db->get()->row();
    }

    /**
     * Buscar cupom por código
     * 
     * @param string $codigo
     * @return object|null
     */
    public function get_by_code($codigo) {
        $this->db->select('coupons.*, COUNT(coupon_usage.id) as total_usos');
        $this->db->from($this->table);
        $this->db->join($this->usage_table, 'coupon_usage.coupon_id = coupons.id', 'left');
        $this->db->where('UPPER(coupons.codigo)', strtoupper($codigo));
        $this->db->group_by('coupons.id');
        
        return $this->db->get()->row();
    }

    /**
     * Criar novo cupom
     * 
     * @param array $data
     * @return int|false ID do cupom criado ou false
     */
    public function create($data) {
        // Converter código para maiúsculas
        if (isset($data['codigo'])) {
            $data['codigo'] = strtoupper($data['codigo']);
        }
        
        // Validar duracao_meses
        if ($data['duracao'] !== 'repeating') {
            $data['duracao_meses'] = null;
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        
        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        
        return false;
    }

    /**
     * Atualizar cupom
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        // Converter código para maiúsculas
        if (isset($data['codigo'])) {
            $data['codigo'] = strtoupper($data['codigo']);
        }
        
        // Validar duracao_meses
        if (isset($data['duracao']) && $data['duracao'] !== 'repeating') {
            $data['duracao_meses'] = null;
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Deletar cupom
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Validar cupom
     * 
     * @param string $codigo
     * @param int $user_id
     * @return array ['valid' => bool, 'message' => string, 'coupon' => object|null]
     */
    public function validate($codigo, $user_id = null) {
        $coupon = $this->get_by_code($codigo);
        
        // Cupom não existe
        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Cupom não encontrado.',
                'coupon' => null
            ];
        }
        
        // Cupom inativo
        if (!$coupon->ativo) {
            return [
                'valid' => false,
                'message' => 'Cupom inativo.',
                'coupon' => null
            ];
        }
        
        // Verificar validade (data início)
        if ($coupon->valido_de && strtotime($coupon->valido_de) > time()) {
            return [
                'valid' => false,
                'message' => 'Cupom ainda não está válido.',
                'coupon' => null
            ];
        }
        
        // Verificar validade (data fim)
        if ($coupon->valido_ate && strtotime($coupon->valido_ate) < time()) {
            return [
                'valid' => false,
                'message' => 'Cupom expirado.',
                'coupon' => null
            ];
        }
        
        // Verificar limite de usos
        if ($coupon->max_usos && $coupon->total_usos >= $coupon->max_usos) {
            return [
                'valid' => false,
                'message' => 'Cupom atingiu o limite de usos.',
                'coupon' => null
            ];
        }
        
        // Verificar se usuário já usou este cupom
        if ($user_id && $this->user_already_used($coupon->id, $user_id)) {
            return [
                'valid' => false,
                'message' => 'Você já utilizou este cupom.',
                'coupon' => null
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Cupom válido!',
            'coupon' => $coupon
        ];
    }

    /**
     * Verificar se usuário já usou o cupom
     * 
     * @param int $coupon_id
     * @param int $user_id
     * @return bool
     */
    public function user_already_used($coupon_id, $user_id) {
        $this->db->where('coupon_id', $coupon_id);
        $this->db->where('user_id', $user_id);
        $count = $this->db->count_all_results($this->usage_table);
        
        return $count > 0;
    }

    /**
     * Registrar uso do cupom
     * 
     * @param int $coupon_id
     * @param int $user_id
     * @param int $subscription_id
     * @param float $desconto_aplicado
     * @param float $valor_original
     * @param float $valor_final
     * @return int|false ID do registro ou false
     */
    public function register_usage($coupon_id, $user_id, $subscription_id, $desconto_aplicado, $valor_original, $valor_final) {
        $data = [
            'coupon_id' => $coupon_id,
            'user_id' => $user_id,
            'subscription_id' => $subscription_id,
            'desconto_aplicado' => $desconto_aplicado,
            'valor_original' => $valor_original,
            'valor_final' => $valor_final,
            'usado_em' => date('Y-m-d H:i:s')
        ];
        
        if ($this->db->insert($this->usage_table, $data)) {
            // Incrementar contador de usos
            $this->db->set('usos_atuais', 'usos_atuais + 1', false);
            $this->db->where('id', $coupon_id);
            $this->db->update($this->table);
            
            return $this->db->insert_id();
        }
        
        return false;
    }

    /**
     * Calcular desconto
     * 
     * @param object $coupon
     * @param float $valor_original
     * @return array ['desconto' => float, 'valor_final' => float]
     */
    public function calculate_discount($coupon, $valor_original) {
        $desconto = 0;
        
        if ($coupon->tipo === 'percent') {
            // Desconto percentual
            $desconto = ($valor_original * $coupon->valor) / 100;
        } else {
            // Desconto fixo
            $desconto = $coupon->valor;
        }
        
        // Garantir que desconto não seja maior que o valor original
        if ($desconto > $valor_original) {
            $desconto = $valor_original;
        }
        
        $valor_final = $valor_original - $desconto;
        
        return [
            'desconto' => round($desconto, 2),
            'valor_final' => round($valor_final, 2)
        ];
    }

    /**
     * Buscar histórico de uso de um cupom
     * 
     * @param int $coupon_id
     * @return array
     */
    public function get_usage_history($coupon_id) {
        $this->db->select('coupon_usage.*, users.nome as user_nome, users.email as user_email');
        $this->db->from($this->usage_table);
        $this->db->join('users', 'users.id = coupon_usage.user_id');
        $this->db->where('coupon_usage.coupon_id', $coupon_id);
        $this->db->order_by('coupon_usage.usado_em', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Buscar cupons usados por um usuário
     * 
     * @param int $user_id
     * @return array
     */
    public function get_user_coupons($user_id) {
        $this->db->select('coupon_usage.*, coupons.codigo, coupons.tipo, coupons.valor');
        $this->db->from($this->usage_table);
        $this->db->join('coupons', 'coupons.id = coupon_usage.coupon_id');
        $this->db->where('coupon_usage.user_id', $user_id);
        $this->db->order_by('coupon_usage.usado_em', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Estatísticas de cupons
     * 
     * @return object
     */
    public function get_statistics() {
        // Total de cupons
        $total_cupons = $this->db->count_all($this->table);
        
        // Cupons ativos
        $this->db->where('ativo', 1);
        $cupons_ativos = $this->db->count_all_results($this->table);
        
        // Total de usos
        $total_usos = $this->db->count_all($this->usage_table);
        
        // Total de desconto concedido
        $this->db->select_sum('desconto_aplicado');
        $result = $this->db->get($this->usage_table)->row();
        $total_desconto = $result->desconto_aplicado ?? 0;
        
        // Cupom mais usado
        $this->db->select('coupons.codigo, coupons.tipo, coupons.valor, COUNT(coupon_usage.id) as usos');
        $this->db->from($this->usage_table);
        $this->db->join('coupons', 'coupons.id = coupon_usage.coupon_id');
        $this->db->group_by('coupon_usage.coupon_id');
        $this->db->order_by('usos', 'DESC');
        $this->db->limit(1);
        $cupom_mais_usado = $this->db->get()->row();
        
        return (object) [
            'total_cupons' => $total_cupons,
            'cupons_ativos' => $cupons_ativos,
            'total_usos' => $total_usos,
            'total_desconto' => round($total_desconto, 2),
            'cupom_mais_usado' => $cupom_mais_usado
        ];
    }

    /**
     * Desativar cupons expirados automaticamente
     * 
     * @return int Quantidade de cupons desativados
     */
    public function deactivate_expired() {
        $this->db->set('ativo', 0);
        $this->db->where('valido_ate <', date('Y-m-d'));
        $this->db->where('ativo', 1);
        $this->db->update($this->table);
        
        return $this->db->affected_rows();
    }

    /**
     * Desativar cupons que atingiram limite de usos
     * 
     * @return int Quantidade de cupons desativados
     */
    public function deactivate_max_usage() {
        $sql = "UPDATE {$this->table} 
                SET ativo = 0 
                WHERE max_usos IS NOT NULL 
                AND usos_atuais >= max_usos 
                AND ativo = 1";
        
        $this->db->query($sql);
        
        return $this->db->affected_rows();
    }
}
