<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Biblioteca de Emails - ConectCorretores
 * 
 * Gerencia envio de emails transacionais do sistema
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 06/11/2025
 */
class Email_lib {

    private $CI;
    private $from_email;
    private $from_name;
    private $site_url;
    private $site_name;

    public function __construct() {
        $this->CI =& get_instance();
        
        // Carregar configurações de email
        $this->CI->load->config('email');
        
        // Configurar biblioteca de email com SMTP
        $config = array(
            'protocol'      => $this->CI->config->item('email_protocol'),
            'smtp_host'     => $this->CI->config->item('smtp_host'),
            'smtp_port'     => $this->CI->config->item('smtp_port'),
            'smtp_user'     => $this->CI->config->item('smtp_user'),
            'smtp_pass'     => $this->CI->config->item('smtp_pass'),
            'smtp_crypto'   => $this->CI->config->item('smtp_crypto'),
            'mailtype'      => $this->CI->config->item('mailtype'),
            'charset'       => $this->CI->config->item('charset'),
            'newline'       => $this->CI->config->item('newline'),
            'crlf'          => $this->CI->config->item('crlf'),
            'wordwrap'      => $this->CI->config->item('wordwrap')
        );
        
        // Inicializar biblioteca com configurações SMTP
        $this->CI->load->library('email', $config);
        
        // Obter configurações adicionais
        $this->from_email = $this->CI->config->item('from_email');
        $this->from_name = $this->CI->config->item('from_name');
        $this->site_url = $this->CI->config->item('site_url');
        $this->site_name = $this->CI->config->item('site_name');
    }

