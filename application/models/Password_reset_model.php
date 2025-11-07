<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Password_reset_model - ConectCorretores
 *
 * Gerenciamento de tokens de recuperação de senha
 *
 * @author Rafael Dias - doisr.com.br
 * @date 07/11/2025
 */
class Password_reset_model extends CI_Model {

    private $table = 'password_resets';

    /**
     * Criar token de reset
     * 
     * @param int $user_id ID do usuário
     * @return string Token gerado
     */
    public function create_token($user_id) {
        // Gerar token único
        $token = bin2hex(random_bytes(32));
        
        // Calcular data de expiração (24 horas)
        $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        // Invalidar tokens anteriores do usuário
        $this->invalidate_user_tokens($user_id);
        
        // Inserir novo token
        $data = [
            'user_id' => $user_id,
            'token' => $token,
            'expires_at' => $expires_at,
            'used' => 0
        ];
        
        $this->db->insert($this->table, $data);
        
        return $token;
    }

    /**
     * Validar token
     * 
     * @param string $token Token a validar
     * @return object|false Dados do reset ou false se inválido
     */
    public function validate_token($token) {
        $this->db->select('password_resets.*, users.email, users.nome');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = password_resets.user_id');
        $this->db->where('password_resets.token', $token);
        $this->db->where('password_resets.used', 0);
        $this->db->where('password_resets.expires_at >', date('Y-m-d H:i:s'));
        
        $result = $this->db->get()->row();
        
        return $result ? $result : false;
    }

    /**
     * Marcar token como usado
     * 
     * @param string $token Token a marcar
     * @return bool Sucesso
     */
    public function mark_as_used($token) {
        $data = [
            'used' => 1,
            'used_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('token', $token);
        return $this->db->update($this->table, $data);
    }

    /**
     * Invalidar todos os tokens de um usuário
     * 
     * @param int $user_id ID do usuário
     * @return bool Sucesso
     */
    public function invalidate_user_tokens($user_id) {
        $data = [
            'used' => 1,
            'used_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('user_id', $user_id);
        $this->db->where('used', 0);
        return $this->db->update($this->table, $data);
    }

    /**
     * Deletar tokens expirados
     * 
     * @return int Número de tokens deletados
     */
    public function delete_expired() {
        $this->db->where('expires_at <', date('Y-m-d H:i:s'));
        $this->db->or_where('used', 1);
        $this->db->where('used_at <', date('Y-m-d H:i:s', strtotime('-7 days')));
        
        $this->db->delete($this->table);
        
        return $this->db->affected_rows();
    }

    /**
     * Buscar token por ID
     * 
     * @param int $id ID do token
     * @return object|null Token ou null
     */
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    /**
     * Buscar tokens de um usuário
     * 
     * @param int $user_id ID do usuário
     * @param bool $only_valid Apenas tokens válidos
     * @return array Lista de tokens
     */
    public function get_by_user($user_id, $only_valid = false) {
        $this->db->where('user_id', $user_id);
        
        if ($only_valid) {
            $this->db->where('used', 0);
            $this->db->where('expires_at >', date('Y-m-d H:i:s'));
        }
        
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }

    /**
     * Contar tokens válidos de um usuário
     * 
     * @param int $user_id ID do usuário
     * @return int Quantidade de tokens válidos
     */
    public function count_valid_tokens($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('used', 0);
        $this->db->where('expires_at >', date('Y-m-d H:i:s'));
        
        return $this->db->count_all_results($this->table);
    }
}
