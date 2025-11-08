<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller: Testar Servidores SMTP
 * ⚠️ DELETAR EM PRODUÇÃO!
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 08/11/2025
 */
class Test_smtp extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Apenas em desenvolvimento
        if (ENVIRONMENT !== 'development') {
            show_404();
        }
    }

    /**
     * Testar conexão com diferentes servidores SMTP
     */
    public function index() {
        echo '<html><head><meta charset="UTF-8"><title>Testar Servidores SMTP</title></head><body>';
        echo '<h1>🧪 Teste de Servidores SMTP</h1>';
        echo '<p>Testando conexão com diferentes servidores...</p>';
        echo '<hr>';
        
        $servidores = [
            'mail.conectcorretores.com.br' => 'DNS do domínio',
            'br61-cp.valueserver.com.br' => 'Servidor ValueServer',
            '177.136.251.242' => 'IP do servidor'
        ];
        
        foreach ($servidores as $host => $descricao) {
            echo '<h2>📡 Testando: ' . $host . '</h2>';
            echo '<p><em>' . $descricao . '</em></p>';
            
            $resultado = $this->testar_conexao($host, 465);
            
            if ($resultado['sucesso']) {
                echo '<div style="background: #d4edda; padding: 15px; border-radius: 5px; color: #155724; margin-bottom: 20px;">';
                echo '<h3>✅ CONEXÃO BEM-SUCEDIDA!</h3>';
                echo '<p>' . $resultado['mensagem'] . '</p>';
                echo '<p><strong>Recomendação:</strong> Use este servidor!</p>';
                echo '<code>$config[\'smtp_host\'] = \'' . $host . '\';</code>';
                echo '</div>';
            } else {
                echo '<div style="background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24; margin-bottom: 20px;">';
                echo '<h3>❌ FALHA NA CONEXÃO</h3>';
                echo '<p>' . $resultado['mensagem'] . '</p>';
                echo '</div>';
            }
            
            echo '<hr>';
        }
        
        echo '<h2>📋 Resumo e Recomendação:</h2>';
        echo '<ol>';
        echo '<li>Se <strong>mail.conectcorretores.com.br</strong> funcionou: Use este (mais profissional)</li>';
        echo '<li>Se <strong>br61-cp.valueserver.com.br</strong> funcionou: Use este (mais confiável)</li>';
        echo '<li>Se <strong>IP 177.136.251.242</strong> funcionou: Use este (último recurso)</li>';
        echo '</ol>';
        
        echo '<hr>';
        echo '<h2>🔧 Próximo Passo:</h2>';
        echo '<p>Após identificar qual servidor funciona, atualize o arquivo:</p>';
        echo '<code>application/config/email.php</code>';
        echo '<p>E teste o envio em: <a href="' . base_url('test_email') . '">Test Email</a></p>';
        
        echo '</body></html>';
    }
    
    /**
     * Testar conexão com servidor SMTP
     */
    private function testar_conexao($host, $port) {
        $timeout = 10;
        $errno = 0;
        $errstr = '';
        
        // Tentar conexão
        $socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
        
        if ($socket) {
            // Ler resposta do servidor
            $response = fgets($socket, 512);
            fclose($socket);
            
            return [
                'sucesso' => true,
                'mensagem' => 'Servidor respondeu: ' . trim($response)
            ];
        } else {
            return [
                'sucesso' => false,
                'mensagem' => "Erro #{$errno}: {$errstr}"
            ];
        }
    }
    
    /**
     * Testar envio com servidor específico
     */
    public function enviar($host = null) {
        if (!$host) {
            echo 'Uso: /test_smtp/enviar/[servidor]<br>';
            echo 'Exemplos:<br>';
            echo '- /test_smtp/enviar/mail.conectcorretores.com.br<br>';
            echo '- /test_smtp/enviar/br61-cp.valueserver.com.br<br>';
            echo '- /test_smtp/enviar/177.136.251.242<br>';
            return;
        }
        
        // Decodificar URL
        $host = urldecode($host);
        
        echo '<html><head><meta charset="UTF-8"><title>Testar Envio SMTP</title></head><body>';
        echo '<h1>📧 Teste de Envio com: ' . htmlspecialchars($host) . '</h1>';
        
        // Configurar email
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => $host,
            'smtp_port' => 465,
            'smtp_crypto' => 'ssl',
            'smtp_user' => 'noreply@conectcorretores.com.br',
            'smtp_pass' => 'U248nKFUVgksm[&O@2025.',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'crlf' => "\r\n",
            'wordwrap' => TRUE
        ];
        
        $this->load->library('email');
        $this->email->initialize($config);
        
        $this->email->from('noreply@conectcorretores.com.br', 'ConectCorretores');
        $this->email->to('doisr.sistemas@gmail.com'); // ⚠️ ALTERAR AQUI
        $this->email->subject('Teste SMTP - ' . $host);
        $this->email->message('Este é um email de teste usando o servidor: <strong>' . $host . '</strong>');
        
        if ($this->email->send()) {
            echo '<div style="background: #d4edda; padding: 20px; border-radius: 5px; color: #155724;">';
            echo '<h2>✅ EMAIL ENVIADO COM SUCESSO!</h2>';
            echo '<p>O servidor <strong>' . htmlspecialchars($host) . '</strong> está funcionando corretamente!</p>';
            echo '<p><strong>Recomendação:</strong> Use este servidor na configuração.</p>';
            echo '<pre>$config[\'smtp_host\'] = \'' . htmlspecialchars($host) . '\';</pre>';
            echo '</div>';
        } else {
            echo '<div style="background: #f8d7da; padding: 20px; border-radius: 5px; color: #721c24;">';
            echo '<h2>❌ FALHA AO ENVIAR EMAIL</h2>';
            echo '<p>O servidor <strong>' . htmlspecialchars($host) . '</strong> não conseguiu enviar o email.</p>';
            echo '<h3>Debug:</h3>';
            echo '<pre>' . $this->email->print_debugger() . '</pre>';
            echo '</div>';
        }
        
        echo '<hr>';
        echo '<p><a href="' . base_url('test_smtp') . '">← Voltar para testes</a></p>';
        echo '</body></html>';
    }
}
