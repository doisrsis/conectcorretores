<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller de Teste - Falha de Pagamento
 * 
 * Simula webhooks de falha de pagamento para testar o sistema
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 07/11/2025
 */
class Test_payment_failure extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Subscription_model');
        $this->load->model('User_model');
        $this->load->library('email_lib');
    }

    /**
     * Página inicial de testes
     */
    public function index() {
        // Verificar se foi passado um ID específico
        $selected_id = $this->input->get('subscription_id');
        
        // Buscar todas as assinaturas ativas para o select
        $all_subscriptions = $this->Subscription_model->get_all(['status' => 'ativa'], 100, 0);
        
        if (empty($all_subscriptions)) {
            echo "<h1>❌ Nenhuma assinatura ativa encontrada</h1>";
            echo "<p>Crie uma assinatura primeiro para testar.</p>";
            return;
        }
        
        // Se foi selecionado um ID específico, buscar essa assinatura
        if ($selected_id) {
            $sub = null;
            foreach ($all_subscriptions as $s) {
                if ($s->id == $selected_id) {
                    $sub = $s;
                    break;
                }
            }
            if (!$sub) {
                $sub = $all_subscriptions[0];
            }
        } else {
            $sub = $all_subscriptions[0];
        }
        
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Teste de Falha de Pagamento</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    max-width: 800px;
                    margin: 50px auto;
                    padding: 20px;
                    background: #f5f5f5;
                }
                .card {
                    background: white;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    margin-bottom: 20px;
                }
                h1 { color: #333; }
                h2 { color: #666; margin-top: 30px; }
                .info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0; }
                .warning { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107; }
                .success { background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #28a745; }
                .error { background: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #dc3545; }
                button {
                    padding: 15px 30px;
                    font-size: 16px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    margin: 10px 5px;
                    transition: all 0.3s;
                }
                .btn-primary { background: #007bff; color: white; }
                .btn-primary:hover { background: #0056b3; }
                .btn-warning { background: #ffc107; color: #333; }
                .btn-warning:hover { background: #e0a800; }
                .btn-danger { background: #dc3545; color: white; }
                .btn-danger:hover { background: #c82333; }
                .btn-success { background: #28a745; color: white; }
                .btn-success:hover { background: #218838; }
                pre {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 5px;
                    overflow-x: auto;
                }
                select {
                    width: 100%;
                    padding: 12px;
                    font-size: 16px;
                    border: 2px solid #ddd;
                    border-radius: 5px;
                    margin: 10px 0;
                    background: white;
                }
                select:focus {
                    outline: none;
                    border-color: #007bff;
                }
                .form-group {
                    margin: 20px 0;
                }
                label {
                    display: block;
                    font-weight: bold;
                    margin-bottom: 8px;
                    color: #333;
                }
            </style>
        </head>
        <body>
            <div class="card">
                <h1>🧪 Teste de Falha de Pagamento</h1>
                
                <div class="form-group">
                    <label for="subscription_select">🔍 Selecionar Assinatura para Testar:</label>
                    <select id="subscription_select" onchange="trocarAssinatura()">
                        <?php foreach ($all_subscriptions as $s): ?>
                            <option value="<?php echo $s->id; ?>" <?php echo ($s->id == $sub->id) ? 'selected' : ''; ?>>
                                ID Assinatura: <?php echo $s->id; ?> | 
                                ID Usuário: <?php echo $s->user_id; ?> | 
                                <?php echo $s->user_nome; ?> 
                                (<?php echo $s->user_email; ?>) | 
                                <?php echo $s->plan_nome; ?> | 
                                R$ <?php echo number_format($s->plan_preco, 2, ',', '.'); ?> | 
                                Status: <?php echo $s->status; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: #666; display: block; margin-top: 5px;">
                        💡 Total de <?php echo count($all_subscriptions); ?> assinatura(s) ativa(s) disponível(is)
                    </small>
                </div>
                
                <div class="form-group">
                    <label for="user_id_input">🔎 Ou buscar por ID do Usuário:</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="number" 
                               id="user_id_input" 
                               placeholder="Digite o ID do usuário" 
                               style="flex: 1; padding: 12px; font-size: 16px; border: 2px solid #ddd; border-radius: 5px;">
                        <button class="btn-primary" onclick="buscarPorUserId()" style="margin: 0;">
                            🔍 Buscar
                        </button>
                    </div>
                    <small style="color: #666; display: block; margin-top: 5px;">
                        💡 Digite o ID do usuário e clique em Buscar para encontrar sua assinatura
                    </small>
                </div>
                
                <div class="info">
                    <strong>📋 Assinatura Selecionada:</strong><br>
                    <strong>ID Assinatura:</strong> <?php echo $sub->id; ?><br>
                    <strong>ID Usuário:</strong> <?php echo $sub->user_id; ?><br>
                    <strong>Nome:</strong> <?php echo $sub->user_nome; ?><br>
                    <strong>Email:</strong> <?php echo $sub->user_email; ?><br>
                    <strong>Plano:</strong> <?php echo $sub->plan_nome; ?><br>
                    <strong>Valor:</strong> R$ <?php echo number_format($sub->plan_preco, 2, ',', '.'); ?><br>
                    <strong>Status:</strong> <span style="color: <?php echo $sub->status == 'ativa' ? '#28a745' : '#ffc107'; ?>; font-weight: bold;"><?php echo strtoupper($sub->status); ?></span>
                </div>

                <h2>🎯 Simular Falhas de Pagamento</h2>
                
                <p>Clique nos botões abaixo para simular diferentes tentativas de cobrança:</p>

                <button class="btn-warning" onclick="simularFalha(1)">
                    ⚠️ Simular 1ª Tentativa
                </button>

                <button class="btn-warning" onclick="simularFalha(2)">
                    ⚠️ Simular 2ª Tentativa
                </button>

                <button class="btn-danger" onclick="simularFalha(3)">
                    🚨 Simular 3ª Tentativa
                </button>

                <button class="btn-danger" onclick="simularFalha(4)">
                    🚨 Simular 4ª Tentativa (ÚLTIMA)
                </button>

                <hr style="margin: 30px 0;">

                <button class="btn-success" onclick="simularSucesso()">
                    ✅ Simular Pagamento Bem-Sucedido
                </button>

                <button class="btn-primary" onclick="location.reload()">
                    🔄 Recarregar Página
                </button>

                <div id="resultado"></div>
            </div>

            <script>
                function trocarAssinatura() {
                    const select = document.getElementById('subscription_select');
                    const subscriptionId = select.value;
                    window.location.href = '<?php echo base_url('test_payment_failure?subscription_id='); ?>' + subscriptionId;
                }

                function buscarPorUserId() {
                    const userId = document.getElementById('user_id_input').value;
                    
                    if (!userId) {
                        alert('❌ Por favor, digite o ID do usuário');
                        return;
                    }

                    // Buscar no select a assinatura deste usuário
                    const select = document.getElementById('subscription_select');
                    const options = select.options;
                    let found = false;

                    for (let i = 0; i < options.length; i++) {
                        const optionText = options[i].text;
                        if (optionText.includes('ID Usuário: ' + userId + ' |')) {
                            select.selectedIndex = i;
                            trocarAssinatura();
                            found = true;
                            break;
                        }
                    }

                    if (!found) {
                        alert('❌ Nenhuma assinatura ativa encontrada para o usuário ID: ' + userId);
                    }
                }

                // Permitir buscar com Enter
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('user_id_input').addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            buscarPorUserId();
                        }
                    });
                });

                function simularFalha(tentativa) {
                    const resultado = document.getElementById('resultado');
                    resultado.innerHTML = '<div class="info">⏳ Processando...</div>';

                    fetch('<?php echo base_url('test_payment_failure/simular/'); ?>' + tentativa + '/<?php echo $sub->id; ?>')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                resultado.innerHTML = `
                                    <div class="success">
                                        <h3>✅ Falha Simulada com Sucesso!</h3>
                                        <p><strong>Tentativa:</strong> ${data.tentativa}ª de 4</p>
                                        <p><strong>Dias restantes:</strong> ${data.dias_restantes} dias</p>
                                        <p><strong>Status:</strong> ${data.status}</p>
                                        <p><strong>Email enviado para:</strong> ${data.email}</p>
                                        <hr>
                                        <p><strong>📧 Verifique o email!</strong></p>
                                        <p>Um email foi enviado com as informações da falha.</p>
                                    </div>
                                `;
                            } else {
                                resultado.innerHTML = `
                                    <div class="error">
                                        <h3>❌ Erro ao Simular</h3>
                                        <p>${data.error}</p>
                                    </div>
                                `;
                            }
                        })
                        .catch(error => {
                            resultado.innerHTML = `
                                <div class="error">
                                    <h3>❌ Erro</h3>
                                    <p>${error.message}</p>
                                </div>
                            `;
                        });
                }

                function simularSucesso() {
                    const resultado = document.getElementById('resultado');
                    resultado.innerHTML = '<div class="info">⏳ Processando...</div>';

                    fetch('<?php echo base_url('test_payment_failure/simular_sucesso/'); ?><?php echo $sub->id; ?>')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                resultado.innerHTML = `
                                    <div class="success">
                                        <h3>✅ Pagamento Bem-Sucedido Simulado!</h3>
                                        <p><strong>Status:</strong> ${data.status}</p>
                                        <p>A assinatura foi reativada.</p>
                                    </div>
                                `;
                            } else {
                                resultado.innerHTML = `
                                    <div class="error">
                                        <h3>❌ Erro ao Simular</h3>
                                        <p>${data.error}</p>
                                    </div>
                                `;
                            }
                        })
                        .catch(error => {
                            resultado.innerHTML = `
                                <div class="error">
                                    <h3>❌ Erro</h3>
                                    <p>${error.message}</p>
                                </div>
                            `;
                        });
                }
            </script>
        </body>
        </html>
        <?php
    }

    /**
     * Simular falha de pagamento
     */
    public function simular($attempt = 1, $subscription_id = null) {
        header('Content-Type: application/json');

        if (!$subscription_id) {
            echo json_encode(['success' => false, 'error' => 'ID da assinatura não fornecido']);
            return;
        }

        $subscription = $this->Subscription_model->get_by_id($subscription_id);

        if (!$subscription) {
            echo json_encode(['success' => false, 'error' => 'Assinatura não encontrada']);
            return;
        }

        // Calcular dias restantes
        $days_until_cancel = 14 - ($attempt * 3);
        if ($days_until_cancel < 0) $days_until_cancel = 0;

        // Atualizar status
        $this->Subscription_model->update($subscription->id, [
            'status' => 'pendente',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Buscar usuário
        $user = $this->User_model->get_by_id($subscription->user_id);

        // Enviar email
        if ($user) {
            $email_data = [
                'nome' => $user->nome,
                'plano_nome' => $subscription->plan_nome,
                'valor' => number_format($subscription->plan_preco, 2, ',', '.'),
                'tentativa' => $attempt,
                'dias_restantes' => $days_until_cancel,
                'portal_url' => base_url('planos/portal')
            ];

            $this->email_lib->send_payment_failed_improved($user, $email_data);
        }

        // Log
        log_message('info', "TESTE: Falha de pagamento simulada - Subscription ID: {$subscription->id}, Tentativa: {$attempt}");

        echo json_encode([
            'success' => true,
            'tentativa' => $attempt,
            'dias_restantes' => $days_until_cancel,
            'status' => 'pendente',
            'email' => $user->email
        ]);
    }

    /**
     * Simular pagamento bem-sucedido
     */
    public function simular_sucesso($subscription_id = null) {
        header('Content-Type: application/json');

        if (!$subscription_id) {
            echo json_encode(['success' => false, 'error' => 'ID da assinatura não fornecido']);
            return;
        }

        $subscription = $this->Subscription_model->get_by_id($subscription_id);

        if (!$subscription) {
            echo json_encode(['success' => false, 'error' => 'Assinatura não encontrada']);
            return;
        }

        // Reativar assinatura
        $this->Subscription_model->update($subscription->id, [
            'status' => 'ativa',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Log
        log_message('info', "TESTE: Pagamento bem-sucedido simulado - Subscription ID: {$subscription->id}");

        echo json_encode([
            'success' => true,
            'status' => 'ativa'
        ]);
    }
}
