<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Imóveis - ConectCorretores
 *
 * CRUD completo de imóveis
 *
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Imoveis extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Você precisa fazer login para acessar esta página.');
            redirect('login');
        }

        // Carregar models
        $this->load->model('Imovel_model');
        $this->load->library('pagination');
        $this->load->library('log_activity');
        $this->load->helper(['url', 'imovel', 'subscription']);
        $this->load->model('User_model');
        $this->load->model('Estado_model');
        $this->load->model('Cidade_model');
        
        // Carregar helper de assinaturas
        $this->load->helper('subscription');
    }

    /**
     * Listar todos os imóveis do corretor
     */
    public function index() {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        // Paginação
        $per_page = 12;
        $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : 0;

        // Filtros
        $filters = [];

        // Admin vê todos, corretor vê apenas seus
        if ($role !== 'admin') {
            $filters['user_id'] = $user_id;
        }

        if ($this->input->get('tipo_negocio')) {
            $filters['tipo_negocio'] = $this->input->get('tipo_negocio');
        }

        if ($this->input->get('tipo_imovel')) {
            $filters['tipo_imovel'] = $this->input->get('tipo_imovel');
        }

        if ($this->input->get('cidade')) {
            $filters['cidade'] = $this->input->get('cidade');
        }

        if ($this->input->get('search')) {
            $filters['search'] = $this->input->get('search');
        }

        // Incluir imóveis inativos no painel do corretor
        $filters['include_inactive'] = true;

        // Buscar imóveis
        $data['imoveis'] = $this->Imovel_model->get_all($filters, $per_page, $offset);
        $data['total'] = $this->Imovel_model->count_all($filters);

        // Paginação
        $data['per_page'] = $per_page;
        $data['offset'] = $offset;

        // Filtros para o formulário
        $data['tipos_imoveis'] = $this->Imovel_model->get_tipos_imoveis();
        $data['estados'] = $this->Imovel_model->get_estados();

        // Paginação HTML (Tabler style)
        $config['base_url'] = base_url('imoveis');
        $config['total_rows'] = $data['total'];
        $config['per_page'] = $per_page;
        $config['use_page_numbers'] = FALSE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'offset';
        $config['reuse_query_string'] = TRUE;
        
        // Tabler pagination style
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '›';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '‹';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        
        // Título
        $data['title'] = 'Meus Imóveis - ConectCorretores';
        $data['page'] = 'imoveis';

        $this->load->view('imoveis/index_tabler', $data);
    }

    /**
     * Visualizar detalhes do imóvel
     */
    public function ver($id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        // Admin pode ver todos, corretor apenas seus
        $imovel = $this->Imovel_model->get_by_id($id, $role === 'admin' ? null : $user_id);

        if (!$imovel) {
            $this->session->set_flashdata('error', 'Imóvel não encontrado.');
            redirect('imoveis');
        }

        $data['imovel'] = $imovel;
        $data['title'] = 'Detalhes do Imóvel - ConectCorretores';
        $data['page'] = 'imoveis';

        $this->load->view('imoveis/ver_tabler', $data);
    }

    /**
     * Formulário para novo imóvel
     */
    public function novo() {
        $user_id = $this->session->userdata('user_id');
        
        // Verificar se tem plano ativo
        if (!pode_gerenciar_imoveis($user_id)) {
            $this->session->set_flashdata('error', mensagem_bloqueio_imovel());
            redirect('planos');
            return;
        }
        
        // Processar formulário
        if ($this->input->post()) {
            $this->_process_criar();
            return;
        }

        // Mostrar formulário
        $data['title'] = 'Cadastrar Imóvel - ConectCorretores';
        $data['page'] = 'imoveis';
        // Não passar $data['imovel'] para indicar que é criação

        $this->load->view('imoveis/form_wizard', $data);
    }


    /**
     * Processar criação de imóvel
     */
    private function _process_criar() {
        $user_id = $this->session->userdata('user_id');
        
        // Verificar se tem plano ativo
        if (!pode_gerenciar_imoveis($user_id)) {
            $this->session->set_flashdata('error', mensagem_bloqueio_imovel());
            redirect('planos');
            return;
        }
        
        // Limpar formatação do preço e área
        $preco = $this->input->post('preco');
        $_POST['preco'] = str_replace(['R$', '.', ',', ' '], ['', '', '.', ''], $preco);
        
        $area = $this->input->post('area_privativa');
        $area_limpa = str_replace(['.', ','], ['', ''], $area);
        $_POST['area_privativa'] = (int)$area_limpa; // Converte para inteiro
        
        // Validações
        $this->form_validation->set_rules('tipo_negocio', 'Tipo de Negócio', 'required|in_list[compra,aluguel]');
        $this->form_validation->set_rules('tipo_imovel', 'Tipo de Imóvel', 'required|trim');
        $this->form_validation->set_rules('cep', 'CEP', 'trim');
        $this->form_validation->set_rules('estado_id', 'Estado', 'required|integer');
        $this->form_validation->set_rules('cidade_id', 'Cidade', 'required|integer');
        $this->form_validation->set_rules('bairro', 'Bairro', 'required|trim');
        $this->form_validation->set_rules('quartos', 'Quartos', 'integer');
        $this->form_validation->set_rules('vagas', 'Vagas', 'integer');
        $this->form_validation->set_rules('preco', 'Preço', 'required|decimal');
        $this->form_validation->set_rules('area_privativa', 'Área Privativa', 'required|numeric');
        $this->form_validation->set_rules('link_imovel', 'Link do Imóvel', 'trim|valid_url');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Cadastrar Imóvel - ConectCorretores';
            $data['page'] = 'imoveis';
            $this->load->view('imoveis/form', $data);
            return;
        }

        // Preparar dados
        $imovel_data = [
            'user_id' => $this->session->userdata('user_id'),
            'tipo_negocio' => $this->input->post('tipo_negocio'),
            'tipo_imovel' => $this->input->post('tipo_imovel'),
            'cep' => $this->input->post('cep'),
            'estado_id' => $this->input->post('estado_id'),
            'cidade_id' => $this->input->post('cidade_id'),
            'bairro' => $this->input->post('bairro'),
            'quartos' => $this->input->post('quartos') ? $this->input->post('quartos') : null,
            'vagas' => $this->input->post('vagas') ? $this->input->post('vagas') : null,
            'preco' => $this->input->post('preco'),
            'area_privativa' => $this->input->post('area_privativa'),
            'link_imovel' => $this->input->post('link_imovel'),
            'ativo' => 1,
        ];

        // Criar imóvel
        $imovel_id = $this->Imovel_model->create($imovel_data);

        if ($imovel_id) {
            // Registrar log
            $this->log_activity->create('imoveis', $imovel_id, "Criou novo imóvel: {$imovel_data['tipo_imovel']} em {$imovel_data['cidade']}");
            
            $this->session->set_flashdata('success', 'Imóvel cadastrado com sucesso!');
            redirect('imoveis/ver/' . $imovel_id);
        } else {
            $this->session->set_flashdata('error', 'Erro ao cadastrar imóvel.');
            redirect('imoveis/novo');
        }
    }

    /**
     * Formulário para editar imóvel
     */
    public function editar($id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');
        
        // Verificar se tem plano ativo (exceto admin)
        if ($role !== 'admin' && !pode_gerenciar_imoveis($user_id)) {
            $this->session->set_flashdata('error', mensagem_bloqueio_imovel(true));
            redirect('dashboard');
            return;
        }

        // Admin pode editar todos, corretor apenas seus
        $imovel = $this->Imovel_model->get_by_id($id, $role === 'admin' ? null : $user_id);

        if (!$imovel) {
            $this->session->set_flashdata('error', 'Imóvel não encontrado.');
            redirect('imoveis');
        }

        // Processar formulário
        if ($this->input->post()) {
            $this->_process_editar($id);
            return;
        }

        // Mostrar formulário
        $data['imovel'] = $imovel;
        $data['title'] = 'Editar Imóvel - ConectCorretores';
        $data['page'] = 'imoveis';

        $this->load->view('imoveis/form_wizard', $data);
    }

    /**
     * Processar edição de imóvel
     */
    private function _process_editar($id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');
        
        // Verificar se tem plano ativo (exceto admin)
        if ($role !== 'admin' && !pode_gerenciar_imoveis($user_id)) {
            $this->session->set_flashdata('error', mensagem_bloqueio_imovel(true));
            redirect('dashboard');
            return;
        }

        // Limpar formatação do preço e área
        $preco = $this->input->post('preco');
        $_POST['preco'] = str_replace(['R$', '.', ',', ' '], ['', '', '.', ''], $preco);
        
        $area = $this->input->post('area_privativa');
        $area_limpa = str_replace(['.', ','], ['', ''], $area);
        $_POST['area_privativa'] = (int)$area_limpa; // Converte para inteiro

        // Validações (mesmas do criar)
        $this->form_validation->set_rules('tipo_negocio', 'Tipo de Negócio', 'required|in_list[compra,aluguel]');
        $this->form_validation->set_rules('tipo_imovel', 'Tipo de Imóvel', 'required|trim');
        $this->form_validation->set_rules('cep', 'CEP', 'trim');
        $this->form_validation->set_rules('estado_id', 'Estado', 'required|integer');
        $this->form_validation->set_rules('cidade_id', 'Cidade', 'required|integer');
        $this->form_validation->set_rules('bairro', 'Bairro', 'required|trim');
        $this->form_validation->set_rules('quartos', 'Quartos', 'integer');
        $this->form_validation->set_rules('vagas', 'Vagas', 'integer');
        $this->form_validation->set_rules('preco', 'Preço', 'required|decimal');
        $this->form_validation->set_rules('area_privativa', 'Área Privativa', 'required|numeric');
        $this->form_validation->set_rules('link_imovel', 'Link do Imóvel', 'trim|valid_url');

        if ($this->form_validation->run() === FALSE) {
            $data['imovel'] = $this->Imovel_model->get_by_id($id, $role === 'admin' ? null : $user_id);
            $data['title'] = 'Editar Imóvel - ConectCorretores';
            $data['page'] = 'imoveis';
            $this->load->view('imoveis/form', $data);
            return;
        }

        // Preparar dados
        $imovel_data = [
            'tipo_negocio' => $this->input->post('tipo_negocio'),
            'tipo_imovel' => $this->input->post('tipo_imovel'),
            'cep' => $this->input->post('cep'),
            'estado_id' => $this->input->post('estado_id'),
            'cidade_id' => $this->input->post('cidade_id'),
            'bairro' => $this->input->post('bairro'),
            'quartos' => $this->input->post('quartos') ? $this->input->post('quartos') : null,
            'vagas' => $this->input->post('vagas') ? $this->input->post('vagas') : null,
            'preco' => $this->input->post('preco'),
            'area_privativa' => $this->input->post('area_privativa'),
            'link_imovel' => $this->input->post('link_imovel'),
        ];

        // Atualizar
        if ($this->Imovel_model->update($id, $imovel_data)) {
            // Registrar log
            $this->log_activity->update('imoveis', $id, "Atualizou imóvel: {$imovel_data['tipo_imovel']} em {$imovel_data['cidade']}");
            
            $this->session->set_flashdata('success', 'Imóvel atualizado com sucesso!');
            redirect('imoveis/ver/' . $id);
        } else {
            $this->session->set_flashdata('error', 'Erro ao atualizar imóvel.');
            redirect('imoveis/editar/' . $id);
        }
    }

    /**
     * Deletar imóvel
     */
    public function deletar($id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        if ($this->Imovel_model->delete($id, $role === 'admin' ? null : $user_id)) {
            $this->session->set_flashdata('success', 'Imóvel deletado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao deletar imóvel.');
        }

        redirect('imoveis');
    }

    /**
     * Ativar/Desativar imóvel (status_publicacao)
     */
    public function toggle_status($id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        $imovel = $this->Imovel_model->get_by_id($id, $role === 'admin' ? null : $user_id);

        if (!$imovel) {
            $this->session->set_flashdata('error', 'Imóvel não encontrado.');
            redirect('imoveis');
            return;
        }
        
        // Verificar se tem plano ativo para reativar (exceto admin)
        if ($role !== 'admin') {
            $status_atual = isset($imovel->status_publicacao) ? $imovel->status_publicacao : 'ativo';
            
            if ($status_atual !== 'ativo' && $status_atual !== 'inativo_manual') {
                // Imóvel desativado por falta de plano
                if (!pode_gerenciar_imoveis($user_id)) {
                    $this->session->set_flashdata('error', 'Você precisa de um plano ativo para reativar imóveis.');
                    redirect('imoveis');
                    return;
                }
            }
        }
        
        // Toggle status_publicacao e ativo
        $status_atual = isset($imovel->status_publicacao) ? $imovel->status_publicacao : 'ativo';
        $novo_status = ($status_atual === 'ativo') ? 'inativo_manual' : 'ativo';
        
        $data_update = [
            'status_publicacao' => $novo_status,
            'ativo' => ($novo_status === 'ativo') ? 1 : 0
        ];
        
        // Se estiver reativando um imóvel desativado por tempo, limpar campos de validação
        if ($novo_status === 'ativo' && in_array($status_atual, ['inativo_por_tempo', 'inativo_vendido', 'inativo_alugado'])) {
            $data_update['validacao_enviada_em'] = null;
            $data_update['validacao_expira_em'] = null;
            $data_update['validacao_confirmada_em'] = null;
            $data_update['validacao_token'] = null;
            
            // Se estava vendido/alugado, limpar também
            if (in_array($status_atual, ['inativo_vendido', 'inativo_alugado'])) {
                $data_update['status_venda'] = 'disponivel';
                $data_update['data_venda'] = null;
            }
        }
        
        $this->Imovel_model->update($id, $data_update);
        
        // Registrar log
        $status_text = ($novo_status === 'ativo') ? 'ativado' : 'desativado';
        $this->log_activity->status_change('imoveis', $id, $imovel->ativo ? 'ativo' : 'inativo', $novo_status);
        
        $mensagem = ($novo_status === 'ativo') ? 'Imóvel ativado com sucesso!' : 'Imóvel desativado com sucesso!';
        $this->session->set_flashdata('success', $mensagem);

        redirect('imoveis');
    }

    /**
     * Marcar/Desmarcar como destaque
     */
    public function toggle_destaque($id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        $imovel = $this->Imovel_model->get_by_id($id, $role === 'admin' ? null : $user_id);

        if (!$imovel) {
            $this->session->set_flashdata('error', 'Imóvel não encontrado.');
            redirect('imoveis');
        }

        $novo_destaque = $imovel->destaque ? 0 : 1;

        if ($this->Imovel_model->toggle_destaque($id, $novo_destaque, $role === 'admin' ? null : $user_id)) {
            $this->session->set_flashdata('success', 'Destaque atualizado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao atualizar destaque.');
        }

        redirect('imoveis');
    }

    /**
     * Marcar imóvel como vendido
     */
    public function marcar_vendido($id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        $imovel = $this->Imovel_model->get_by_id($id, $role === 'admin' ? null : $user_id);

        if (!$imovel) {
            $this->session->set_flashdata('error', 'Imóvel não encontrado.');
            redirect('imoveis');
            return;
        }

        $data_update = [
            'status_publicacao' => 'inativo_vendido',
            'ativo' => 0
        ];

        $this->Imovel_model->update($id, $data_update);
        
        // Registrar log
        $this->log_activity->status_change('imoveis', $id, 'ativo', 'vendido');
        
        $this->session->set_flashdata('success', 'Imóvel marcado como vendido!');
        redirect('imoveis/ver/' . $id);
    }

    /**
     * Marcar imóvel como alugado
     */
    public function marcar_alugado($id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');

        $imovel = $this->Imovel_model->get_by_id($id, $role === 'admin' ? null : $user_id);

        if (!$imovel) {
            $this->session->set_flashdata('error', 'Imóvel não encontrado.');
            redirect('imoveis');
            return;
        }

        $data_update = [
            'status_publicacao' => 'inativo_alugado',
            'ativo' => 0
        ];

        $this->Imovel_model->update($id, $data_update);
        
        // Registrar log
        $this->log_activity->status_change('imoveis', $id, 'ativo', 'alugado');
        
        $this->session->set_flashdata('success', 'Imóvel marcado como alugado!');
        redirect('imoveis/ver/' . $id);
    }

    /**
     * Buscar CEP via ViaCEP (AJAX)
     */
    public function buscar_cep() {
        // Verificar se é requisição AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $cep = $this->input->post('cep');

        if (!$cep) {
            echo json_encode(['success' => false, 'message' => 'CEP não informado']);
            return;
        }

        // Limpar CEP
        $cep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cep) !== 8) {
            echo json_encode(['success' => false, 'message' => 'CEP inválido']);
            return;
        }

        // Consultar ViaCEP
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            echo json_encode(['success' => false, 'message' => 'Erro ao consultar CEP']);
            return;
        }

        $data = json_decode($response, true);

        if (isset($data['erro']) && $data['erro'] === true) {
            echo json_encode(['success' => false, 'message' => 'CEP não encontrado']);
            return;
        }

        // Buscar ou criar estado
        $estado = $this->Estado_model->get_by_uf($data['uf']);

        if (!$estado) {
            echo json_encode(['success' => false, 'message' => 'Estado não encontrado']);
            return;
        }

        // Buscar ou criar cidade
        $cidade_id = $this->Cidade_model->get_or_create(
            $estado->id,
            $data['localidade'],
            $data['ibge'] ?? null
        );

        // Retornar dados
        echo json_encode([
            'success' => true,
            'data' => [
                'cep' => $data['cep'],
                'estado_id' => $estado->id,
                'estado_uf' => $estado->uf,
                'estado_nome' => $estado->nome,
                'cidade_id' => $cidade_id,
                'cidade_nome' => $data['localidade'],
                'bairro' => $data['bairro'] ?? ''
            ]
        ]);
    }

    /**
     * Buscar cidades por estado (AJAX)
     */
    public function get_cidades() {
        // Verificar se é requisição AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $estado_id = $this->input->post('estado_id');

        if (!$estado_id) {
            echo json_encode(['success' => false, 'message' => 'Estado não informado']);
            return;
        }

        $cidades = $this->Cidade_model->get_by_estado($estado_id);

        echo json_encode([
            'success' => true,
            'cidades' => $cidades
        ]);
    }

    /**
     * Buscar todos os estados (AJAX)
     */
    public function get_estados() {
        // Verificar se é requisição AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $estados = $this->Estado_model->get_all();

        echo json_encode([
            'success' => true,
            'estados' => $estados
        ]);
    }

    // ========================================
    // MÉTODOS DE VALIDAÇÃO DE IMÓVEIS (60 DIAS)
    // ========================================

    /**
     * Confirmar disponibilidade via link do email
     * 
     * URL: /imoveis/confirmar/{token}
     */
    public function confirmar($token = null) {
        if (!$token) {
            $this->session->set_flashdata('error', 'Token de validação não informado.');
            redirect('dashboard');
            return;
        }

        // Buscar imóvel pelo token
        $imovel = $this->Imovel_model->get_by_token($token);

        if (!$imovel) {
            $data['title'] = 'Token Inválido';
            $data['page'] = 'imoveis';
            $data['tipo'] = 'token_invalido';
            $data['mensagem'] = 'O link de validação é inválido ou já foi utilizado.';
            $this->load->view('imoveis/confirmacao', $data);
            return;
        }

        // Verificar se não expirou
        if ($imovel->validacao_expira_em && strtotime($imovel->validacao_expira_em) < time()) {
            $data['title'] = 'Prazo Expirado';
            $data['page'] = 'imoveis';
            $data['tipo'] = 'expirado';
            $data['mensagem'] = 'O prazo de 72 horas para validação já expirou. O imóvel foi desativado automaticamente.';
            $data['imovel'] = $imovel;
            $this->load->view('imoveis/confirmacao', $data);
            return;
        }

        // Confirmar disponibilidade
        if ($this->Imovel_model->confirmar_disponibilidade($token)) {
            $data['title'] = 'Imóvel Confirmado!';
            $data['page'] = 'imoveis';
            $data['tipo'] = 'sucesso_confirmado';
            $data['mensagem'] = 'Obrigado por confirmar! O imóvel continua ativo e disponível.';
            $data['imovel'] = $imovel;
            $this->load->view('imoveis/confirmacao', $data);
        } else {
            $data['title'] = 'Erro';
            $data['page'] = 'imoveis';
            $data['tipo'] = 'erro';
            $data['mensagem'] = 'Ocorreu um erro ao confirmar o imóvel. Tente novamente.';
            $data['imovel'] = $imovel;
            $this->load->view('imoveis/confirmacao', $data);
        }
    }

    /**
     * Marcar como vendido via link do email
     * 
     * URL: /imoveis/vendido/{token}
     */
    public function vendido($token = null) {
        if (!$token) {
            $this->session->set_flashdata('error', 'Token de validação não informado.');
            redirect('dashboard');
            return;
        }

        // Buscar imóvel pelo token
        $imovel = $this->Imovel_model->get_by_token($token);

        if (!$imovel) {
            $data['title'] = 'Token Inválido';
            $data['page'] = 'imoveis';
            $data['tipo'] = 'token_invalido';
            $data['mensagem'] = 'O link de validação é inválido ou já foi utilizado.';
            $this->load->view('imoveis/confirmacao', $data);
            return;
        }

        // Marcar como vendido
        if ($this->Imovel_model->marcar_como_negociado($token, 'vendido')) {
            $data['title'] = 'Parabéns pela Venda!';
            $data['page'] = 'imoveis';
            $data['tipo'] = 'sucesso_vendido';
            $data['mensagem'] = 'Parabéns! O imóvel foi marcado como vendido e desativado.';
            $data['imovel'] = $imovel;
            $this->load->view('imoveis/confirmacao', $data);
        } else {
            $data['title'] = 'Erro';
            $data['page'] = 'imoveis';
            $data['tipo'] = 'erro';
            $data['mensagem'] = 'Ocorreu um erro ao marcar o imóvel como vendido. Tente novamente.';
            $data['imovel'] = $imovel;
            $this->load->view('imoveis/confirmacao', $data);
        }
    }

    /**
     * Marcar como alugado via link do email
     * 
     * URL: /imoveis/alugado/{token}
     */
    public function alugado($token = null) {
        if (!$token) {
            $this->session->set_flashdata('error', 'Token de validação não informado.');
            redirect('dashboard');
            return;
        }

        // Buscar imóvel pelo token
        $imovel = $this->Imovel_model->get_by_token($token);

        if (!$imovel) {
            $data['title'] = 'Token Inválido';
            $data['page'] = 'imoveis';
            $data['tipo'] = 'token_invalido';
            $data['mensagem'] = 'O link de validação é inválido ou já foi utilizado.';
            $this->load->view('imoveis/confirmacao', $data);
            return;
        }

        // Marcar como alugado
        if ($this->Imovel_model->marcar_como_negociado($token, 'alugado')) {
            $data['title'] = 'Imóvel Alugado!';
            $data['page'] = 'imoveis';
            $data['tipo'] = 'sucesso_alugado';
            $data['mensagem'] = 'O imóvel foi marcado como alugado e desativado.';
            $data['imovel'] = $imovel;
            $this->load->view('imoveis/confirmacao', $data);
        } else {
            $data['title'] = 'Erro';
            $data['page'] = 'imoveis';
            $data['tipo'] = 'erro';
            $data['mensagem'] = 'Ocorreu um erro ao marcar o imóvel como alugado. Tente novamente.';
            $data['imovel'] = $imovel;
            $this->load->view('imoveis/confirmacao', $data);
        }
    }
}
