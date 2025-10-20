<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Dashboard - ConectCorretores
 * 
 * Painel principal do corretor
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Você precisa fazer login para acessar esta página.');
            redirect('login');
        }

        // Desabilitar cache do navegador
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

        // Carregar models
        $this->load->model('User_model');
        $this->load->model('Imovel_model');
        $this->load->model('Subscription_model');
        $this->load->model('Plan_model');
        
        // Carregar libraries
        $this->load->library('stripe_lib');
    }

    /**
     * Página principal do dashboard
     */
    public function index() {
        $user_id = $this->session->userdata('user_id');

        // Buscar estatísticas do usuário
        $data['stats'] = $this->User_model->get_stats($user_id);

        // Buscar assinatura ativa
        $data['subscription'] = $this->Subscription_model->get_active_by_user($user_id);
        
        // SINCRONIZAÇÃO HABILITADA PARA TESTES
        // Agora com validação de datas para evitar sobrescrever dados corretos
        if ($data['subscription']) {
            $this->_sync_subscription_with_stripe($data['subscription']);
            // Recarregar assinatura após sincronização
            $data['subscription'] = $this->Subscription_model->get_active_by_user($user_id);
        }

        // Buscar últimos imóveis cadastrados
        $data['recent_imoveis'] = $this->Imovel_model->get_all([
            'user_id' => $user_id
        ], 5, 0);

        // Dados do usuário
        $data['user'] = $this->User_model->get_by_id($user_id);

        // Título da página
        $data['title'] = 'Dashboard - ConectCorretores';
        $data['page'] = 'dashboard';

        // Carregar view
        $this->load->view('dashboard/index', $data);
    }

    /**
     * Página de perfil do usuário
     */
    public function perfil() {
        $user_id = $this->session->userdata('user_id');

        // Se for POST, processar edição
        if ($this->input->post()) {
            $this->_process_editar_perfil($user_id);
            return;
        }

        // Buscar dados do usuário
        $data['user'] = $this->User_model->get_by_id($user_id);
        
        // Buscar assinatura ativa
        $data['subscription'] = $this->Subscription_model->get_active_by_user($user_id);
        
        // Buscar estatísticas
        $data['stats'] = $this->User_model->get_stats($user_id);
        
        $data['title'] = 'Meu Perfil - ConectCorretores';
        $data['page'] = 'perfil';

        $this->load->view('dashboard/perfil', $data);
    }

    /**
     * Editar perfil (alias para perfil)
     */
    public function editar_perfil() {
        $this->perfil();
    }

    /**
     * Processar edição de perfil
     */
    private function _process_editar_perfil($user_id) {
        // Validações
        $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('telefone', 'Telefone', 'trim');
        $this->form_validation->set_rules('whatsapp', 'WhatsApp', 'trim');
        $this->form_validation->set_rules('cpf', 'CPF', 'trim');
        $this->form_validation->set_rules('endereco', 'Endereço', 'trim');

        // Se estiver mudando senha
        if ($this->input->post('nova_senha')) {
            $this->form_validation->set_rules('nova_senha', 'Nova Senha', 'min_length[6]');
            $this->form_validation->set_rules('confirmar_senha', 'Confirmação de Senha', 'matches[nova_senha]');
        }

        if ($this->form_validation->run() === FALSE) {
            $data['user'] = $this->User_model->get_by_id($user_id);
            $data['title'] = 'Meu Perfil - ConectCorretores';
            $data['page'] = 'perfil';
            $this->load->view('dashboard/perfil', $data);
            return;
        }

        // Preparar dados
        $update_data = [
            'nome' => $this->input->post('nome'),
            'email' => $this->input->post('email'),
            'telefone' => $this->input->post('telefone'),
            'whatsapp' => $this->input->post('whatsapp'),
            'cpf' => $this->input->post('cpf'),
            'endereco' => $this->input->post('endereco'),
        ];

        // Se estiver mudando senha
        if ($this->input->post('nova_senha')) {
            $update_data['senha'] = $this->input->post('nova_senha');
        }

        // Atualizar
        if ($this->User_model->update($user_id, $update_data)) {
            // Atualizar sessão
            $this->session->set_userdata('nome', $update_data['nome']);
            $this->session->set_userdata('email', $update_data['email']);

            $this->session->set_flashdata('success', 'Perfil atualizado com sucesso!');
            redirect('perfil');
        } else {
            $this->session->set_flashdata('error', 'Erro ao atualizar perfil.');
            redirect('perfil');
        }
    }

    /**
     * Sincronizar assinatura com Stripe
     * 
     * @param object $local_subscription Assinatura do banco de dados
     * @return void
     */
    private function _sync_subscription_with_stripe($local_subscription) {
        // Verificar se tem stripe_subscription_id
        if (!$local_subscription->stripe_subscription_id) {
            return;
        }

        try {
            // Buscar assinatura no Stripe
            $stripe_result = $this->stripe_lib->retrieve_subscription($local_subscription->stripe_subscription_id);

            if (!$stripe_result['success']) {
                // Erro ao buscar no Stripe, não fazer nada
                log_message('error', 'Erro ao sincronizar assinatura: ' . $stripe_result['error']);
                return;
            }

            $stripe_sub = $stripe_result['subscription'];
            $update_data = [];

            // 1. Sincronizar STATUS
            $stripe_status = $this->_map_stripe_status($stripe_sub->status);
            if ($stripe_status !== $local_subscription->status) {
                $update_data['status'] = $stripe_status;
                log_message('info', "Sincronização: Status alterado de '{$local_subscription->status}' para '{$stripe_status}'");
            }

            // 2. Sincronizar DATA DE FIM (current_period_end)
            $stripe_end_date = date('Y-m-d', $stripe_sub->current_period_end);
            $stripe_start_date = date('Y-m-d', $stripe_sub->current_period_start);
            
            // Validar se data fim é maior que data início (evitar dados inconsistentes)
            if ($stripe_end_date > $stripe_start_date && $stripe_end_date !== $local_subscription->data_fim) {
                $update_data['data_fim'] = $stripe_end_date;
                log_message('info', "Sincronização: Data fim alterada de '{$local_subscription->data_fim}' para '{$stripe_end_date}'");
            } elseif ($stripe_end_date <= $stripe_start_date) {
                log_message('error', "Sincronização: Data fim inválida no Stripe (fim <= início). Ignorando atualização.");
            }

            // 3. Sincronizar PLANO (price_id)
            $stripe_price_id = $stripe_sub->items->data[0]->price->id;
            // Comparar com o stripe_price_id do plano atual
            $current_plan_stripe_price_id = isset($local_subscription->plan_stripe_price_id) ? $local_subscription->plan_stripe_price_id : null;
            
            if ($stripe_price_id !== $current_plan_stripe_price_id) {
                // Buscar plano local pelo stripe_price_id
                $plan = $this->Plan_model->get_by_stripe_price_id($stripe_price_id);
                if ($plan) {
                    $update_data['plan_id'] = $plan->id;
                    log_message('info', "Sincronização: Plano alterado para '{$plan->nome}' (ID: {$plan->id})");
                } else {
                    log_message('warning', "Sincronização: Plano com stripe_price_id '{$stripe_price_id}' não encontrado no banco");
                }
            }

            // 4. Atualizar se houver mudanças
            if (!empty($update_data)) {
                $update_data['updated_at'] = date('Y-m-d H:i:s');
                $this->Subscription_model->update($local_subscription->id, $update_data);
                log_message('info', "Sincronização: Assinatura ID {$local_subscription->id} atualizada com sucesso");
            }

        } catch (Exception $e) {
            log_message('error', 'Exceção ao sincronizar assinatura: ' . $e->getMessage());
        }
    }

    /**
     * Mapear status do Stripe para status local
     * 
     * @param string $stripe_status Status do Stripe
     * @return string Status local
     */
    private function _map_stripe_status($stripe_status) {
        $status_map = [
            'active' => 'ativa',
            'past_due' => 'pendente',           // Pagamento atrasado
            'canceled' => 'cancelada',
            'unpaid' => 'pendente',             // Não pago
            'incomplete' => 'pendente',         // Checkout incompleto
            'incomplete_expired' => 'expirada',
            'trialing' => 'trial',              // Período de teste
            'paused' => 'pausada',              // Pausada
        ];

        return $status_map[$stripe_status] ?? 'pendente';
    }
}
