<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper de Assinaturas - ConectCorretores
 * 
 * Funções auxiliares para verificar status de planos e permissões
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 03/11/2025
 */

if (!function_exists('usuario_tem_plano_ativo')) {
    /**
     * Verificar se usuário tem plano ativo
     * 
     * @param int $user_id ID do usuário
     * @return bool True se tem plano ativo
     */
    function usuario_tem_plano_ativo($user_id) {
        $CI =& get_instance();
        $CI->load->model('Subscription_model');
        
        $subscription = $CI->Subscription_model->get_active_by_user($user_id);
        
        if (!$subscription) {
            return false;
        }
        
        // Verificar se não expirou
        return strtotime($subscription->data_fim) >= strtotime(date('Y-m-d'));
    }
}

if (!function_exists('pode_gerenciar_imoveis')) {
    /**
     * Verificar se pode cadastrar/editar imóveis
     * 
     * @param int $user_id ID do usuário
     * @return bool True se pode gerenciar imóveis
     */
    function pode_gerenciar_imoveis($user_id) {
        return usuario_tem_plano_ativo($user_id);
    }
}

if (!function_exists('mensagem_bloqueio_imovel')) {
    /**
     * Obter mensagem de bloqueio apropriada
     * 
     * @param bool $tem_plano_vencido Se true, usuário tinha plano mas venceu
     * @return string Mensagem de bloqueio
     */
    function mensagem_bloqueio_imovel($tem_plano_vencido = false) {
        if ($tem_plano_vencido) {
            return 'Seu plano expirou. Renove para gerenciar seus imóveis.';
        }
        return 'Você precisa de um plano ativo para cadastrar imóveis.';
    }
}

if (!function_exists('get_status_assinatura')) {
    /**
     * Obter informações sobre status da assinatura
     * 
     * @param int $user_id ID do usuário
     * @return object Objeto com informações da assinatura
     */
    function get_status_assinatura($user_id) {
        $CI =& get_instance();
        $CI->load->model('Subscription_model');
        
        $status = new stdClass();
        $status->tem_plano = false;
        $status->plano_ativo = false;
        $status->plano_vencido = false;
        $status->data_fim = null;
        $status->subscription = null;
        
        $subscription = $CI->Subscription_model->get_active_by_user($user_id);
        
        if ($subscription) {
            $status->tem_plano = true;
            $status->subscription = $subscription;
            $status->data_fim = $subscription->data_fim;
            
            // Verificar se está ativo ou vencido
            if (strtotime($subscription->data_fim) >= strtotime(date('Y-m-d'))) {
                $status->plano_ativo = true;
            } else {
                $status->plano_vencido = true;
            }
        }
        
        return $status;
    }
}
