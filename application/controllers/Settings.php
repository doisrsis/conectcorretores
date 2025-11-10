<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Settings - ConectCorretores
 * 
 * Gerenciamento de configurações do sistema (Admin)
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 08/11/2025
 */
class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // Verificar se é admin
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Acesso negado. Apenas administradores podem acessar esta área.');
            redirect('dashboard');
        }

        $this->load->model('Settings_model');
        $this->load->helper('settings');
    }

    /**
     * Página principal de configurações
     */
    public function index() {
        // Redirecionar para aba de assinaturas por padrão
        redirect('settings/assinaturas');
    }

    /**
     * Configurações de Assinaturas
     */
    public function assinaturas() {
        if ($this->input->post()) {
            $this->_save_settings('assinaturas');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('assinaturas', true);
        $data['title'] = 'Configurações de Assinaturas - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'assinaturas';

        $this->load->view('admin/settings/assinaturas', $data);
    }

    /**
     * Configurações do Site
     */
    public function site() {
        if ($this->input->post()) {
            $this->_save_settings('site');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('site', true);
        $data['title'] = 'Configurações do Site - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'site';

        $this->load->view('admin/settings/site', $data);
    }

    /**
     * Configurações de Email
     */
    public function email() {
        if ($this->input->post()) {
            $this->_save_settings('email');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('email', true);
        $data['title'] = 'Configurações de Email - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'email';

        $this->load->view('admin/settings/email', $data);
    }

    /**
     * Configurações de Segurança
     */
    public function seguranca() {
        if ($this->input->post()) {
            $this->_save_settings('seguranca');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('seguranca', true);
        $data['title'] = 'Configurações de Segurança - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'seguranca';

        $this->load->view('admin/settings/seguranca', $data);
    }

    /**
     * Configurações de Imóveis
     */
    public function imoveis() {
        if ($this->input->post()) {
            $this->_save_settings('imoveis');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('imoveis', true);
        $data['title'] = 'Configurações de Imóveis - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'imoveis';

        $this->load->view('admin/settings/imoveis', $data);
    }

    /**
     * Configurações do Sistema
     */
    public function sistema() {
        if ($this->input->post()) {
            $this->_save_settings('sistema');
            return;
        }

        $data['settings'] = $this->Settings_model->get_all('sistema', true);
        $data['title'] = 'Configurações do Sistema - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'sistema';

        $this->load->view('admin/settings/sistema', $data);
    }

    /**
     * Gerenciamento de Cupons
     */
    public function cupons() {
        // Carregar models necessários
        $this->load->model('Coupon_model');
        $this->load->library('stripe_lib');
        
        // Listar cupons
        $data['coupons'] = $this->Coupon_model->get_all();
        $data['statistics'] = $this->Coupon_model->get_statistics();
        $data['title'] = 'Cupons de Desconto - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'cupons';
        
        $this->load->view('admin/settings/cupons/index', $data);
    }
    
    /**
     * Criar cupom
     */
    public function cupons_create() {
        $this->load->model('Coupon_model');
        $this->load->library('stripe_lib');
        $this->load->library('form_validation');
        
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
                redirect('settings/cupons_create');
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
                redirect('settings/cupons_create');
            }
            
            // Validar duração "forever" apenas para cupons percentuais (limitação do Stripe)
            if ($coupon_data['tipo'] === 'fixed' && $coupon_data['duracao'] === 'forever') {
                $this->session->set_flashdata('error', 'Cupons de valor fixo não podem ter duração "Para Sempre". Use "Uma Vez" ou "Recorrente".');
                redirect('settings/cupons_create');
            }
            
            // Criar cupom no Stripe
            $stripe_result = $this->stripe_lib->create_coupon($coupon_data);
            
            if (!$stripe_result['success']) {
                $this->session->set_flashdata('error', 'Erro ao criar cupom no Stripe: ' . $stripe_result['error']);
                redirect('settings/cupons_create');
            }
            
            // Salvar ID do Stripe
            $coupon_data['stripe_coupon_id'] = $stripe_result['coupon']->id;
            
            // Criar cupom no banco local
            $coupon_id = $this->Coupon_model->create($coupon_data);
            
            if ($coupon_id) {
                $this->session->set_flashdata('success', 'Cupom criado com sucesso!');
                redirect('settings/cupons');
            } else {
                // Se falhar localmente, deletar do Stripe
                $this->stripe_lib->delete_coupon($stripe_result['coupon']->id);
                $this->session->set_flashdata('error', 'Erro ao salvar cupom no banco de dados.');
                redirect('settings/cupons_create');
            }
        }
        
        $data['title'] = 'Criar Cupom - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'cupons';
        
        $this->load->view('admin/settings/cupons/create', $data);
    }
    
    /**
     * Editar cupom
     */
    public function cupons_edit($id) {
        $this->load->model('Coupon_model');
        $this->load->library('form_validation');
        
        $coupon = $this->Coupon_model->get_by_id($id);
        
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Cupom não encontrado.');
            redirect('settings/cupons');
        }
        
        if ($this->input->method() === 'post') {
            // Validação
            $this->form_validation->set_rules('max_usos', 'Máximo de usos', 'integer');
            $this->form_validation->set_rules('descricao', 'Descrição', 'max_length[500]');
            
            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('settings/cupons_edit/' . $id);
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
                redirect('settings/cupons');
            } else {
                $this->session->set_flashdata('error', 'Erro ao atualizar cupom.');
                redirect('settings/cupons_edit/' . $id);
            }
        }
        
        $data['coupon'] = $coupon;
        $data['title'] = 'Editar Cupom - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'cupons';
        
        $this->load->view('admin/settings/cupons/edit', $data);
    }
    
    /**
     * Ver detalhes do cupom
     */
    public function cupons_view($id) {
        $this->load->model('Coupon_model');
        
        $coupon = $this->Coupon_model->get_by_id($id);
        
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Cupom não encontrado.');
            redirect('settings/cupons');
        }
        
        $data['coupon'] = $coupon;
        $data['usage_history'] = $this->Coupon_model->get_usage_history($id);
        $data['title'] = 'Detalhes do Cupom - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'cupons';
        
        $this->load->view('admin/settings/cupons/view', $data);
    }
    
    /**
     * Deletar cupom
     */
    public function cupons_delete($id) {
        $this->load->model('Coupon_model');
        $this->load->library('stripe_lib');
        
        $coupon = $this->Coupon_model->get_by_id($id);
        
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Cupom não encontrado.');
            redirect('settings/cupons');
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
        
        redirect('settings/cupons');
    }
    
    /**
     * Ativar/Desativar cupom
     */
    public function cupons_toggle($id) {
        $this->load->model('Coupon_model');
        
        $coupon = $this->Coupon_model->get_by_id($id);
        
        if (!$coupon) {
            $this->session->set_flashdata('error', 'Cupom não encontrado.');
            redirect('settings/cupons');
        }
        
        $new_status = $coupon->ativo ? 0 : 1;
        
        if ($this->Coupon_model->update($id, ['ativo' => $new_status])) {
            $status_text = $new_status ? 'ativado' : 'desativado';
            $this->session->set_flashdata('success', "Cupom {$status_text} com sucesso!");
        } else {
            $this->session->set_flashdata('error', 'Erro ao alterar status do cupom.');
        }
        
        redirect('settings/cupons');
    }
    
    /**
     * Manutenção automática de cupons
     */
    public function cupons_maintenance() {
        $this->load->model('Coupon_model');
        
        $expired = $this->Coupon_model->deactivate_expired();
        $max_usage = $this->Coupon_model->deactivate_max_usage();
        
        $total = $expired + $max_usage;
        
        $this->session->set_flashdata('success', "{$total} cupom(ns) desativado(s) automaticamente.");
        redirect('settings/cupons');
    }
    
    /**
     * Validar cupom (AJAX)
     */
    public function cupons_validate_ajax() {
        // Definir header JSON
        header('Content-Type: application/json');
        
        $this->load->model('Coupon_model');
        
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
     * Todas as configurações (visualização)
     */
    public function todas() {
        $data['grouped_settings'] = $this->Settings_model->get_grouped(false);
        $data['title'] = 'Todas as Configurações - Admin';
        $data['page'] = 'settings';
        $data['active_tab'] = 'todas';

        $this->load->view('admin/settings/todas', $data);
    }

    /**
     * Limpar cache de configurações
     */
    public function limpar_cache() {
        $this->Settings_model->clear_cache();
        
        $this->session->set_flashdata('success', 'Cache de configurações limpo com sucesso!');
        redirect($this->input->server('HTTP_REFERER') ?: 'settings');
    }

    /**
     * Salvar configurações
     * 
     * @param string $grupo Grupo das configurações
     */
    private function _save_settings($grupo) {
        $settings = $this->Settings_model->get_all($grupo, true);
        $updates = [];
        $errors = [];

        foreach ($settings as $setting) {
            $chave = $setting->chave;
            $valor = $this->input->post($chave);

            // Tratar checkbox (bool)
            if ($setting->tipo === 'bool') {
                $valor = $valor ? '1' : '0';
            }

            // Validar valor
            if (!$this->_validate_setting($valor, $setting->tipo)) {
                $errors[] = "Valor inválido para '{$setting->chave}'";
                continue;
            }

            $updates[$chave] = $valor;
        }

        if (!empty($errors)) {
            $this->session->set_flashdata('error', implode('<br>', $errors));
            redirect('settings/' . $grupo);
            return;
        }

        // Salvar todas as configurações
        if ($this->Settings_model->update_batch($updates)) {
            $this->session->set_flashdata('success', 'Configurações salvas com sucesso!');
            
            // Log da alteração
            log_message('info', "Configurações do grupo '{$grupo}' atualizadas pelo admin ID: " . $this->session->userdata('user_id'));
        } else {
            $this->session->set_flashdata('error', 'Erro ao salvar configurações.');
        }

        redirect('settings/' . $grupo);
    }

    /**
     * Validar valor de configuração
     * 
     * @param mixed $valor Valor a validar
     * @param string $tipo Tipo esperado
     * @return bool Válido
     */
    private function _validate_setting($valor, $tipo) {
        switch ($tipo) {
            case 'int':
                return is_numeric($valor) && (int)$valor == $valor && (int)$valor >= 0;
            
            case 'float':
                return is_numeric($valor) && (float)$valor >= 0;
            
            case 'bool':
                return in_array($valor, ['0', '1'], true);
            
            case 'json':
                if (empty($valor)) return true;
                json_decode($valor);
                return json_last_error() === JSON_ERROR_NONE;
            
            default:
                return true;
        }
    }

    /**
     * API: Obter valor de configuração (AJAX)
     */
    public function get_value() {
        $chave = $this->input->get('chave');
        
        if (!$chave) {
            echo json_encode(['success' => false, 'error' => 'Chave não informada']);
            return;
        }

        $valor = $this->Settings_model->get($chave);
        
        echo json_encode([
            'success' => true,
            'chave' => $chave,
            'valor' => $valor
        ]);
    }

    /**
     * API: Definir valor de configuração (AJAX)
     */
    public function set_value() {
        $chave = $this->input->post('chave');
        $valor = $this->input->post('valor');
        
        if (!$chave) {
            echo json_encode(['success' => false, 'error' => 'Chave não informada']);
            return;
        }

        $success = $this->Settings_model->set($chave, $valor);
        
        echo json_encode([
            'success' => $success,
            'chave' => $chave,
            'valor' => $valor
        ]);
    }
}
