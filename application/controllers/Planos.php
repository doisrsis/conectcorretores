<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Planos - ConectCorretores
 *
 * Página de planos e checkout
 *
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Planos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Plan_model');
        $this->load->model('Subscription_model');
        $this->load->model('User_model');
        $this->load->library('stripe_lib');
        $this->load->library('email_lib');
    }

    /**
     * Página de planos
     */
    public function index() {
        // Buscar planos ativos
        $data['plans'] = $this->Plan_model->get_all(true);

        // Se estiver logado, buscar assinatura atual
        if ($this->session->userdata('logged_in')) {
            $user_id = $this->session->userdata('user_id');
            $data['current_subscription'] = $this->Subscription_model->get_active_by_user($user_id);
        } else {
            $data['current_subscription'] = null;
        }

        $data['title'] = 'Planos - ConectCorretores';
        $data['page'] = 'planos';

        // Se estiver logado, carregar com sidebar
        if ($this->session->userdata('logged_in')) {
            $this->load->view('planos/index_tabler', $data);
        } else {
            // Se não estiver logado, carregar sem sidebar
            $this->load->view('planos/index_public_tabler', $data);
        }
    }

    /**
     * Escolher plano (redireciona para checkout)
     */
    public function escolher($plan_id) {
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Você precisa fazer login para assinar um plano.');
            $this->session->set_userdata('redirect_after_login', 'planos/escolher/' . $plan_id);
            redirect('login');
        }

        // Verificar se plano existe
        $plan = $this->Plan_model->get_by_id($plan_id);

        if (!$plan || !$plan->ativo) {
            $this->session->set_flashdata('error', 'Plano não encontrado ou inativo.');
            redirect('planos');
        }

        // Verificar se já tem assinatura ativa
        $user_id = $this->session->userdata('user_id');
        $current_subscription = $this->Subscription_model->get_active_by_user($user_id);

        if ($current_subscription) {
            $this->session->set_flashdata('error', 'Você já possui uma assinatura ativa. Cancele-a antes de assinar um novo plano.');
            redirect('dashboard');
        }

        // Redirecionar para checkout
        redirect('checkout/' . $plan_id);
    }

    /**
     * Iniciar período de teste gratuito (trial)
     * 
     * @param int $plan_id ID do plano
     */
    public function iniciar_trial($plan_id) {
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Você precisa fazer login para iniciar o período de teste.');
            $this->session->set_userdata('redirect_after_login', 'planos/iniciar_trial/' . $plan_id);
            redirect('login');
        }

        $user_id = $this->session->userdata('user_id');

        // Verificar se plano existe
        $plan = $this->Plan_model->get_by_id($plan_id);

        if (!$plan || !$plan->ativo) {
            $this->session->set_flashdata('error', 'Plano não encontrado ou inativo.');
            redirect('planos');
        }

        // Verificar se já tem assinatura ativa (incluindo trial)
        $current_subscription = $this->Subscription_model->get_active_by_user($user_id);

        if ($current_subscription) {
            $this->session->set_flashdata('error', 'Você já possui uma assinatura ativa.');
            redirect('dashboard');
        }

        // Verificar se já usou trial antes
        if ($this->Subscription_model->has_used_trial($user_id)) {
            $this->session->set_flashdata('error', 'Você já utilizou seu período de teste gratuito. Assine um plano para continuar.');
            redirect('planos');
        }

        // Criar trial (7 dias grátis)
        $trial_days = 7;
        $subscription_id = $this->Subscription_model->create_trial($user_id, $plan_id, $trial_days);

        if ($subscription_id) {
            // Buscar dados da assinatura criada
            $subscription = $this->Subscription_model->get_by_id($subscription_id);
            $user = $this->User_model->get_by_id($user_id);

            // Enviar email de boas-vindas ao trial
            $this->email_lib->send_trial_welcome($user, $subscription);

            // Mensagem de sucesso
            $this->session->set_flashdata('success', 
                "🎉 Parabéns! Seu período de teste de {$trial_days} dias foi ativado com sucesso! " .
                "Aproveite todas as funcionalidades do plano {$plan->nome} gratuitamente."
            );

            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Erro ao criar período de teste. Tente novamente.');
            redirect('planos');
        }
    }

    /**
     * Cancelar assinatura
     */
    public function cancelar() {
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $user_id = $this->session->userdata('user_id');
        $subscription = $this->Subscription_model->get_active_by_user($user_id);

        if (!$subscription) {
            $this->session->set_flashdata('error', 'Você não possui assinatura ativa.');
            redirect('dashboard');
        }

        // Processar cancelamento
        if ($this->input->post('confirmar')) {
            // Cancelar no Stripe primeiro
            if ($subscription->stripe_subscription_id) {
                $cancel_result = $this->stripe_lib->cancel_subscription($subscription->stripe_subscription_id);
                
                if (!$cancel_result['success']) {
                    $this->session->set_flashdata('error', 'Erro ao cancelar no Stripe: ' . $cancel_result['error']);
                    redirect('planos/cancelar');
                    return;
                }
            }
            
            // Cancelar no banco de dados
            if ($this->Subscription_model->cancel($subscription->id)) {
                // Enviar email de cancelamento confirmado
                $user = $this->User_model->get_by_id($user_id);
                if ($user) {
                    $this->email_lib->send_cancellation_confirmed($user, $subscription);
                }
                
                $this->session->set_flashdata('success', 'Assinatura cancelada com sucesso.');
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Erro ao cancelar assinatura no banco de dados.');
                redirect('planos/cancelar');
            }
        }

        // Mostrar confirmação
        $data['subscription'] = $subscription;
        $data['title'] = 'Cancelar Assinatura - ConectCorretores';
        $data['page'] = 'planos';

        $this->load->view('planos/cancelar', $data);
    }

    /**
     * Criar sessão de checkout Stripe (AJAX)
     */
    public function criar_checkout_session() {
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'error' => 'Você precisa fazer login.']);
            return;
        }

        // Obter plan_id do POST
        $plan_id = $this->input->post('plan_id');

        if (!$plan_id) {
            echo json_encode(['success' => false, 'error' => 'Plano não especificado.']);
            return;
        }

        // Buscar plano
        $plan = $this->Plan_model->get_by_id($plan_id);

        if (!$plan || !$plan->ativo) {
            echo json_encode(['success' => false, 'error' => 'Plano não encontrado.']);
            return;
        }

        // Verificar se tem stripe_price_id
        if (!$plan->stripe_price_id) {
            echo json_encode(['success' => false, 'error' => 'Plano não configurado no Stripe.']);
            return;
        }

        // Buscar dados do usuário
        $user_id = $this->session->userdata('user_id');
        $user = $this->User_model->get_by_id($user_id);

        // Verificar se já tem assinatura ativa
        $active_subscription = $this->Subscription_model->get_active_by_user($user_id);

        if ($active_subscription) {
            // Verificar se é o mesmo plano
            if ($active_subscription->plan_id == $plan_id) {
                echo json_encode(['success' => false, 'error' => 'Você já possui este plano ativo.']);
                return;
            }

            // Verificar se é upgrade ou downgrade
            $current_plan = $this->Plan_model->get_by_id($active_subscription->plan_id);
            $is_upgrade = $plan->preco > $current_plan->preco;

            // Cancelar assinatura antiga no Stripe
            if ($active_subscription->stripe_subscription_id) {
                $cancel_result = $this->stripe_lib->cancel_subscription($active_subscription->stripe_subscription_id);

                if (!$cancel_result['success']) {
                    echo json_encode(['success' => false, 'error' => 'Erro ao cancelar assinatura anterior: ' . $cancel_result['error']]);
                    return;
                }
            }

            // Atualizar status da assinatura antiga no banco
            $this->Subscription_model->update($active_subscription->id, [
                'status' => 'cancelada',
                'cancelada_em' => date('Y-m-d H:i:s')
            ]);
        }

        // Verificar se tem cupom
        $coupon_code = $this->input->post('coupon_code');
        
        // Criar sessão de checkout (com ou sem cupom)
        if ($coupon_code) {
            // Validar cupom antes de usar
            $this->load->model('Coupon_model');
            $validation = $this->Coupon_model->validate($coupon_code, $user_id);
            
            if (!$validation['valid']) {
                echo json_encode(['success' => false, 'error' => 'Cupom inválido: ' . $validation['message']]);
                return;
            }
            
            // Criar checkout com cupom
            $result = $this->stripe_lib->create_checkout_session_with_coupon($plan->stripe_price_id, [
                'user_id' => $user_id,
                'email' => $user->email,
                'plan_id' => $plan_id,
                'is_upgrade' => isset($is_upgrade) ? $is_upgrade : false,
                'success_url' => base_url('planos/sucesso?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => base_url('planos')
            ], $coupon_code);
            
            // Se sucesso, registrar uso do cupom
            if ($result['success']) {
                $coupon = $validation['coupon'];
                $discount_calc = $this->Coupon_model->calculate_discount($coupon, $plan->preco);
                
                // Registrar uso (subscription_id será atualizado depois via webhook)
                $this->Coupon_model->register_usage(
                    $coupon->id,
                    $user_id,
                    null, // subscription_id será preenchido depois
                    $discount_calc['desconto'],
                    $plan->preco,
                    $discount_calc['valor_final']
                );
            }
        } else {
            // Criar checkout sem cupom
            $result = $this->stripe_lib->create_checkout_session($plan->stripe_price_id, [
                'user_id' => $user_id,
                'email' => $user->email,
                'plan_id' => $plan_id,
                'is_upgrade' => isset($is_upgrade) ? $is_upgrade : false
            ]);
        }

        echo json_encode($result);
    }

    /**
     * Página de sucesso após pagamento
     */
    public function sucesso() {
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $session_id = $this->input->get('session_id');

        if (!$session_id) {
            $this->session->set_flashdata('error', 'Sessão inválida.');
            redirect('planos');
        }

        // Recuperar sessão do Stripe
        $result = $this->stripe_lib->retrieve_session($session_id);

        if (!$result['success']) {
            $this->session->set_flashdata('error', 'Erro ao recuperar sessão: ' . $result['error']);
            redirect('planos');
        }

        $session = $result['session'];

        // Verificar se pagamento foi concluído e processar assinatura
        if ($session->payment_status === 'paid') {
            // Processar assinatura se pagamento confirmado
            $user_id = $this->session->userdata('user_id');

            // Verificar se assinatura já existe
            $existing = $this->Subscription_model->get_by_stripe_id($session->subscription);

            if (!$existing && $session->subscription) {
                // Buscar plano pelo metadata
                $plan_id = $session->metadata->plan_id ?? null;

                if ($plan_id) {
                    $plan = $this->Plan_model->get_by_id($plan_id);

                    if ($plan) {
                        // Calcular data de expiração
                        $data_inicio = date('Y-m-d');
                        $data_fim = $this->Plan_model->calculate_expiration_date($plan->tipo, $data_inicio);

                        // Criar assinatura
                        $subscription_data = [
                            'user_id' => $user_id,
                            'plan_id' => $plan_id,
                            'stripe_subscription_id' => $session->subscription,
                            'stripe_customer_id' => $session->customer,
                            'status' => 'ativa',
                            'data_inicio' => $data_inicio,
                            'data_fim' => $data_fim
                        ];

                        $this->Subscription_model->create($subscription_data);

                        // Atualizar stripe_customer_id do usuário
                        $this->User_model->update($user_id, [
                            'stripe_customer_id' => $session->customer
                        ]);
                        
                        // Reativar imóveis
                        $this->load->model('Imovel_model');
                        $this->Imovel_model->reativar_por_renovacao_plano($user_id);
                        
                        // Log
                        log_message('info', "Imóveis reativados para usuário ID: {$user_id}");

                        $this->session->set_flashdata('success', 'Assinatura ativada com sucesso! Seus imóveis foram reativados.');
                    }
                }
            }
        } else {
            $this->session->set_flashdata('warning', 'Pagamento ainda não foi confirmado.');
        }

        $data['session'] = $session;
        $data['title'] = 'Pagamento Realizado - ConectCorretores';
        $data['page'] = 'planos';

        $this->load->view('planos/sucesso', $data);
    }

    /**
     * Página de cancelamento
     */
    public function cancelado() {
        $data['title'] = 'Pagamento Cancelado - ConectCorretores';
        $data['page'] = 'planos';

        $this->load->view('planos/cancelado', $data);
    }

    /**
     * Webhook do Stripe
     */
    public function webhook() {
        // Log início do webhook
        log_message('info', '========== WEBHOOK RECEBIDO ==========');
        
        // Obter payload
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        $this->config->load('stripe');
        $webhook_secret = $this->config->item('stripe_webhook_secret');
        
        log_message('info', 'Webhook Secret configurado: ' . ($webhook_secret ? 'SIM' : 'NÃO'));

        try {
            if ($webhook_secret) {
                $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $webhook_secret);
            } else {
                $event = json_decode($payload);
            }
            
            log_message('info', 'Evento recebido: ' . $event->type);

            // Processar evento
            switch ($event->type) {
                case 'checkout.session.completed':
                    log_message('info', 'Processando checkout.session.completed');
                    $this->_handle_checkout_completed($event->data->object);
                    break;

                case 'invoice.payment_succeeded':
                    log_message('info', 'Processando invoice.payment_succeeded');
                    $this->_handle_payment_succeeded($event->data->object);
                    break;

                case 'invoice.payment_failed':
                    log_message('info', 'Processando invoice.payment_failed');
                    $this->_handle_payment_failed($event->data->object);
                    break;

                case 'customer.subscription.updated':
                    log_message('info', 'Processando customer.subscription.updated');
                    $this->_handle_subscription_updated($event->data->object);
                    break;

                case 'customer.subscription.deleted':
                    log_message('info', 'Processando customer.subscription.deleted');
                    $this->_handle_subscription_deleted($event->data->object);
                    break;
                    
                default:
                    log_message('info', 'Evento não tratado: ' . $event->type);
            }
            
            log_message('info', 'Webhook processado com sucesso');
            log_message('info', '========================================');

            http_response_code(200);
            echo json_encode(['success' => true]);

        } catch (\Exception $e) {
            log_message('error', 'ERRO no webhook: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            log_message('info', '========================================');
            
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Processar checkout completado
     */
    private function _handle_checkout_completed($session) {
        log_message('info', '--- Iniciando _handle_checkout_completed ---');
        
        $user_id = $session->client_reference_id;
        $stripe_subscription_id = $session->subscription;
        $stripe_customer_id = $session->customer;
        
        log_message('info', 'User ID: ' . $user_id);
        log_message('info', 'Subscription ID: ' . $stripe_subscription_id);
        log_message('info', 'Customer ID: ' . $stripe_customer_id);

        // Buscar plano pelo metadata
        $plan_id = $session->metadata->plan_id ?? null;
        
        log_message('info', 'Plan ID do metadata: ' . $plan_id);

        if (!$plan_id) {
            log_message('error', 'Plan ID não encontrado no metadata!');
            return;
        }

        $plan = $this->Plan_model->get_by_id($plan_id);
        
        log_message('info', 'Plano encontrado: ' . ($plan ? $plan->nome : 'NÃO'));

        if (!$plan) {
            return;
        }

        // Calcular data de expiração
        $data_inicio = date('Y-m-d');
        $data_fim = $this->Plan_model->calculate_expiration_date($plan->tipo, $data_inicio);

        // Criar assinatura
        $subscription_data = [
            'user_id' => $user_id,
            'plan_id' => $plan_id,
            'stripe_subscription_id' => $stripe_subscription_id,
            'stripe_customer_id' => $stripe_customer_id,
            'status' => 'ativa',
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim
        ];

        $this->Subscription_model->create($subscription_data);

        // Atualizar stripe_customer_id do usuário
        $this->User_model->update($user_id, [
            'stripe_customer_id' => $stripe_customer_id
        ]);
        
        // Reativar imóveis
        $this->load->model('Imovel_model');
        $this->Imovel_model->reativar_por_renovacao_plano($user_id);
        log_message('info', 'Imóveis reativados');
        
        // Enviar email de assinatura ativada
        log_message('info', '--- Tentando enviar email de assinatura ativada ---');
        $user = $this->User_model->get_by_id($user_id);
        log_message('info', 'Usuário encontrado: ' . ($user ? $user->email : 'NÃO'));
        
        $subscription = $this->Subscription_model->get_active_by_user($user_id);
        log_message('info', 'Assinatura encontrada: ' . ($subscription ? 'SIM' : 'NÃO'));
        
        if ($user && $subscription) {
            log_message('info', 'Chamando email_lib->send_subscription_activated()');
            $result = $this->email_lib->send_subscription_activated($user, $plan, $subscription);
            log_message('info', 'Email enviado: ' . ($result ? 'SUCESSO' : 'FALHA'));
        } else {
            log_message('error', 'Não foi possível enviar email - usuário ou assinatura não encontrados');
        }
        
        // Log
        log_message('info', "Webhook: Imóveis reativados para usuário ID: {$user_id}");
        log_message('info', '--- Fim _handle_checkout_completed ---');
    }

    /**
     * Processar pagamento bem-sucedido (renovação)
     */
    private function _handle_payment_succeeded($invoice) {
        $stripe_subscription_id = $invoice->subscription;

        $subscription = $this->Subscription_model->get_by_stripe_id($stripe_subscription_id);

        if ($subscription) {
            // Renovar assinatura
            $plan = $this->Plan_model->get_by_id($subscription->plan_id);
            $nova_data_fim = $this->Plan_model->calculate_expiration_date($plan->tipo, $subscription->data_fim);

            $this->Subscription_model->update($subscription->id, [
                'data_fim' => $nova_data_fim,
                'status' => 'ativa'
            ]);
            
            // Enviar email de pagamento confirmado
            $user = $this->User_model->get_by_id($subscription->user_id);
            if ($user) {
                $valor = $invoice->amount_paid / 100; // Converter de centavos para reais
                $this->email_lib->send_payment_confirmed($user, $plan, $valor);
            }
        }
    }

    /**
     * Fazer upgrade de plano (AJAX)
     */
    public function upgrade() {
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'error' => 'Você precisa fazer login.']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $new_plan_id = $this->input->post('plan_id');

        if (!$new_plan_id) {
            echo json_encode(['success' => false, 'error' => 'Plano não especificado.']);
            return;
        }

        // Buscar assinatura atual
        $current_subscription = $this->Subscription_model->get_active_by_user($user_id);

        if (!$current_subscription) {
            echo json_encode(['success' => false, 'error' => 'Você não possui assinatura ativa.']);
            return;
        }

        // Buscar novo plano
        $new_plan = $this->Plan_model->get_by_id($new_plan_id);

        if (!$new_plan || !$new_plan->ativo) {
            echo json_encode(['success' => false, 'error' => 'Plano não encontrado.']);
            return;
        }

        // Verificar se é realmente upgrade (preço maior)
        if ($new_plan->preco <= $current_subscription->plan_preco) {
            echo json_encode(['success' => false, 'error' => 'Este não é um upgrade. Use a opção de downgrade.']);
            return;
        }

        // Verificar se novo plano tem stripe_price_id
        if (!$new_plan->stripe_price_id) {
            echo json_encode(['success' => false, 'error' => 'Plano não configurado no Stripe.']);
            return;
        }

        // Atualizar assinatura no Stripe
        if ($current_subscription->stripe_subscription_id) {
            $result = $this->stripe_lib->update_subscription(
                $current_subscription->stripe_subscription_id,
                $new_plan->stripe_price_id
            );

            if (!$result['success']) {
                echo json_encode(['success' => false, 'error' => 'Erro ao atualizar no Stripe: ' . $result['error']]);
                return;
            }
        }

        // Atualizar assinatura no banco de dados
        $update_data = [
            'plan_id' => $new_plan->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->Subscription_model->update($current_subscription->id, $update_data)) {
            // Enviar email de upgrade confirmado
            $user = $this->User_model->get_by_id($user_id);
            $old_plan = (object)[
                'nome' => $current_subscription->plan_nome,
                'preco' => $current_subscription->plan_preco,
                'limite_imoveis' => $current_subscription->plan_limite_imoveis
            ];
            if ($user) {
                $this->email_lib->send_upgrade_confirmed($user, $old_plan, $new_plan);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Upgrade realizado com sucesso!'
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erro ao atualizar assinatura no banco de dados.']);
        }
    }

    /**
     * Fazer downgrade de plano (AJAX)
     */
    public function downgrade() {
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            echo json_encode(['success' => false, 'error' => 'Você precisa fazer login.']);
            return;
        }

        $user_id = $this->session->userdata('user_id');
        $new_plan_id = $this->input->post('plan_id');

        if (!$new_plan_id) {
            echo json_encode(['success' => false, 'error' => 'Plano não especificado.']);
            return;
        }

        // Buscar assinatura atual
        $current_subscription = $this->Subscription_model->get_active_by_user($user_id);

        if (!$current_subscription) {
            echo json_encode(['success' => false, 'error' => 'Você não possui assinatura ativa.']);
            return;
        }

        // Buscar novo plano
        $new_plan = $this->Plan_model->get_by_id($new_plan_id);

        if (!$new_plan || !$new_plan->ativo) {
            echo json_encode(['success' => false, 'error' => 'Plano não encontrado.']);
            return;
        }

        // Verificar se é realmente downgrade (preço menor)
        if ($new_plan->preco >= $current_subscription->plan_preco) {
            echo json_encode(['success' => false, 'error' => 'Este não é um downgrade. Use a opção de upgrade.']);
            return;
        }

        // Verificar se novo plano tem stripe_price_id
        if (!$new_plan->stripe_price_id) {
            echo json_encode(['success' => false, 'error' => 'Plano não configurado no Stripe.']);
            return;
        }

        // Carregar model de imóveis
        $this->load->model('Imovel_model');
        
        // Verificar se precisa inativar imóveis
        $total_imoveis = $this->Imovel_model->count_by_user($user_id);
        $message = '';

        if ($new_plan->limite_imoveis && $total_imoveis > $new_plan->limite_imoveis) {
            // Inativar todos os imóveis
            $this->Imovel_model->inativar_todos_by_user($user_id);
            $message = "Seus imóveis foram inativados. Acesse 'Meus Imóveis' e reative até {$new_plan->limite_imoveis} imóveis.";
        }

        // Atualizar assinatura no Stripe
        if ($current_subscription->stripe_subscription_id) {
            $result = $this->stripe_lib->update_subscription(
                $current_subscription->stripe_subscription_id,
                $new_plan->stripe_price_id
            );

            if (!$result['success']) {
                echo json_encode(['success' => false, 'error' => 'Erro ao atualizar no Stripe: ' . $result['error']]);
                return;
            }
        }

        // Atualizar assinatura no banco de dados
        $update_data = [
            'plan_id' => $new_plan->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->Subscription_model->update($current_subscription->id, $update_data)) {
            // Enviar email de downgrade confirmado
            $user = $this->User_model->get_by_id($user_id);
            $old_plan = (object)[
                'nome' => $current_subscription->plan_nome,
                'preco' => $current_subscription->plan_preco,
                'limite_imoveis' => $current_subscription->plan_limite_imoveis
            ];
            if ($user) {
                $this->email_lib->send_downgrade_confirmed($user, $old_plan, $new_plan);
            }
            
            echo json_encode([
                'success' => true,
                'message' => $message ?: 'Downgrade realizado com sucesso!'
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erro ao atualizar assinatura no banco de dados.']);
        }
    }

    /**
     * Processar falha no pagamento
     */
    private function _handle_payment_failed($invoice) {
        $stripe_subscription_id = $invoice->subscription;

        $subscription = $this->Subscription_model->get_by_stripe_id($stripe_subscription_id);

        if ($subscription) {
            // Carregar helper de configurações
            $this->load->helper('settings');
            
            // Contar tentativas de pagamento
            $attempt_count = isset($invoice->attempt_count) ? $invoice->attempt_count : 1;
            
            // Calcular dias até cancelamento (baseado nas configurações)
            $grace_period = grace_period_days(); // Da configuração
            $retry_interval = retry_interval_days(); // Da configuração
            $days_until_cancel = $grace_period - ($attempt_count * $retry_interval);
            if ($days_until_cancel < 0) $days_until_cancel = 0;
            
            // Atualizar assinatura
            $update_data = [
                'status' => 'pendente',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->Subscription_model->update($subscription->id, $update_data);
            
            // Enviar email de falha no pagamento
            $user = $this->User_model->get_by_id($subscription->user_id);
            if ($user) {
                $email_data = [
                    'nome' => $user->nome,
                    'plano_nome' => $subscription->plan_nome,
                    'valor' => number_format($subscription->plan_preco, 2, ',', '.'),
                    'tentativa' => $attempt_count,
                    'dias_restantes' => $days_until_cancel,
                    'portal_url' => base_url('planos/portal')
                ];
                
                $this->email_lib->send_payment_failed_improved($user, $email_data);
            }
            
            log_message('info', "Falha de pagamento processada - Subscription ID: {$subscription->id}, Tentativa: {$attempt_count}");
        }
    }

    /**
     * Processar atualização de assinatura (webhook)
     */
    private function _handle_subscription_updated($stripe_subscription) {
        $subscription = $this->Subscription_model->get_by_stripe_id($stripe_subscription->id);

        if ($subscription) {
            $update_data = [];

            // Atualizar status
            $stripe_status = $this->_map_stripe_status($stripe_subscription->status);
            if ($stripe_status !== $subscription->status) {
                $update_data['status'] = $stripe_status;
            }

            // Atualizar data de fim (com validação)
            $stripe_end_date = date('Y-m-d', $stripe_subscription->current_period_end);
            $stripe_start_date = date('Y-m-d', $stripe_subscription->current_period_start);
            
            // Validar se data fim é maior que data início
            if ($stripe_end_date > $stripe_start_date && $stripe_end_date !== $subscription->data_fim) {
                $update_data['data_fim'] = $stripe_end_date;
            }

            // Atualizar plano (se mudou)
            $stripe_price_id = $stripe_subscription->items->data[0]->price->id;
            $plan = $this->Plan_model->get_by_stripe_price_id($stripe_price_id);
            if ($plan && $plan->id != $subscription->plan_id) {
                $update_data['plan_id'] = $plan->id;
            }

            // Atualizar se houver mudanças
            if (!empty($update_data)) {
                $update_data['updated_at'] = date('Y-m-d H:i:s');
                $this->Subscription_model->update($subscription->id, $update_data);
            }
        }
    }

    /**
     * Processar cancelamento de assinatura
     */
    private function _handle_subscription_deleted($stripe_subscription) {
        $subscription = $this->Subscription_model->get_by_stripe_id($stripe_subscription->id);

        if ($subscription) {
            $this->Subscription_model->cancel($subscription->id);
        }
    }

    /**
     * Mapear status do Stripe para status local
     */
    private function _map_stripe_status($stripe_status) {
        $status_map = [
            'active' => 'ativa',
            'past_due' => 'pendente',
            'canceled' => 'cancelada',
            'unpaid' => 'pendente',
            'incomplete' => 'pendente',
            'incomplete_expired' => 'expirada',
            'trialing' => 'trial',
            'paused' => 'pausada',
        ];

        return $status_map[$stripe_status] ?? 'pendente';
    }

    /**
     * Abrir Customer Portal do Stripe
     */
    public function portal() {
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Você precisa fazer login.');
            redirect('login');
            return;
        }

        $user_id = $this->session->userdata('user_id');

        // Buscar usuário
        $user = $this->User_model->get_by_id($user_id);

        if (!$user) {
            $this->session->set_flashdata('error', 'Usuário não encontrado.');
            redirect('dashboard');
            return;
        }

        // Verificar se tem stripe_customer_id
        if (empty($user->stripe_customer_id)) {
            $this->session->set_flashdata('error', 'Você precisa ter uma assinatura ativa para acessar o portal.');
            redirect('planos');
            return;
        }

        // URL de retorno
        $return_url = base_url('dashboard');

        // Criar sessão do portal
        $result = $this->stripe_lib->create_customer_portal($user->stripe_customer_id, $return_url);

        if ($result['success']) {
            // Redirecionar para o portal
            redirect($result['url']);
        } else {
            log_message('error', "Erro ao criar portal do Stripe: {$result['error']}");
            $this->session->set_flashdata('error', 'Erro ao abrir portal de gerenciamento. Tente novamente.');
            redirect('dashboard');
        }
    }
}
