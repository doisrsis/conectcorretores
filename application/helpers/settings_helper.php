<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper de Configurações - ConectCorretores
 * 
 * Funções auxiliares para acessar configurações do sistema
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 08/11/2025
 */

if (!function_exists('get_setting')) {
    /**
     * Obter valor de uma configuração
     * 
     * @param string $chave Chave da configuração
     * @param mixed $default Valor padrão se não encontrar
     * @return mixed Valor da configuração
     */
    function get_setting($chave, $default = null) {
        $CI =& get_instance();
        $CI->load->model('Settings_model');
        
        return $CI->Settings_model->get($chave, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Definir valor de uma configuração
     * 
     * @param string $chave Chave da configuração
     * @param mixed $valor Valor da configuração
     * @param string $tipo Tipo do valor (opcional)
     * @return bool Sucesso
     */
    function set_setting($chave, $valor, $tipo = null) {
        $CI =& get_instance();
        $CI->load->model('Settings_model');
        
        return $CI->Settings_model->set($chave, $valor, $tipo);
    }
}

if (!function_exists('get_settings_by_group')) {
    /**
     * Obter todas as configurações de um grupo
     * 
     * @param string $grupo Nome do grupo
     * @return array Lista de configurações
     */
    function get_settings_by_group($grupo) {
        $CI =& get_instance();
        $CI->load->model('Settings_model');
        
        return $CI->Settings_model->get_all($grupo);
    }
}

if (!function_exists('clear_settings_cache')) {
    /**
     * Limpar cache de configurações
     * 
     * @return void
     */
    function clear_settings_cache() {
        $CI =& get_instance();
        $CI->load->model('Settings_model');
        
        $CI->Settings_model->clear_cache();
    }
}

// ========================================
// Atalhos para Configurações Comuns
// ========================================

if (!function_exists('trial_days')) {
    /**
     * Obter dias de período de teste padrão
     * 
     * @return int Dias de trial
     */
    function trial_days() {
        return (int)get_setting('trial_days_default', 7);
    }
}

if (!function_exists('grace_period_days')) {
    /**
     * Obter dias de período de graça
     * 
     * @return int Dias de graça
     */
    function grace_period_days() {
        return (int)get_setting('grace_period_days', 14);
    }
}

if (!function_exists('max_retry_attempts')) {
    /**
     * Obter número máximo de tentativas de cobrança
     * 
     * @return int Tentativas máximas
     */
    function max_retry_attempts() {
        return (int)get_setting('max_retry_attempts', 4);
    }
}

if (!function_exists('retry_interval_days')) {
    /**
     * Obter intervalo entre tentativas de cobrança
     * 
     * @return int Dias de intervalo
     */
    function retry_interval_days() {
        return (int)get_setting('retry_interval_days', 3);
    }
}

if (!function_exists('site_name')) {
    /**
     * Obter nome do site
     * 
     * @return string Nome do site
     */
    function site_name() {
        return get_setting('site_name', 'ConectCorretores');
    }
}

if (!function_exists('site_email')) {
    /**
     * Obter email do site
     * 
     * @return string Email do site
     */
    function site_email() {
        return get_setting('site_email', 'contato@conectcorretores.com.br');
    }
}

if (!function_exists('is_maintenance_mode')) {
    /**
     * Verificar se está em modo de manutenção
     * 
     * @return bool Em manutenção
     */
    function is_maintenance_mode() {
        return (bool)get_setting('maintenance_mode', false);
    }
}

if (!function_exists('email_enabled')) {
    /**
     * Verificar se um tipo de email está habilitado
     * 
     * @param string $tipo Tipo do email (payment_failed, subscription_created, etc)
     * @return bool Habilitado
     */
    function email_enabled($tipo) {
        $chave = 'email_' . $tipo;
        return (bool)get_setting($chave, true);
    }
}

if (!function_exists('max_images_per_property')) {
    /**
     * Obter máximo de imagens por imóvel
     * 
     * @return int Máximo de imagens
     */
    function max_images_per_property() {
        return (int)get_setting('max_images_per_property', 20);
    }
}

if (!function_exists('image_max_size_mb')) {
    /**
     * Obter tamanho máximo de imagem em MB
     * 
     * @return int Tamanho máximo
     */
    function image_max_size_mb() {
        return (int)get_setting('image_max_size_mb', 5);
    }
}

if (!function_exists('system_version')) {
    /**
     * Obter versão do sistema
     * 
     * @return string Versão
     */
    function system_version() {
        return get_setting('system_version', '1.0.0');
    }
}

if (!function_exists('is_debug_mode')) {
    /**
     * Verificar se está em modo de debug
     * 
     * @return bool Debug ativado
     */
    function is_debug_mode() {
        return (bool)get_setting('debug_mode', false);
    }
}

// ========================================
// Funções de Formatação
// ========================================

if (!function_exists('format_setting_value')) {
    /**
     * Formatar valor de configuração para exibição
     * 
     * @param mixed $valor Valor da configuração
     * @param string $tipo Tipo do valor
     * @return string Valor formatado
     */
    function format_setting_value($valor, $tipo) {
        switch ($tipo) {
            case 'bool':
                return $valor ? 'Sim' : 'Não';
            
            case 'int':
            case 'float':
                return number_format($valor, $tipo === 'float' ? 2 : 0, ',', '.');
            
            case 'json':
                return '<pre>' . json_encode($valor, JSON_PRETTY_PRINT) . '</pre>';
            
            default:
                return htmlspecialchars($valor);
        }
    }
}

if (!function_exists('get_setting_input_type')) {
    /**
     * Obter tipo de input HTML baseado no tipo da configuração
     * 
     * @param string $tipo Tipo da configuração
     * @return string Tipo do input
     */
    function get_setting_input_type($tipo) {
        switch ($tipo) {
            case 'int':
            case 'float':
                return 'number';
            
            case 'bool':
                return 'checkbox';
            
            case 'json':
                return 'textarea';
            
            default:
                return 'text';
        }
    }
}

// ========================================
// Funções de Validação
// ========================================

if (!function_exists('validate_setting_value')) {
    /**
     * Validar valor de configuração
     * 
     * @param mixed $valor Valor a validar
     * @param string $tipo Tipo esperado
     * @return bool Válido
     */
    function validate_setting_value($valor, $tipo) {
        switch ($tipo) {
            case 'int':
                return is_numeric($valor) && (int)$valor == $valor;
            
            case 'float':
                return is_numeric($valor);
            
            case 'bool':
                return in_array($valor, [0, 1, '0', '1', true, false, 'true', 'false'], true);
            
            case 'json':
                json_decode($valor);
                return json_last_error() === JSON_ERROR_NONE;
            
            default:
                return is_string($valor);
        }
    }
}
