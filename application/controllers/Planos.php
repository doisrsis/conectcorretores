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
            $this->load->view('planos/index', $data);
        } else {
            // Se não estiver logado, carregar sem sidebar
            $this->load->view('planos/index_public', $data);
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
            if ($this->Subscription_model->cancel($subscription->id)) {
                $this->session->set_flashdata('success', 'Assinatura cancelada com sucesso.');
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Erro ao cancelar assinatura.');
                redirect('planos/cancelar');
            }
        }

        // Mostrar confirmação
        $data['subscription'] = $subscription;
        $data['title'] = 'Cancelar Assinatura - ConectCorretores';
        $data['page'] = 'planos';
        
        $this->load->view('planos/cancelar', $data);
    }
}
