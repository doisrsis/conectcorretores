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

        // Carregar models
        $this->load->model('User_model');
        $this->load->model('Imovel_model');
        $this->load->model('Subscription_model');
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
}
