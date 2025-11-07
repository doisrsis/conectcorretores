<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Password - ConectCorretores
 *
 * Recuperação de senha
 *
 * @author Rafael Dias - doisr.com.br
 * @date 07/11/2025
 */
class Password extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Password_reset_model');
        $this->load->library('form_validation');
        $this->load->library('email_lib');
    }

    /**
     * Formulário de solicitação de reset
     */
    public function forgot() {
        // Se já estiver logado, redirecionar para dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $data['title'] = 'Esqueci Minha Senha - ConectCorretores';
        $data['page'] = 'password_forgot';

        $this->load->view('password/forgot', $data);
    }

    /**
     * Processar solicitação de reset
     */
    public function send_reset() {
        // Validação
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('password/forgot');
        }

        $email = $this->input->post('email');

        // Buscar usuário
        $user = $this->User_model->get_by_email($email);

        if (!$user) {
            // Por segurança, não revelar se email existe ou não
            $this->session->set_flashdata('success', 'Se o e-mail estiver cadastrado, você receberá instruções para redefinir sua senha.');
            redirect('password/forgot');
        }

        // Verificar se usuário está ativo
        if (!$user->ativo) {
            $this->session->set_flashdata('error', 'Sua conta está desativada. Entre em contato com o suporte.');
            redirect('password/forgot');
        }

        // Criar token
        $token = $this->Password_reset_model->create_token($user->id);

        // Enviar email
        $result = $this->email_lib->send_password_reset($user, $token);

        if ($result) {
            log_message('info', "Token de reset enviado para: {$user->email}");
            $this->session->set_flashdata('success', 'Instruções para redefinir sua senha foram enviadas para seu e-mail.');
        } else {
            log_message('error', "Falha ao enviar email de reset para: {$user->email}");
            $this->session->set_flashdata('error', 'Erro ao enviar e-mail. Tente novamente mais tarde.');
        }

        redirect('password/forgot');
    }

    /**
     * Formulário de nova senha
     * 
     * @param string $token Token de reset
     */
    public function reset($token = null) {
        // Se já estiver logado, redirecionar para dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if (!$token) {
            $this->session->set_flashdata('error', 'Token inválido.');
            redirect('password/forgot');
        }

        // Validar token
        $reset = $this->Password_reset_model->validate_token($token);

        if (!$reset) {
            $this->session->set_flashdata('error', 'Token inválido ou expirado. Solicite um novo link de recuperação.');
            redirect('password/forgot');
        }

        $data['title'] = 'Redefinir Senha - ConectCorretores';
        $data['page'] = 'password_reset';
        $data['token'] = $token;
        $data['user_name'] = $reset->nome;

        $this->load->view('password/reset', $data);
    }

    /**
     * Processar atualização de senha
     */
    public function update_password() {
        // Validação
        $this->form_validation->set_rules('token', 'Token', 'required');
        $this->form_validation->set_rules('password', 'Nova senha', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Confirmação de senha', 'required|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('password/reset/' . $this->input->post('token'));
        }

        $token = $this->input->post('token');
        $new_password = $this->input->post('password');

        // Validar token novamente
        $reset = $this->Password_reset_model->validate_token($token);

        if (!$reset) {
            $this->session->set_flashdata('error', 'Token inválido ou expirado.');
            redirect('password/forgot');
        }

        // Atualizar senha (o model já faz o hash)
        $update_data = [
            'senha' => $new_password
        ];

        $updated = $this->User_model->update($reset->user_id, $update_data);

        if ($updated) {
            // Marcar token como usado
            $this->Password_reset_model->mark_as_used($token);

            log_message('info', "Senha redefinida para usuário ID: {$reset->user_id}");

            $this->session->set_flashdata('success', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
            redirect('login');
        } else {
            log_message('error', "Erro ao atualizar senha para usuário ID: {$reset->user_id}");
            $this->session->set_flashdata('error', 'Erro ao atualizar senha. Tente novamente.');
            redirect('password/reset/' . $token);
        }
    }

    /**
     * Limpar tokens expirados (para CRON job)
     */
    public function cleanup() {
        // Verificar se é chamada via CLI ou com chave secreta
        if (!is_cli() && $this->input->get('key') !== 'cleanup_secret_key_2025') {
            show_404();
            return;
        }

        $deleted = $this->Password_reset_model->delete_expired();

        if (is_cli()) {
            echo "Tokens expirados deletados: {$deleted}\n";
        } else {
            echo json_encode(['success' => true, 'deleted' => $deleted]);
        }
    }
}
