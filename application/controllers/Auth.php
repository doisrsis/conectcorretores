<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller de Autenticação - ConectCorretores
 * 
 * Gerencia login, registro e logout de usuários
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->library('email_lib');
        $this->load->library('log_activity');
        $this->load->helper(['url', 'form']);
    }

    /**
     * Página de login
     */
    public function login() {
        // Se já estiver logado, redirecionar
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role();
            return;
        }

        // Processar formulário
        if ($this->input->post()) {
            $this->_process_login();
            return;
        }

        // Mostrar formulário
        $data['title'] = 'Login - ConectCorretores';
        $this->load->view('auth/login_tabler', $data);
    }

    /**
     * Processar login
     */
    private function _process_login() {
        // Validações
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Login - ConectCorretores';
            $this->load->view('auth/login_tabler', $data);
            return;
        }

        // Tentar autenticar
        $email = $this->input->post('email');
        $senha = $this->input->post('senha');

        $user = $this->User_model->authenticate($email, $senha);

        if ($user) {
            // Verificar se está ativo
            if ($user->ativo != 1) {
                $this->session->set_flashdata('error', 'Sua conta está desativada. Entre em contato com o suporte.');
                redirect('login');
                return;
            }

            // Criar sessão
            $session_data = [
                'user_id' => $user->id,
                'nome' => $user->nome,
                'email' => $user->email,
                'role' => $user->role,
                'logged_in' => TRUE
            ];

            $this->session->set_userdata($session_data);

            // Registrar log de login
            $this->log_activity->login($user->id, $user->nome);

            // Mensagem de sucesso
            $this->session->set_flashdata('success', 'Login realizado com sucesso! Bem-vindo, ' . $user->nome);

            // Redirecionar baseado no role
            $this->_redirect_by_role();
        } else {
            // Falha na autenticação
            $this->session->set_flashdata('error', 'Email ou senha incorretos.');
            redirect('login');
        }
    }

    /**
     * Página de registro
     */
    public function register() {
        // Se já estiver logado, redirecionar
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role();
            return;
        }

        // Processar formulário
        if ($this->input->post()) {
            $this->_process_register();
            return;
        }

        // Mostrar formulário
        $data['title'] = 'Cadastro - ConectCorretores';
        $this->load->view('auth/register_tabler', $data);
    }

    /**
     * Processar registro
     */
    private function _process_register() {
        // Validações
        $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]|max_length[255]|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]|trim', [
            'is_unique' => 'Este email já está cadastrado.'
        ]);
        $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]');
        $this->form_validation->set_rules('senha_confirm', 'Confirmação de Senha', 'required|matches[senha]');
        $this->form_validation->set_rules('telefone', 'Telefone', 'trim');
        $this->form_validation->set_rules('whatsapp', 'WhatsApp', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Cadastro - ConectCorretores';
            $this->load->view('auth/register_tabler', $data);
            return;
        }

        // Preparar dados
        $user_data = [
            'nome' => $this->input->post('nome'),
            'email' => $this->input->post('email'),
            'senha' => $this->input->post('senha'),
            'telefone' => $this->input->post('telefone'),
            'whatsapp' => $this->input->post('whatsapp'),
            'role' => 'corretor',
            'ativo' => 1
        ];

        // Criar usuário
        $user_id = $this->User_model->create($user_data);

        if ($user_id) {
            // Buscar usuário criado
            $user = $this->User_model->get_by_id($user_id);

            // Enviar email de boas-vindas
            $this->email_lib->send_welcome($user);

            // Criar sessão automaticamente
            $session_data = [
                'user_id' => $user->id,
                'nome' => $user->nome,
                'email' => $user->email,
                'role' => $user->role,
                'logged_in' => TRUE
            ];

            $this->session->set_userdata($session_data);

            // Mensagem de sucesso
            $this->session->set_flashdata('success', 'Cadastro realizado com sucesso! Bem-vindo, ' . $user->nome);

            // Redirecionar para escolher plano
            redirect('planos');
        } else {
            // Erro ao criar
            $this->session->set_flashdata('error', 'Erro ao criar conta. Tente novamente.');
            redirect('register');
        }
    }

    /**
     * Logout
     */
    public function logout() {
        // Registrar log de logout antes de destruir a sessão
        if ($this->session->userdata('logged_in')) {
            $this->log_activity->logout(
                $this->session->userdata('user_id'),
                $this->session->userdata('nome')
            );
        }
        
        // Destruir sessão
        $this->session->unset_userdata(['user_id', 'nome', 'email', 'role', 'logged_in']);
        $this->session->sess_destroy();

        // Mensagem
        $this->session->set_flashdata('success', 'Logout realizado com sucesso!');

        // Redirecionar
        redirect('login');
    }

    /**
     * Redirecionar baseado no role do usuário
     */
    private function _redirect_by_role() {
        $role = $this->session->userdata('role');

        if ($role === 'admin') {
            redirect('admin/dashboard');
        } else {
            redirect('dashboard');
        }
    }

    /**
     * Verificar se está logado (helper para outros controllers)
     * 
     * @return bool
     */
    public function check_login() {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Você precisa fazer login para acessar esta página.');
            redirect('login');
            return false;
        }
        return true;
    }

    /**
     * Verificar se é admin (helper para outros controllers)
     * 
     * @return bool
     */
    public function check_admin() {
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Acesso negado. Apenas administradores.');
            redirect('dashboard');
            return false;
        }
        return true;
    }
}
