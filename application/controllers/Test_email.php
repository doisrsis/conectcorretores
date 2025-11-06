<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller de Teste de Emails
 *
 * Usar apenas em desenvolvimento para testar envio de emails
 * DELETAR em produção!
 *
 * @author Rafael Dias - doisr.com.br
 * @date 06/11/2025
 */
class Test_email extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Apenas em desenvolvimento
        if (ENVIRONMENT !== 'development') {
            show_404();
        }

        $this->load->library('email_lib');
        $this->load->model('User_model');
    }

    /**
     * Página inicial com lista de testes
     */
    public function index() {
        echo '<h1>🧪 Testes de Email - ConectCorretores</h1>';
        echo '<p>Escolha um teste para executar:</p>';
        echo '<ul>';
        echo '<li><a href="' . base_url('test_email/welcome') . '">1. Email de Boas-Vindas</a></li>';
        echo '<li><a href="' . base_url('test_email/subscription_activated') . '">2. Assinatura Ativada</a></li>';
        echo '<li><a href="' . base_url('test_email/payment_confirmed') . '">3. Pagamento Confirmado</a></li>';
        echo '<li><a href="' . base_url('test_email/renewal_reminder') . '">4. Lembrete de Renovação</a></li>';
        echo '<li><a href="' . base_url('test_email/payment_failed') . '">5. Falha no Pagamento</a></li>';
        echo '<li><a href="' . base_url('test_email/plan_expired') . '">6. Plano Vencido</a></li>';
        echo '<li><a href="' . base_url('test_email/upgrade') . '">7. Upgrade Confirmado</a></li>';
        echo '<li><a href="' . base_url('test_email/downgrade') . '">8. Downgrade Confirmado</a></li>';
        echo '<li><a href="' . base_url('test_email/cancellation') . '">9. Cancelamento Confirmado</a></li>';
        echo '<li><a href="' . base_url('test_email/password_reset') . '">10. Recuperação de Senha</a></li>';
        echo '</ul>';
        echo '<hr>';
        echo '<p><strong>⚠️ Importante:</strong> Configure o email de destino no método <code>_get_test_user()</code></p>';
    }

    /**
     * Teste: Email de Boas-Vindas
     */
    public function welcome() {
        $user = $this->_get_test_user();

        $result = $this->email_lib->send_welcome($user);

        $this->_show_result('Boas-Vindas', $result);
    }

    /**
     * Teste: Assinatura Ativada
     */
    public function subscription_activated() {
        $user = $this->_get_test_user();
        $plan = $this->_get_test_plan();
        $subscription = $this->_get_test_subscription();

        $result = $this->email_lib->send_subscription_activated($user, $plan, $subscription);

        $this->_show_result('Assinatura Ativada', $result);
    }

    /**
     * Teste: Pagamento Confirmado
     */
    public function payment_confirmed() {
        $user = $this->_get_test_user();
        $plan = $this->_get_test_plan();

        $result = $this->email_lib->send_payment_confirmed($user, $plan, 49.90);

        $this->_show_result('Pagamento Confirmado', $result);
    }

    /**
     * Teste: Lembrete de Renovação
     */
    public function renewal_reminder() {
        $user = $this->_get_test_user();
        $subscription = $this->_get_test_subscription();

        $result = $this->email_lib->send_renewal_reminder($user, $subscription);

        $this->_show_result('Lembrete de Renovação', $result);
    }

    /**
     * Teste: Falha no Pagamento
     */
    public function payment_failed() {
        $user = $this->_get_test_user();
        $subscription = $this->_get_test_subscription();

        $result = $this->email_lib->send_payment_failed($user, $subscription);

        $this->_show_result('Falha no Pagamento', $result);
    }

    /**
     * Teste: Plano Vencido
     */
    public function plan_expired() {
        $user = $this->_get_test_user();
        $subscription = $this->_get_test_subscription();

        $result = $this->email_lib->send_plan_expired($user, $subscription);

        $this->_show_result('Plano Vencido', $result);
    }

    /**
     * Teste: Upgrade Confirmado
     */
    public function upgrade() {
        $user = $this->_get_test_user();
        $old_plan = $this->_get_test_plan('Básico', 29.90, 5);
        $new_plan = $this->_get_test_plan('Profissional', 49.90, 15);

        $result = $this->email_lib->send_upgrade_confirmed($user, $old_plan, $new_plan);

        $this->_show_result('Upgrade Confirmado', $result);
    }

    /**
     * Teste: Downgrade Confirmado
     */
    public function downgrade() {
        $user = $this->_get_test_user();
        $old_plan = $this->_get_test_plan('Profissional', 49.90, 15);
        $new_plan = $this->_get_test_plan('Básico', 29.90, 5);

        $result = $this->email_lib->send_downgrade_confirmed($user, $old_plan, $new_plan);

        $this->_show_result('Downgrade Confirmado', $result);
    }

    /**
     * Teste: Cancelamento Confirmado
     */
    public function cancellation() {
        $user = $this->_get_test_user();
        $subscription = $this->_get_test_subscription();

        $result = $this->email_lib->send_cancellation_confirmed($user, $subscription);

        $this->_show_result('Cancelamento Confirmado', $result);
    }

    /**
     * Teste: Recuperação de Senha
     */
    public function password_reset() {
        $user = $this->_get_test_user();
        $token = bin2hex(random_bytes(32));

        $result = $this->email_lib->send_password_reset($user, $token);

        $this->_show_result('Recuperação de Senha', $result);
    }

    /**
     * Obter usuário de teste
     */
    private function _get_test_user() {
        $user = new stdClass();
        $user->nome = 'João Silva';
        $user->email = 'doisr.sistemas@gmail.com'; // ⚠️ ALTERAR AQUI!
        return $user;
    }

    /**
     * Obter plano de teste
     */
    private function _get_test_plan($nome = 'Profissional', $preco = 49.90, $limite = 15) {
        $plan = new stdClass();
        $plan->nome = $nome;
        $plan->preco = $preco;
        $plan->limite_imoveis = $limite;
        return $plan;
    }

    /**
     * Obter assinatura de teste
     */
    private function _get_test_subscription() {
        $subscription = new stdClass();
        $subscription->plan_nome = 'Profissional';
        $subscription->plan_preco = 49.90;
        $subscription->data_inicio = date('Y-m-d');
        $subscription->data_fim = date('Y-m-d', strtotime('+30 days'));
        return $subscription;
    }

    /**
     * Mostrar resultado do teste
     */
    private function _show_result($tipo, $result) {
        echo '<h1>🧪 Teste: ' . $tipo . '</h1>';

        if ($result) {
            echo '<div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 5px; color: #155724;">';
            echo '<h2>✅ Email Enviado com Sucesso!</h2>';
            echo '<p>Verifique sua caixa de entrada.</p>';
            echo '</div>';
        } else {
            echo '<div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 5px; color: #721c24;">';
            echo '<h2>❌ Erro ao Enviar Email</h2>';
            echo '<p>Verifique as configurações SMTP.</p>';
            echo '<h3>Debug:</h3>';
            echo '<pre>' . $this->email->print_debugger() . '</pre>';
            echo '</div>';
        }

        echo '<hr>';
        echo '<p><a href="' . base_url('test_email') . '">← Voltar para lista de testes</a></p>';
    }
}
