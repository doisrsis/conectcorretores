<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Admin - ConectCorretores
 * 
 * Painel administrativo
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Você precisa fazer login para acessar esta página.');
            redirect('login');
        }

        // Verificar se é admin
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Acesso negado. Apenas administradores.');
            redirect('dashboard');
        }

        // Carregar models
        $this->load->model('User_model');
        $this->load->model('Imovel_model');
        $this->load->model('Subscription_model');
        $this->load->model('Plan_model');
    }

    /**
     * Dashboard Admin
     */
    public function dashboard() {
        // Estatísticas gerais
        $data['stats'] = new stdClass();
        
        // Total de corretores
        $data['stats']->total_corretores = $this->User_model->count_corretores();
        
        // Total de imóveis
        $data['stats']->total_imoveis = $this->Imovel_model->count_all(['include_inactive' => true]);
        
        // Assinaturas ativas
        $data['stats']->assinaturas_ativas = $this->Subscription_model->count_all(['status' => 'ativa']);
        
        // Receita mensal
        $data['stats']->receita_mensal = $this->Subscription_model->calculate_revenue('ativa');
        
        // Últimos corretores cadastrados
        $data['recent_users'] = $this->User_model->get_corretores(5, 0);
        
        // Últimas assinaturas
        $data['recent_subscriptions'] = $this->Subscription_model->get_recent(5);
        
        // Título da página
        $data['title'] = 'Admin Dashboard - ConectCorretores';
        $data['page'] = 'admin_dashboard';

        // Carregar view
        $this->load->view('admin/dashboard', $data);
    }

    /**
     * Gerenciar Usuários
     */
    public function usuarios() {
        // Paginação
        $per_page = 20;
        $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
        
        // Filtros
        $filters = [];
        if ($this->input->get('role')) {
            $filters['role'] = $this->input->get('role');
        }
        if ($this->input->get('search')) {
            $filters['search'] = $this->input->get('search');
        }
        
        // Buscar usuários
        $data['users'] = $this->User_model->get_all($filters, $per_page, $offset);
        $data['total'] = $this->User_model->count_all($filters);
        
        // Paginação
        $data['per_page'] = $per_page;
        $data['offset'] = $offset;
        
        // Título
        $data['title'] = 'Gerenciar Usuários - Admin';
        $data['page'] = 'admin_usuarios';
        
        $this->load->view('admin/usuarios', $data);
    }

    /**
     * Editar Usuário
     */
    public function editar_usuario($id) {
        $user = $this->User_model->get_by_id($id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'Usuário não encontrado.');
            redirect('admin/usuarios');
        }
        
        // Se for POST, processar
        if ($this->input->post()) {
            $this->_process_editar_usuario($id);
            return;
        }
        
        $data['user'] = $user;
        $data['title'] = 'Editar Usuário - Admin';
        $data['page'] = 'admin_usuarios';
        
        $this->load->view('admin/editar_usuario', $data);
    }

    /**
     * Processar edição de usuário
     */
    private function _process_editar_usuario($id) {
        $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]|trim');
        $this->form_validation->set_rules('ativo', 'Status', 'required|in_list[0,1]');
        
        if ($this->form_validation->run() === FALSE) {
            $data['user'] = $this->User_model->get_by_id($id);
            $data['title'] = 'Editar Usuário - Admin';
            $data['page'] = 'admin_usuarios';
            $this->load->view('admin/editar_usuario', $data);
            return;
        }
        
        $update_data = [
            'nome' => $this->input->post('nome'),
            'telefone' => $this->input->post('telefone'),
            'ativo' => $this->input->post('ativo'),
        ];
        
        if ($this->User_model->update($id, $update_data)) {
            $this->session->set_flashdata('success', 'Usuário atualizado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao atualizar usuário.');
        }
        
        redirect('admin/usuarios');
    }

    /**
     * Deletar Usuário
     */
    public function deletar_usuario($id) {
        // Não permitir deletar o próprio usuário
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Você não pode deletar sua própria conta.');
            redirect('admin/usuarios');
        }
        
        if ($this->User_model->delete($id)) {
            $this->session->set_flashdata('success', 'Usuário deletado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao deletar usuário.');
        }
        
        redirect('admin/usuarios');
    }

    /**
     * Gerenciar Planos
     */
    public function planos() {
        $data['plans'] = $this->Plan_model->get_all(false); // Incluir inativos
        $data['title'] = 'Gerenciar Planos - Admin';
        $data['page'] = 'admin_planos';
        
        $this->load->view('admin/planos', $data);
    }

    /**
     * Gerenciar Assinaturas
     */
    public function assinaturas() {
        // Paginação
        $per_page = 20;
        $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
        
        // Filtros
        $filters = [];
        if ($this->input->get('status')) {
            $filters['status'] = $this->input->get('status');
        }
        
        // Buscar assinaturas
        $data['subscriptions'] = $this->Subscription_model->get_all($filters, $per_page, $offset);
        $data['total'] = $this->Subscription_model->count_all($filters);
        
        // Paginação
        $data['per_page'] = $per_page;
        $data['offset'] = $offset;
        
        // Título
        $data['title'] = 'Gerenciar Assinaturas - Admin';
        $data['page'] = 'admin_assinaturas';
        
        $this->load->view('admin/assinaturas', $data);
    }

    /**
     * Relatórios
     */
    public function relatorios() {
        $data['title'] = 'Relatórios - Admin';
        $data['page'] = 'admin_relatorios';
        
        // Estatísticas por período
        $data['stats_mes'] = $this->_get_stats_periodo('month');
        $data['stats_ano'] = $this->_get_stats_periodo('year');
        
        $this->load->view('admin/relatorios', $data);
    }

    /**
     * Obter estatísticas por período
     */
    private function _get_stats_periodo($periodo) {
        $stats = new stdClass();
        
        // Definir data inicial
        if ($periodo === 'month') {
            $date_start = date('Y-m-01');
        } else {
            $date_start = date('Y-01-01');
        }
        
        // Novos corretores
        $this->db->where('role', 'corretor');
        $this->db->where('created_at >=', $date_start);
        $stats->novos_corretores = $this->db->count_all_results('users');
        
        // Novas assinaturas
        $this->db->where('created_at >=', $date_start);
        $stats->novas_assinaturas = $this->db->count_all_results('subscriptions');
        
        // Novos imóveis
        $this->db->where('created_at >=', $date_start);
        $stats->novos_imoveis = $this->db->count_all_results('imoveis');
        
        return $stats;
    }
}
