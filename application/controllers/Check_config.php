<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Verificar Configurações
 * ⚠️ DELETAR EM PRODUÇÃO!
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 08/11/2025
 */
class Check_config extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Apenas em desenvolvimento
        if (ENVIRONMENT !== 'development') {
            show_404();
        }
    }

    /**
     * Verificar configuração de email
     */
    public function email() {
        // Carregar configuração
        $this->config->load('email');
        
        $smtp_host = $this->config->item('smtp_host');
        $smtp_port = $this->config->item('smtp_port');
        $smtp_crypto = $this->config->item('smtp_crypto');
        $smtp_user = $this->config->item('smtp_user');
        $smtp_pass = $this->config->item('smtp_pass');
        
        echo '<html><head><meta charset="UTF-8"><title>Verificar Email Config</title></head><body>';
        echo '<h1>🔍 Configuração de Email Atual</h1>';
        echo '<table border="1" cellpadding="10" style="border-collapse: collapse;">';
        echo '<tr style="background: #f0f0f0;"><th>Configuração</th><th>Valor</th></tr>';
        echo '<tr><td><strong>smtp_host</strong></td><td><code>' . htmlspecialchars($smtp_host) . '</code></td></tr>';
        echo '<tr><td><strong>smtp_port</strong></td><td><code>' . htmlspecialchars($smtp_port) . '</code></td></tr>';
        echo '<tr><td><strong>smtp_crypto</strong></td><td><code>' . htmlspecialchars($smtp_crypto) . '</code></td></tr>';
        echo '<tr><td><strong>smtp_user</strong></td><td><code>' . htmlspecialchars($smtp_user) . '</code></td></tr>';
        echo '<tr><td><strong>smtp_pass</strong></td><td><code>' . substr($smtp_pass, 0, 5) . '***</code></td></tr>';
        echo '</table>';
        
        echo '<hr>';
        echo '<h2>✅ Configuração Esperada:</h2>';
        echo '<ul>';
        echo '<li><strong>smtp_host:</strong> <code>mail.conectcorretores.com.br</code></li>';
        echo '<li><strong>smtp_port:</strong> <code>465</code></li>';
        echo '<li><strong>smtp_crypto:</strong> <code>ssl</code></li>';
        echo '<li><strong>smtp_user:</strong> <code>noreply@conectcorretores.com.br</code></li>';
        echo '<li><strong>smtp_pass:</strong> <code>U248n***</code> (com ponto final)</li>';
        echo '</ul>';
        
        echo '<hr>';
        
        // Verificar se está correto
        $correto = true;
        $erros = [];
        
        if ($smtp_host !== 'mail.conectcorretores.com.br') {
            $correto = false;
            $erros[] = "smtp_host está como '<strong>{$smtp_host}</strong>' mas deveria ser '<strong>mail.conectcorretores.com.br</strong>'";
        }
        
        if ($smtp_port != 465) {
            $correto = false;
            $erros[] = "smtp_port está como '<strong>{$smtp_port}</strong>' mas deveria ser '<strong>465</strong>'";
        }
        
        if ($smtp_crypto !== 'ssl') {
            $correto = false;
            $erros[] = "smtp_crypto está como '<strong>{$smtp_crypto}</strong>' mas deveria ser '<strong>ssl</strong>'";
        }
        
        if (!str_ends_with($smtp_pass, '.')) {
            $correto = false;
            $erros[] = "smtp_pass não termina com ponto (.)";
        }
        
        if ($correto) {
            echo '<div style="background: #d4edda; padding: 20px; border-radius: 5px; color: #155724; border: 2px solid #c3e6cb;">';
            echo '<h2>✅ Configuração CORRETA!</h2>';
            echo '<p>Todas as configurações estão corretas. Você pode testar o envio de emails.</p>';
            echo '<p><a href="' . base_url('test_email') . '" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;">🧪 Testar Envio de Email</a></p>';
            echo '</div>';
        } else {
            echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px; color: #721c24; border: 2px solid #f5c6cb;">';
            echo '<h2>❌ Configuração INCORRETA!</h2>';
            echo '<p><strong>Problemas encontrados:</strong></p>';
            echo '<ul>';
            foreach ($erros as $erro) {
                echo '<li>' . $erro . '</li>';
            }
            echo '</ul>';
            echo '<p><strong>Ação necessária:</strong> Edite o arquivo <code>application/config/email.php</code> e corrija as configurações acima.</p>';
            echo '</div>';
        }
        
        echo '<hr>';
        echo '<h3>📍 Localização do Arquivo:</h3>';
        echo '<p><code>' . APPPATH . 'config/email.php</code></p>';
        
        echo '<hr>';
        echo '<p style="color: #856404; background: #fff3cd; padding: 10px; border-radius: 5px; border: 1px solid #ffeaa7;">';
        echo '<strong>⚠️ IMPORTANTE:</strong> Delete este controller em produção! (application/controllers/Check_config.php)';
        echo '</p>';
        
        echo '<hr>';
        echo '<p><a href="' . base_url() . '">← Voltar para Home</a></p>';
        echo '</body></html>';
    }
    
    /**
     * Verificar todas as configurações
     */
    public function index() {
        echo '<html><head><meta charset="UTF-8"><title>Verificar Configurações</title></head><body>';
        echo '<h1>🔍 Verificar Configurações do Sistema</h1>';
        echo '<ul>';
        echo '<li><a href="' . base_url('check_config/email') . '">📧 Configuração de Email</a></li>';
        echo '<li><a href="' . base_url('check_config/database') . '">🗄️ Configuração de Banco de Dados</a></li>';
        echo '<li><a href="' . base_url('check_config/stripe') . '">💳 Configuração do Stripe</a></li>';
        echo '</ul>';
        echo '<hr>';
        echo '<p><a href="' . base_url() . '">← Voltar para Home</a></p>';
        echo '</body></html>';
    }
    
    /**
     * Verificar configuração de banco de dados
     */
    public function database() {
        $db = $this->db;
        
        echo '<html><head><meta charset="UTF-8"><title>Verificar Database Config</title></head><body>';
        echo '<h1>🗄️ Configuração de Banco de Dados</h1>';
        echo '<table border="1" cellpadding="10" style="border-collapse: collapse;">';
        echo '<tr style="background: #f0f0f0;"><th>Configuração</th><th>Valor</th></tr>';
        echo '<tr><td><strong>hostname</strong></td><td><code>' . $db->hostname . '</code></td></tr>';
        echo '<tr><td><strong>username</strong></td><td><code>' . $db->username . '</code></td></tr>';
        echo '<tr><td><strong>database</strong></td><td><code>' . $db->database . '</code></td></tr>';
        echo '<tr><td><strong>dbdriver</strong></td><td><code>' . $db->dbdriver . '</code></td></tr>';
        echo '<tr><td><strong>char_set</strong></td><td><code>' . $db->char_set . '</code></td></tr>';
        echo '</table>';
        
        echo '<hr>';
        echo '<h2>🧪 Teste de Conexão:</h2>';
        
        try {
            $query = $db->query('SELECT 1');
            if ($query) {
                echo '<div style="background: #d4edda; padding: 20px; border-radius: 5px; color: #155724;">';
                echo '<h3>✅ Conexão Bem-Sucedida!</h3>';
                echo '<p>O banco de dados está conectado e funcionando.</p>';
                echo '</div>';
            }
        } catch (Exception $e) {
            echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px; color: #721c24;">';
            echo '<h3>❌ Erro de Conexão!</h3>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '</div>';
        }
        
        echo '<hr>';
        echo '<p><a href="' . base_url('check_config') . '">← Voltar</a></p>';
        echo '</body></html>';
    }
    
    /**
     * Verificar configuração do Stripe
     */
    public function stripe() {
        $this->config->load('stripe');
        
        $secret_key = $this->config->item('stripe_secret_key');
        $publishable_key = $this->config->item('stripe_publishable_key');
        $mode = $this->config->item('stripe_mode');
        
        echo '<html><head><meta charset="UTF-8"><title>Verificar Stripe Config</title></head><body>';
        echo '<h1>💳 Configuração do Stripe</h1>';
        echo '<table border="1" cellpadding="10" style="border-collapse: collapse;">';
        echo '<tr style="background: #f0f0f0;"><th>Configuração</th><th>Valor</th></tr>';
        echo '<tr><td><strong>stripe_mode</strong></td><td><code>' . $mode . '</code></td></tr>';
        echo '<tr><td><strong>secret_key</strong></td><td><code>' . substr($secret_key, 0, 10) . '***</code></td></tr>';
        echo '<tr><td><strong>publishable_key</strong></td><td><code>' . substr($publishable_key, 0, 10) . '***</code></td></tr>';
        echo '</table>';
        
        echo '<hr>';
        
        if ($mode === 'live') {
            echo '<div style="background: #fff3cd; padding: 20px; border-radius: 5px; color: #856404;">';
            echo '<h3>⚠️ MODO PRODUÇÃO ATIVO</h3>';
            echo '<p>O Stripe está configurado para processar pagamentos REAIS.</p>';
            echo '</div>';
        } else {
            echo '<div style="background: #d1ecf1; padding: 20px; border-radius: 5px; color: #0c5460;">';
            echo '<h3>🧪 MODO TESTE ATIVO</h3>';
            echo '<p>O Stripe está configurado para processar pagamentos de TESTE.</p>';
            echo '</div>';
        }
        
        echo '<hr>';
        echo '<p><a href="' . base_url('check_config') . '">← Voltar</a></p>';
        echo '</body></html>';
    }
}