    /**
     * Enviar email
     * 
     * @param string $to Email do destinatário
     * @param string $subject Assunto do email
     * @param string $template Nome do template
     * @param array $data Dados para o template
     * @return bool Sucesso no envio
     */
    public function send($to, $subject, $template, $data = []) {
        try {
            log_message('info', "=== ENVIANDO EMAIL ===");
            log_message('info', "Para: {$to}");
            log_message('info', "Assunto: {$subject}");
            log_message('info', "Template: {$template}");
            
            // Adicionar dados padrão
            $data['site_url'] = $this->site_url;
            $data['site_name'] = $this->site_name;
            $data['current_year'] = date('Y');
            
            // Renderizar template
            $message = $this->_render_template($template, $data);
            log_message('info', "Template renderizado com sucesso");
            
            // Configurar email
            $this->CI->email->clear();
            $this->CI->email->from($this->from_email, $this->from_name);
            $this->CI->email->to($to);
            $this->CI->email->subject($subject);
            $this->CI->email->message($message);
            
            log_message('info', "Tentando enviar email...");
            
            // Enviar
            $result = $this->CI->email->send();
            
            if ($result) {
                log_message('info', "Email enviado com SUCESSO!");
            } else {
                log_message('error', "FALHA ao enviar email!");
                log_message('error', "Debug: " . $this->CI->email->print_debugger());
            }
            
            // Log
            if ($this->CI->config->item('email_log')) {
                $this->_log_email($to, $subject, $template, $result);
            }
            
            // Debug
            if (!$result && $this->CI->config->item('email_debug')) {
                log_message('error', 'Erro ao enviar email: ' . $this->CI->email->print_debugger());
            }
            
            log_message('info', "======================");
            
            return $result;
            
        } catch (Exception $e) {
            log_message('error', 'Exceção ao enviar email: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Enviar email de boas-vindas
     * 
     * @param object $user Dados do usuário
     * @return bool
     */
    public function send_welcome($user) {
        return $this->send(
            $user->email,
            'Bem-vindo ao ConectCorretores!',
            'welcome',
            [
                'nome' => $user->nome,
                'email' => $user->email
            ]
        );
    }

    /**
     * Enviar email de assinatura ativada
     * 
     * @param object $user Dados do usuário
     * @param object $plan Dados do plano
     * @param object $subscription Dados da assinatura
     * @return bool
     */
    public function send_subscription_activated($user, $plan, $subscription) {
        return $this->send(
            $user->email,
            'Assinatura Ativada - ConectCorretores',
            'subscription_activated',
            [
                'nome' => $user->nome,
                'plano_nome' => $plan->nome,
                'plano_preco' => number_format($plan->preco, 2, ',', '.'),
                'data_inicio' => date('d/m/Y', strtotime($subscription->data_inicio)),
                'data_fim' => date('d/m/Y', strtotime($subscription->data_fim)),
                'limite_imoveis' => $plan->limite_imoveis
            ]
        );
    }

    /**
     * Enviar email de pagamento confirmado
     * 
     * @param object $user Dados do usuário
     * @param object $plan Dados do plano
     * @param float $valor Valor pago
     * @return bool
     */
    public function send_payment_confirmed($user, $plan, $valor) {
        return $this->send(
            $user->email,
            'Pagamento Recebido - ConectCorretores',
            'payment_confirmed',
            [
                'nome' => $user->nome,
                'plano_nome' => $plan->nome,
                'valor' => number_format($valor, 2, ',', '.'),
                'data_pagamento' => date('d/m/Y H:i')
            ]
        );
    }

    /**
     * Enviar email de renovação próxima
     * 
     * @param object $user Dados do usuário
     * @param object $subscription Dados da assinatura
     * @return bool
     */
    public function send_renewal_reminder($user, $subscription) {
        $dias_restantes = ceil((strtotime($subscription->data_fim) - time()) / 86400);
        
        return $this->send(
            $user->email,
            'Seu plano renova em ' . $dias_restantes . ' dias',
            'renewal_reminder',
            [
                'nome' => $user->nome,
                'plano_nome' => $subscription->plan_nome,
                'data_renovacao' => date('d/m/Y', strtotime($subscription->data_fim)),
                'dias_restantes' => $dias_restantes,
                'valor' => number_format($subscription->plan_preco, 2, ',', '.')
            ]
        );
    }

    /**
     * Enviar email de falha no pagamento
     * 
     * @param object $user Dados do usuário
     * @param object $subscription Dados da assinatura
     * @return bool
     */
    public function send_payment_failed($user, $subscription) {
        return $this->send(
            $user->email,
            'Problema com seu pagamento - Ação Necessária',
            'payment_failed',
            [
                'nome' => $user->nome,
                'plano_nome' => $subscription->plan_nome,
                'valor' => number_format($subscription->plan_preco, 2, ',', '.')
            ]
        );
    }

    /**
     * Enviar email de plano vencido
     * 
     * @param object $user Dados do usuário
     * @param object $subscription Dados da assinatura
     * @return bool
     */
    public function send_plan_expired($user, $subscription) {
        return $this->send(
            $user->email,
            'Seu plano expirou - ConectCorretores',
            'plan_expired',
            [
                'nome' => $user->nome,
                'plano_nome' => $subscription->plan_nome,
                'data_expiracao' => date('d/m/Y', strtotime($subscription->data_fim))
            ]
        );
    }

    /**
     * Enviar email de upgrade confirmado
     * 
     * @param object $user Dados do usuário
     * @param object $old_plan Plano antigo
     * @param object $new_plan Novo plano
     * @return bool
     */
    public function send_upgrade_confirmed($user, $old_plan, $new_plan) {
        return $this->send(
            $user->email,
            'Upgrade Realizado com Sucesso!',
            'upgrade_confirmed',
            [
                'nome' => $user->nome,
                'plano_antigo' => $old_plan->nome,
                'plano_novo' => $new_plan->nome,
                'valor_antigo' => number_format($old_plan->preco, 2, ',', '.'),
                'valor_novo' => number_format($new_plan->preco, 2, ',', '.'),
                'limite_imoveis' => $new_plan->limite_imoveis
            ]
        );
    }

    /**
     * Enviar email de downgrade confirmado
     * 
     * @param object $user Dados do usuário
     * @param object $old_plan Plano antigo
     * @param object $new_plan Novo plano
     * @return bool
     */
    public function send_downgrade_confirmed($user, $old_plan, $new_plan) {
        return $this->send(
            $user->email,
            'Plano Alterado - ConectCorretores',
            'downgrade_confirmed',
            [
                'nome' => $user->nome,
                'plano_antigo' => $old_plan->nome,
                'plano_novo' => $new_plan->nome,
                'valor_antigo' => number_format($old_plan->preco, 2, ',', '.'),
                'valor_novo' => number_format($new_plan->preco, 2, ',', '.'),
                'limite_imoveis' => $new_plan->limite_imoveis
            ]
        );
    }

    /**
     * Enviar email de cancelamento confirmado
     * 
     * @param object $user Dados do usuário
     * @param object $subscription Dados da assinatura
     * @return bool
     */
    public function send_cancellation_confirmed($user, $subscription) {
        return $this->send(
            $user->email,
            'Assinatura Cancelada - ConectCorretores',
            'cancellation_confirmed',
            [
                'nome' => $user->nome,
                'plano_nome' => $subscription->plan_nome,
                'data_termino' => date('d/m/Y', strtotime($subscription->data_fim))
            ]
        );
    }

    /**
     * Enviar email de recuperação de senha
     * 
     * @param object $user Dados do usuário
     * @param string $token Token de recuperação
     * @return bool
     */
    public function send_password_reset($user, $token) {
        $reset_link = base_url('password/reset/' . $token);
        
        return $this->send(
            $user->email,
            'Redefinir sua senha - ConectCorretores',
            'password_reset',
            [
                'nome' => $user->nome,
                'reset_link' => $reset_link,
                'validade' => '24 horas'
            ]
        );
    }

    /**
     * Renderizar template de email
     * 
     * @param string $template Nome do template
     * @param array $data Dados para o template
     * @return string HTML renderizado
     */
    private function _render_template($template, $data) {
        // Renderizar conteúdo do template
        $content = $this->CI->load->view('emails/' . $template, $data, TRUE);
        
        // Renderizar layout com conteúdo
        $data['content'] = $content;
        return $this->CI->load->view('emails/layout', $data, TRUE);
    }

    /**
     * Registrar log de email enviado
     * 
     * @param string $to Destinatário
     * @param string $subject Assunto
     * @param string $template Template usado
     * @param bool $success Sucesso no envio
     */
    private function _log_email($to, $subject, $template, $success) {
        $log_data = [
            'to' => $to,
            'subject' => $subject,
            'template' => $template,
            'success' => $success ? 1 : 0,
            'sent_at' => date('Y-m-d H:i:s')
        ];
        
        log_message('info', 'Email ' . ($success ? 'enviado' : 'falhou') . ': ' . $to . ' - ' . $subject);
        
        // Opcional: Salvar em tabela de logs no banco
        // $this->CI->db->insert('email_logs', $log_data);
    }
}
