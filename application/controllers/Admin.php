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
        
        // Carregar libraries
        $this->load->library('stripe_lib');
        $this->load->library('log_activity');
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
        
        $this->load->view('admin/usuarios/index_tabler', $data);
    }

    /**
     * Ver Detalhes do Usuário
     */
    public function ver_usuario($id) {
        $user = $this->User_model->get_by_id($id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'Usuário não encontrado.');
            redirect('admin/usuarios');
        }
        
        $data['user'] = $user;
        $data['title'] = 'Detalhes do Usuário - Admin';
        $data['page'] = 'admin_usuarios';
        
        // Se for corretor, buscar estatísticas
        if ($user->role === 'corretor') {
            $data['stats'] = new stdClass();
            $data['stats']->total_imoveis = $this->Imovel_model->count_by_user($user->id, ['include_inactive' => true]);
            $data['stats']->imoveis_ativos = $this->Imovel_model->count_by_user($user->id, ['ativo' => 1]);
            
            // Buscar assinatura
            $data['subscription'] = $this->Subscription_model->get_active_by_user($user->id);
        }
        
        $this->load->view('admin/usuarios/ver_tabler', $data);
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
        
        $this->load->view('admin/usuarios/editar_tabler', $data);
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
            $this->load->view('admin/usuarios/editar_tabler', $data);
            return;
        }
        
        $update_data = [
            'nome' => $this->input->post('nome'),
            'telefone' => $this->input->post('telefone'),
            'whatsapp' => $this->input->post('whatsapp'),
            'ativo' => $this->input->post('ativo'),
        ];
        
        if ($this->User_model->update($id, $update_data)) {
            // Registrar log
            $user = $this->User_model->get_by_id($id);
            $this->log_activity->update('users', $id, "Atualizou dados do usuário: {$user->nome}");
            
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
        
        // Buscar dados do usuário antes de deletar
        $user = $this->User_model->get_by_id($id);
        $user_name = $user ? $user->nome : 'Desconhecido';
        
        if ($this->User_model->delete($id)) {
            // Registrar log
            $this->log_activity->delete('users', $id, "Deletou usuário: {$user_name}");
            
            $this->session->set_flashdata('success', 'Usuário deletado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao deletar usuário.');
        }
        
        redirect('admin/usuarios');
    }

    /**
     * Gerenciar Planos (com integração Stripe)
     */
    public function planos() {
        // Buscar planos do banco
        $data['plans'] = $this->Plan_model->get_all(false); // Incluir inativos
        
        // Buscar produtos do Stripe
        $stripe_result = $this->stripe_lib->list_products();
        $data['stripe_products'] = $stripe_result['success'] ? $stripe_result['products'] : [];
        
        // Buscar preços do Stripe
        $prices_result = $this->stripe_lib->list_prices();
        $data['stripe_prices'] = $prices_result['success'] ? $prices_result['prices'] : [];
        
        $data['title'] = 'Gerenciar Planos - Admin';
        $data['page'] = 'admin_planos';
        
        $this->load->view('admin/planos/index', $data);
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
    
    // ========================================
    // GERENCIAMENTO DE PLANOS
    // ========================================
    
    /**
     * Criar novo plano
     */
    public function planos_criar() {
        if ($this->input->post()) {
            $nome = $this->input->post('nome');
            $descricao = $this->input->post('descricao');
            $preco = $this->input->post('preco');
            $tipo = $this->input->post('tipo');
            $limite_imoveis = $this->input->post('limite_imoveis');
            
            // Converter tipo para interval do Stripe
            $interval_map = [
                'mensal' => 'month',
                'trimestral' => 'month', // 3 meses
                'semestral' => 'month',  // 6 meses
                'anual' => 'year'
            ];
            $interval = $interval_map[$tipo] ?? 'month';
            
            // Criar produto no Stripe
            $product_result = $this->stripe_lib->create_product($nome, $descricao);
            
            if (!$product_result['success']) {
                $this->session->set_flashdata('error', 'Erro ao criar produto no Stripe: ' . $product_result['error']);
                redirect('admin/planos');
                return;
            }
            
            $product_id = $product_result['product']->id;
            
            // Criar preço no Stripe
            $price_result = $this->stripe_lib->create_price($product_id, $preco, 'brl', $interval);
            
            if (!$price_result['success']) {
                // Desativar produto se falhar ao criar preço
                $this->stripe_lib->deactivate_product($product_id);
                $this->session->set_flashdata('error', 'Erro ao criar preço no Stripe: ' . $price_result['error']);
                redirect('admin/planos');
                return;
            }
            
            $price_id = $price_result['price']->id;
            
            // Salvar no banco de dados
            $plan_data = [
                'nome' => $nome,
                'descricao' => $descricao,
                'preco' => $preco,
                'tipo' => $tipo,
                'stripe_product_id' => $product_id,
                'stripe_price_id' => $price_id,
                'limite_imoveis' => $limite_imoveis ? $limite_imoveis : null,
                'ativo' => 1
            ];
            
            if ($this->Plan_model->create($plan_data)) {
                $this->session->set_flashdata('success', 'Plano criado com sucesso!');
            } else {
                $this->session->set_flashdata('error', 'Erro ao salvar plano no banco de dados.');
            }
            
            redirect('admin/planos');
            return;
        }
        
        $data['title'] = 'Criar Plano - Admin';
        $data['page'] = 'admin_planos';
        
        $this->load->view('admin/planos/criar', $data);
    }
    
    /**
     * Editar plano
     */
    public function planos_editar($id) {
        $plan = $this->Plan_model->get_by_id($id);
        
        if (!$plan) {
            $this->session->set_flashdata('error', 'Plano não encontrado.');
            redirect('admin/planos');
            return;
        }
        
        if ($this->input->post()) {
            $nome = $this->input->post('nome');
            $descricao = $this->input->post('descricao');
            $preco = $this->input->post('preco');
            $limite_imoveis = $this->input->post('limite_imoveis');
            $ativo = $this->input->post('ativo');
            
            // Atualizar produto no Stripe
            if ($plan->stripe_product_id) {
                $update_result = $this->stripe_lib->update_product($plan->stripe_product_id, [
                    'name' => $nome,
                    'description' => $descricao
                ]);
                
                if (!$update_result['success']) {
                    $this->session->set_flashdata('error', 'Erro ao atualizar no Stripe: ' . $update_result['error']);
                    redirect('admin/planos/editar/' . $id);
                    return;
                }
            }
            
            // Se preço mudou, criar novo preço no Stripe
            if ($preco != $plan->preco && $plan->stripe_product_id) {
                $tipo = $plan->tipo;
                $interval_map = [
                    'mensal' => 'month',
                    'trimestral' => 'month',
                    'semestral' => 'month',
                    'anual' => 'year'
                ];
                $interval = $interval_map[$tipo] ?? 'month';
                
                // Desativar preço antigo
                if ($plan->stripe_price_id) {
                    $this->stripe_lib->deactivate_price($plan->stripe_price_id);
                }
                
                // Criar novo preço
                $price_result = $this->stripe_lib->create_price($plan->stripe_product_id, $preco, 'brl', $interval);
                
                if ($price_result['success']) {
                    $plan_data['stripe_price_id'] = $price_result['price']->id;
                }
            }
            
            // Atualizar no banco
            $plan_data['nome'] = $nome;
            $plan_data['descricao'] = $descricao;
            $plan_data['preco'] = $preco;
            $plan_data['limite_imoveis'] = $limite_imoveis ? $limite_imoveis : null;
            $plan_data['ativo'] = $ativo;
            
            if ($this->Plan_model->update($id, $plan_data)) {
                $this->session->set_flashdata('success', 'Plano atualizado com sucesso!');
            } else {
                $this->session->set_flashdata('error', 'Erro ao atualizar plano.');
            }
            
            redirect('admin/planos');
            return;
        }
        
        $data['plan'] = $plan;
        $data['title'] = 'Editar Plano - Admin';
        $data['page'] = 'admin_planos';
        
        $this->load->view('admin/planos/editar', $data);
    }
    
    /**
     * Excluir plano
     */
    public function planos_excluir($id) {
        $plan = $this->Plan_model->get_by_id($id);
        
        if (!$plan) {
            $this->session->set_flashdata('error', 'Plano não encontrado.');
            redirect('admin/planos');
            return;
        }
        
        // Verificar se há assinaturas ativas
        $this->db->where('plan_id', $id);
        $this->db->where('status', 'ativa');
        $active_subscriptions = $this->db->count_all_results('subscriptions');
        
        if ($active_subscriptions > 0) {
            $this->session->set_flashdata('error', 'Não é possível excluir plano com assinaturas ativas. Desative o plano ao invés de excluir.');
            redirect('admin/planos');
            return;
        }
        
        // Desativar no Stripe
        if ($plan->stripe_product_id) {
            $this->stripe_lib->deactivate_product($plan->stripe_product_id);
        }
        
        if ($plan->stripe_price_id) {
            $this->stripe_lib->deactivate_price($plan->stripe_price_id);
        }
        
        // Desativar no banco (não deletar)
        if ($this->Plan_model->update($id, ['ativo' => 0])) {
            $this->session->set_flashdata('success', 'Plano desativado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao desativar plano.');
        }
        
        redirect('admin/planos');
    }
    
    /**
     * Sincronizar planos do Stripe
     */
    public function planos_sincronizar() {
        $products_result = $this->stripe_lib->list_products();
        $prices_result = $this->stripe_lib->list_prices();
        
        if (!$products_result['success'] || !$prices_result['success']) {
            $this->session->set_flashdata('error', 'Erro ao buscar dados do Stripe.');
            redirect('admin/planos');
            return;
        }
        
        $synced = 0;
        $products = $products_result['products'];
        $prices = $prices_result['prices'];
        
        // Criar mapa de preços por produto
        $price_map = [];
        foreach ($prices as $price) {
            if (!isset($price_map[$price->product])) {
                $price_map[$price->product] = [];
            }
            $price_map[$price->product][] = $price;
        }
        
        foreach ($products as $product) {
            // Verificar se produto já existe no banco
            $existing = $this->Plan_model->get_by_stripe_product_id($product->id);
            
            if (!$existing && isset($price_map[$product->id])) {
                // Pegar primeiro preço ativo
                $price = $price_map[$product->id][0];
                
                // Converter interval para tipo
                $interval = $price->recurring->interval ?? 'month';
                $tipo_map = [
                    'month' => 'mensal',
                    'year' => 'anual'
                ];
                $tipo = $tipo_map[$interval] ?? 'mensal';
                
                // Criar plano no banco
                $plan_data = [
                    'nome' => $product->name,
                    'descricao' => $product->description,
                    'preco' => $price->unit_amount / 100,
                    'tipo' => $tipo,
                    'stripe_product_id' => $product->id,
                    'stripe_price_id' => $price->id,
                    'ativo' => 1
                ];
                
                if ($this->Plan_model->create($plan_data)) {
                    $synced++;
                }
            }
        }
        
        $this->session->set_flashdata('success', "$synced plano(s) sincronizado(s) do Stripe!");
        redirect('admin/planos');
    }

    /**
     * Dashboard de Inadimplência
     */
    public function inadimplencia() {
        $data['title'] = 'Inadimplência - Admin';
        $data['page'] = 'admin_inadimplencia';
        
        // Buscar assinaturas com problemas
        $data['payment_issues'] = $this->Subscription_model->get_payment_issues();
        $data['total_issues'] = $this->Subscription_model->count_payment_issues();
        
        // Estatísticas
        $total_valor_pendente = 0;
        foreach ($data['payment_issues'] as $issue) {
            $total_valor_pendente += $issue->plan_preco;
        }
        $data['total_valor_pendente'] = $total_valor_pendente;
        
        $this->load->view('admin/inadimplencia', $data);
    }

    /**
     * Logs de Atividade
     */
    public function logs() {
        // Carregar model
        $this->load->model('Activity_log_model');
        
        // Paginação
        $per_page = 50;
        $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;
        
        // Filtros
        $filters = [];
        if ($this->input->get('module')) {
            $filters['module'] = $this->input->get('module');
        }
        if ($this->input->get('action')) {
            $filters['action'] = $this->input->get('action');
        }
        if ($this->input->get('date_from')) {
            $filters['date_from'] = $this->input->get('date_from');
        }
        if ($this->input->get('date_to')) {
            $filters['date_to'] = $this->input->get('date_to');
        }
        if ($this->input->get('search')) {
            $filters['search'] = $this->input->get('search');
        }
        
        // Buscar logs
        $data['logs'] = $this->Activity_log_model->get_all($filters, $per_page, $offset);
        $data['total'] = $this->Activity_log_model->count_all($filters);
        
        // Estatísticas
        $data['stats'] = $this->Activity_log_model->get_statistics('today');
        
        // Paginação
        $data['per_page'] = $per_page;
        $data['offset'] = $offset;
        
        // Título
        $data['title'] = 'Logs de Atividade - Admin';
        $data['page'] = 'admin_logs';
        
        $this->load->view('admin/logs/index_tabler', $data);
    }
}
