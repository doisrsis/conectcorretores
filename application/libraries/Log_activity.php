<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library Log_activity - ConectCorretores
 * 
 * Biblioteca para registrar atividades do sistema
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 11/11/2025 23:30
 */
class Log_activity {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('Activity_log_model');
    }

    /**
     * Registrar login
     * 
     * @param int $user_id ID do usuário
     * @param string $user_name Nome do usuário
     * @return int|bool ID do log ou false
     */
    public function login($user_id, $user_name) {
        return $this->CI->Activity_log_model->create([
            'user_id' => $user_id,
            'user_name' => $user_name,
            'action' => 'login',
            'module' => 'auth',
            'description' => "Usuário {$user_name} fez login no sistema"
        ]);
    }

    /**
     * Registrar logout
     * 
     * @param int $user_id ID do usuário
     * @param string $user_name Nome do usuário
     * @return int|bool ID do log ou false
     */
    public function logout($user_id, $user_name) {
        return $this->CI->Activity_log_model->create([
            'user_id' => $user_id,
            'user_name' => $user_name,
            'action' => 'logout',
            'module' => 'auth',
            'description' => "Usuário {$user_name} saiu do sistema"
        ]);
    }

    /**
     * Registrar criação de registro
     * 
     * @param string $module Módulo (users, imoveis, planos, etc)
     * @param int $record_id ID do registro criado
     * @param string $description Descrição adicional
     * @return int|bool ID do log ou false
     */
    public function create($module, $record_id, $description = null) {
        $user_name = $this->CI->session->userdata('nome') ?: 'Sistema';
        
        if (!$description) {
            $description = "Criou novo registro em {$module} (ID: {$record_id})";
        }
        
        return $this->CI->Activity_log_model->create([
            'action' => 'create',
            'module' => $module,
            'record_id' => $record_id,
            'description' => $description
        ]);
    }

    /**
     * Registrar atualização de registro
     * 
     * @param string $module Módulo
     * @param int $record_id ID do registro
     * @param string $description Descrição adicional
     * @return int|bool ID do log ou false
     */
    public function update($module, $record_id, $description = null) {
        $user_name = $this->CI->session->userdata('nome') ?: 'Sistema';
        
        if (!$description) {
            $description = "Atualizou registro em {$module} (ID: {$record_id})";
        }
        
        return $this->CI->Activity_log_model->create([
            'action' => 'update',
            'module' => $module,
            'record_id' => $record_id,
            'description' => $description
        ]);
    }

    /**
     * Registrar exclusão de registro
     * 
     * @param string $module Módulo
     * @param int $record_id ID do registro
     * @param string $description Descrição adicional
     * @return int|bool ID do log ou false
     */
    public function delete($module, $record_id, $description = null) {
        $user_name = $this->CI->session->userdata('nome') ?: 'Sistema';
        
        if (!$description) {
            $description = "Deletou registro em {$module} (ID: {$record_id})";
        }
        
        return $this->CI->Activity_log_model->create([
            'action' => 'delete',
            'module' => $module,
            'record_id' => $record_id,
            'description' => $description
        ]);
    }

    /**
     * Registrar visualização de registro
     * 
     * @param string $module Módulo
     * @param int $record_id ID do registro
     * @param string $description Descrição adicional
     * @return int|bool ID do log ou false
     */
    public function view($module, $record_id, $description = null) {
        if (!$description) {
            $description = "Visualizou registro em {$module} (ID: {$record_id})";
        }
        
        return $this->CI->Activity_log_model->create([
            'action' => 'view',
            'module' => $module,
            'record_id' => $record_id,
            'description' => $description
        ]);
    }

    /**
     * Registrar ação customizada
     * 
     * @param string $action Ação
     * @param string $module Módulo
     * @param string $description Descrição
     * @param int $record_id ID do registro (opcional)
     * @return int|bool ID do log ou false
     */
    public function log($action, $module, $description, $record_id = null) {
        return $this->CI->Activity_log_model->create([
            'action' => $action,
            'module' => $module,
            'record_id' => $record_id,
            'description' => $description
        ]);
    }

    /**
     * Registrar alteração de status
     * 
     * @param string $module Módulo
     * @param int $record_id ID do registro
     * @param string $old_status Status antigo
     * @param string $new_status Status novo
     * @return int|bool ID do log ou false
     */
    public function status_change($module, $record_id, $old_status, $new_status) {
        $description = "Alterou status em {$module} (ID: {$record_id}) de '{$old_status}' para '{$new_status}'";
        
        return $this->CI->Activity_log_model->create([
            'action' => 'status_change',
            'module' => $module,
            'record_id' => $record_id,
            'description' => $description
        ]);
    }

    /**
     * Registrar exportação de dados
     * 
     * @param string $module Módulo
     * @param string $format Formato (csv, pdf, excel)
     * @param int $records_count Quantidade de registros
     * @return int|bool ID do log ou false
     */
    public function export($module, $format, $records_count = null) {
        $description = "Exportou dados de {$module} em formato {$format}";
        if ($records_count) {
            $description .= " ({$records_count} registros)";
        }
        
        return $this->CI->Activity_log_model->create([
            'action' => 'export',
            'module' => $module,
            'description' => $description
        ]);
    }

    /**
     * Registrar importação de dados
     * 
     * @param string $module Módulo
     * @param int $records_count Quantidade de registros
     * @return int|bool ID do log ou false
     */
    public function import($module, $records_count) {
        $description = "Importou {$records_count} registro(s) em {$module}";
        
        return $this->CI->Activity_log_model->create([
            'action' => 'import',
            'module' => $module,
            'description' => $description
        ]);
    }
}
