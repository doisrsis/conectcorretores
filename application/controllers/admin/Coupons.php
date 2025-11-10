<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Admin Coupons - ConectCorretores
 * 
 * Gerenciamento de cupons de desconto (Admin)
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 10/11/2025
 */
class Coupons extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        
        // Verificar se é admin
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Acesso negado. Apenas administradores.');
            redirect('dashboard');
        }
        
        // Carregar models e libraries
        $this->load->model('Coupon_model');
        $this->load->library('stripe_lib');
        $this->load->library('form_validation');
    }

    /**
     * Listar todos os cupons
     */
    public function index() {
        $data['coupons'] = $this->Coupon_model->get_all();
        $data['statistics'] = $this->Coupon_model->get_statistics();
        $data['title'] = 'Gerenciar Cupons - Admin';
        $data['page'] = 'admin/coupons';
        
        $this->load->view('admin/coupons/index', $data);
    }

    /**
     * Criar novo cupom
     */
    public function create() {
        if ($this->input->method() === 'post') {
            // Validação
            $this->form_validation->set_rules('codigo', 'Código', 'required|min_length[3]|max_length[50]|alpha_dash');
            $this->form_validation->set_rules('tipo', 'Tipo', 'required|in_list[percent,fixed]');
            $this->form_validation->set_rules('valor', 'Valor', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('duracao', 'Duração', 'required|in_list[once,repeating,forever]');
            
            if ($this->input->post('duracao') === 'repeating') {
                $this->form_validation->set_rules('duracao_meses', 'Duração em meses', 'required|integer|greater_than[0]');
            }
            
            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('admin/coupons/create');
            }
            
            // Preparar dados
            $coupon_data = [
                'codigo' => strtoupper($this->input->post('codigo')),
                'tipo' => $this->input->post('tipo'),
                'valor' => $this->input->post('valor'),
                'duracao' => $this->input->post('duracao'),
                'duracao_meses' => $this->input->post('duracao_meses'),
                'max_usos' => $this->input->post('max_usos') ?: null,
                'valido_de' => $this->input->post('valido_de') ?: null,
                'valido_ate' => $this->input->post('valido_ate') ?: null,
                'descricao' => $this->input->post('descricao'),
                'ativo' => $this->input->post('ativo') ? 1 : 0,
                'created_by' => $this->session->userdata('user_id')
            ];
            
            // Validar valor percentual
            if ($coupon_data['tipo'] === 'percent' && $coupon_data['valor'] > 100) {
                $this->session->set_flashdata('error', 'Desconto percentual não pode ser maior que 100%.');
                redirect('admin/coupons/create');
            }
            
            // Criar cupom no Stripe
            $stripe_result = $this->stripe_lib->create_coupon($coupon_data);
            
            if (!$stripe_result['success']) {
                $this->session->set_flashdata('error', 'Erro ao criar cupom no Stripe: ' . $stripe_result['error']);
                redirect('admin/coupons/create');
            }
            
            // Salvar ID do Stripe
            $coupon_data['stripe_coupon_id'] = $stripe_result['coupon']->id;
            
            // Criar cupom no banco local
            $coupon_id = $this->Coupon_model->create($coupon_data);
            
            if ($coupon_id) {
                $this->session->set_flashdata('success', 'Cupom criado com sucesso!');
                redirect('admin/coupons');
            } else {
                // Se falhar localmente, deletar do Stripe
                $this->stripe_lib->delete_coupon($stripe_result['coupon']->id);
                $this->session->set_flashdata('error', 'Erro ao salvar cupom no banco de dados.');
                redirect('admin/coupons/create');
            }
        }
        
        $data['title'] = 'Criar Cupom - Admin';
        $data['page'] = 'admin/coupons';
        
        $this->load->view('admin/coupons/create', $data);
    }

    /**
     * Editar cupom
     */
    public function edit($id) {
        $coupon = $this->Coupon_model->get_by_id($id);
        
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Cupom não encontrado.');
            redirect('admin/coupons');
        }
        
        if ($this->input->method() === 'post') {
            // Validação
            $this->form_validation->set_rules('max_usos', 'Máximo de usos', 'integer');
            $this->form_validation->set_rules('descricao', 'Descrição', 'max_length[500]');
            
            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('admin/coupons/edit/' . $id);
            }
            
            // Preparar dados (apenas campos editáveis)
            $update_data = [
                'max_usos' => $this->input->post('max_usos') ?: null,
                'valido_de' => $this->input->post('valido_de') ?: null,
                'valido_ate' => $this->input->post('valido_ate') ?: null,
                'descricao' => $this->input->post('descricao'),
                'ativo' => $this->input->post('ativo') ? 1 : 0
            ];
            
            // Atualizar no banco
            if ($this->Coupon_model->update($id, $update_data)) {
                $this->session->set_flashdata('success', 'Cupom atualizado com sucesso!');
                redirect('admin/coupons');
            } else {
                $this->session->set_flashdata('error', 'Erro ao atualizar cupom.');
                redirect('admin/coupons/edit/' . $id);
            }
        }
        
        $data['coupon'] = $coupon;
        $data['title'] = 'Editar Cupom - Admin';
        $data['page'] = 'admin/coupons';
        
        $this->load->view('admin/coupons/edit', $data);
    }

    /**
     * Deletar cupom
     */
    public function delete($id) {
        $coupon = $this->Coupon_model->get_by_id($id);
        
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Cupom não encontrado.');
            redirect('admin/coupons');
        }
        
        // Deletar do Stripe
        if ($coupon->stripe_coupon_id) {
            $stripe_result = $this->stripe_lib->delete_coupon($coupon->stripe_coupon_id);
            
            if (!$stripe_result['success']) {
                log_message('error', 'Erro ao deletar cupom do Stripe: ' . $stripe_result['error']);
            }
        }
        
        // Deletar do banco
        if ($this->Coupon_model->delete($id)) {
            $this->session->set_flashdata('success', 'Cupom deletado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao deletar cupom.');
        }
        
        redirect('admin/coupons');
    }

    /**
     * Ativar/Desativar cupom
     */
    public function toggle($id) {
        $coupon = $this->Coupon_model->get_by_id($id);
        
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Cupom não encontrado.');
            redirect('admin/coupons');
        }
        
        $new_status = $coupon->ativo ? 0 : 1;
        
        if ($this->Coupon_model->update($id, ['ativo' => $new_status])) {
            $status_text = $new_status ? 'ativado' : 'desativado';
            $this->session->set_flashdata('success', "Cupom {$status_text} com sucesso!");
        } else {
            $this->session->set_flashdata('error', 'Erro ao alterar status do cupom.');
        }
        
        redirect('admin/coupons');
    }

    /**
     * Ver detalhes e histórico de uso
     */
    public function view($id) {
        $coupon = $this->Coupon_model->get_by_id($id);
        
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Cupom não encontrado.');
            redirect('admin/coupons');
        }
        
        $data['coupon'] = $coupon;
        $data['usage_history'] = $this->Coupon_model->get_usage_history($id);
        $data['title'] = 'Detalhes do Cupom - Admin';
        $data['page'] = 'admin/coupons';
        
        $this->load->view('admin/coupons/view', $data);
    }

    /**
     * Validar cupom (AJAX)
     */
    public function validate_ajax() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $codigo = $this->input->post('codigo');
        $user_id = $this->input->post('user_id');
        
        if (!$codigo) {
            echo json_encode([
                'success' => false,
                'message' => 'Código do cupom não informado.'
            ]);
            return;
        }
        
        $validation = $this->Coupon_model->validate($codigo, $user_id);
        
        if ($validation['valid']) {
            $coupon = $validation['coupon'];
            
            // Calcular desconto para exibição
            $plan_price = $this->input->post('plan_price');
            if ($plan_price) {
                $discount = $this->Coupon_model->calculate_discount($coupon, $plan_price);
                
                echo json_encode([
                    'success' => true,
                    'message' => $validation['message'],
                    'coupon' => [
                        'id' => $coupon->id,
                        'codigo' => $coupon->codigo,
                        'tipo' => $coupon->tipo,
                        'valor' => $coupon->valor,
                        'duracao' => $coupon->duracao,
                        'desconto' => $discount['desconto'],
                        'valor_final' => $discount['valor_final']
                    ]
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'message' => $validation['message'],
                    'coupon' => [
                        'id' => $coupon->id,
                        'codigo' => $coupon->codigo,
                        'tipo' => $coupon->tipo,
                        'valor' => $coupon->valor,
                        'duracao' => $coupon->duracao
                    ]
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => $validation['message']
            ]);
        }
    }

    /**
     * Executar manutenção automática (desativar expirados)
     */
    public function maintenance() {
        $expired = $this->Coupon_model->deactivate_expired();
        $max_usage = $this->Coupon_model->deactivate_max_usage();
        
        $total = $expired + $max_usage;
        
        $this->session->set_flashdata('success', "{$total} cupom(ns) desativado(s) automaticamente.");
        redirect('admin/coupons');
    }
}
