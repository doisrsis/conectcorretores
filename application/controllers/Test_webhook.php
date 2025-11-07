<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Test_webhook - ConectCorretores
 *
 * Testar webhook manualmente
 *
 * @author Rafael Dias - doisr.com.br
 * @date 07/11/2025
 */
class Test_webhook extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Plan_model');
        $this->load->model('Subscription_model');
        $this->load->model('User_model');
        $this->load->library('email_lib');
    }

    /**
     * Simular webhook de checkout completado
     */
    public function checkout_completed() {
        echo "<h1>Teste de Webhook - Checkout Completed</h1>";
        echo "<hr>";
        
        // Pegar última assinatura criada
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $subscription = $this->db->get('subscriptions')->row();
        
        if (!$subscription) {
            echo "<p style='color:red;'>❌ Nenhuma assinatura encontrada!</p>";
            return;
        }
        
        echo "<p>✅ Assinatura encontrada: ID {$subscription->id}</p>";
        echo "<p>User ID: {$subscription->user_id}</p>";
        echo "<p>Plan ID: {$subscription->plan_id}</p>";
        echo "<hr>";
        
        // Buscar usuário
        $user = $this->User_model->get_by_id($subscription->user_id);
        
        if (!$user) {
            echo "<p style='color:red;'>❌ Usuário não encontrado!</p>";
            return;
        }
        
        echo "<p>✅ Usuário encontrado: {$user->email}</p>";
        echo "<hr>";
        
        // Buscar plano
        $plan = $this->Plan_model->get_by_id($subscription->plan_id);
        
        if (!$plan) {
            echo "<p style='color:red;'>❌ Plano não encontrado!</p>";
            return;
        }
        
        echo "<p>✅ Plano encontrado: {$plan->nome}</p>";
        echo "<hr>";
        
        // Tentar enviar email
        echo "<h2>Enviando Email...</h2>";
        
        $result = $this->email_lib->send_subscription_activated($user, $plan, $subscription);
        
        if ($result) {
            echo "<p style='color:green; font-size:20px;'>✅ EMAIL ENVIADO COM SUCESSO!</p>";
        } else {
            echo "<p style='color:red; font-size:20px;'>❌ FALHA AO ENVIAR EMAIL!</p>";
        }
        
        echo "<hr>";
        echo "<p><a href='" . base_url() . "'>← Voltar</a></p>";
        
        echo "<hr>";
        echo "<h3>Verificar Log:</h3>";
        echo "<p>Abra: <code>application/logs/log-" . date('Y-m-d') . ".php</code></p>";
        echo "<p>Procure por: <code>=== ENVIANDO EMAIL ===</code></p>";
    }
    
    /**
     * Simular webhook de pagamento bem-sucedido
     */
    public function payment_succeeded() {
        echo "<h1>Teste de Webhook - Payment Succeeded</h1>";
        echo "<hr>";
        
        // Pegar última assinatura ativa
        $this->db->where('status', 'ativa');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $subscription = $this->db->get('subscriptions')->row();
        
        if (!$subscription) {
            echo "<p style='color:red;'>❌ Nenhuma assinatura ativa encontrada!</p>";
            return;
        }
        
        echo "<p>✅ Assinatura encontrada: ID {$subscription->id}</p>";
        echo "<hr>";
        
        // Buscar usuário
        $user = $this->User_model->get_by_id($subscription->user_id);
        
        if (!$user) {
            echo "<p style='color:red;'>❌ Usuário não encontrado!</p>";
            return;
        }
        
        echo "<p>✅ Usuário encontrado: {$user->email}</p>";
        echo "<hr>";
        
        // Buscar plano
        $plan = $this->Plan_model->get_by_id($subscription->plan_id);
        
        if (!$plan) {
            echo "<p style='color:red;'>❌ Plano não encontrado!</p>";
            return;
        }
        
        echo "<p>✅ Plano encontrado: {$plan->nome}</p>";
        echo "<hr>";
        
        // Tentar enviar email
        echo "<h2>Enviando Email...</h2>";
        
        $valor = $plan->preco;
        $result = $this->email_lib->send_payment_confirmed($user, $plan, $valor);
        
        if ($result) {
            echo "<p style='color:green; font-size:20px;'>✅ EMAIL ENVIADO COM SUCESSO!</p>";
        } else {
            echo "<p style='color:red; font-size:20px;'>❌ FALHA AO ENVIAR EMAIL!</p>";
        }
        
        echo "<hr>";
        echo "<p><a href='" . base_url() . "'>← Voltar</a></p>";
    }
}
