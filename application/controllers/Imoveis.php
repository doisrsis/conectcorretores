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
        $this->load->model('User_model');
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
        
        // Buscar imóveis
        $data['imoveis'] = $this->Imovel_model->get_all($filters, $per_page, $offset);
        $data['total'] = $this->Imovel_model->count_all($filters);
        
        // Paginação
        $data['per_page'] = $per_page;
        $data['offset'] = $offset;
        
        // Filtros para o formulário
        $data['tipos_imoveis'] = $this->Imovel_model->get_tipos_imoveis();
        $data['estados'] = $this->Imovel_model->get_estados();
        
        // Título
        $data['title'] = 'Meus Imóveis - ConectCorretores';
        $data['page'] = 'imoveis';
        
        $this->load->view('imoveis/index', $data);
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
        
        $this->load->view('imoveis/ver', $data);
    }

    /**
     * Formulário para novo imóvel
     */
    public function novo() {
        // Processar formulário
        if ($this->input->post()) {
            $this->_process_criar();
            return;
        }
        
        // Mostrar formulário
        $data['title'] = 'Cadastrar Imóvel - ConectCorretores';
        $data['page'] = 'imoveis';
        // Não passar $data['imovel'] para indicar que é criação
        
        $this->load->view('imoveis/form', $data);
    }

    /**
     * Processar criação de imóvel
     */
    private function _process_criar() {
        // Validações
        $this->form_validation->set_rules('tipo_imovel', 'Tipo de Imóvel', 'required|trim');
        $this->form_validation->set_rules('tipo_negocio', 'Tipo de Negócio', 'required|in_list[compra,aluguel]');
        $this->form_validation->set_rules('preco', 'Preço', 'required|numeric');
        $this->form_validation->set_rules('endereco', 'Endereço', 'required|trim');
        $this->form_validation->set_rules('bairro', 'Bairro', 'required|trim');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required|trim');
        $this->form_validation->set_rules('estado', 'Estado', 'required|exact_length[2]|trim');
        $this->form_validation->set_rules('cep', 'CEP', 'trim');
        $this->form_validation->set_rules('area_privativa', 'Área Privativa', 'required|numeric');
        $this->form_validation->set_rules('quartos', 'Quartos', 'required|integer');
        $this->form_validation->set_rules('banheiros', 'Banheiros', 'required|integer');
        $this->form_validation->set_rules('vagas', 'Vagas', 'required|integer');
        $this->form_validation->set_rules('descricao', 'Descrição', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Cadastrar Imóvel - ConectCorretores';
            $data['page'] = 'imoveis';
            $this->load->view('imoveis/form', $data);
            return;
        }

        // Preparar dados
        $imovel_data = [
            'user_id' => $this->session->userdata('user_id'),
            'tipo_imovel' => $this->input->post('tipo_imovel'),
            'tipo_negocio' => $this->input->post('tipo_negocio'),
            'preco' => $this->input->post('preco'),
            'endereco' => $this->input->post('endereco'),
            'numero' => $this->input->post('numero'),
            'complemento' => $this->input->post('complemento'),
            'bairro' => $this->input->post('bairro'),
            'cidade' => $this->input->post('cidade'),
            'estado' => strtoupper($this->input->post('estado')),
            'cep' => $this->input->post('cep'),
            'area_privativa' => $this->input->post('area_privativa'),
            'area_total' => $this->input->post('area_total'),
            'quartos' => $this->input->post('quartos'),
            'suites' => $this->input->post('suites'),
            'banheiros' => $this->input->post('banheiros'),
            'vagas' => $this->input->post('vagas'),
            'descricao' => $this->input->post('descricao'),
            'ativo' => 1,
        ];

        // Criar imóvel
        $imovel_id = $this->Imovel_model->create($imovel_data);

        if ($imovel_id) {
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
        
        $this->load->view('imoveis/form', $data);
    }

    /**
     * Processar edição de imóvel
     */
    private function _process_editar($id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');
        
        // Validações (mesmas do criar)
        $this->form_validation->set_rules('tipo_imovel', 'Tipo de Imóvel', 'required|trim');
        $this->form_validation->set_rules('tipo_negocio', 'Tipo de Negócio', 'required|in_list[compra,aluguel]');
        $this->form_validation->set_rules('preco', 'Preço', 'required|numeric');
        $this->form_validation->set_rules('endereco', 'Endereço', 'required|trim');
        $this->form_validation->set_rules('bairro', 'Bairro', 'required|trim');
        $this->form_validation->set_rules('cidade', 'Cidade', 'required|trim');
        $this->form_validation->set_rules('estado', 'Estado', 'required|exact_length[2]|trim');
        $this->form_validation->set_rules('area_privativa', 'Área Privativa', 'required|numeric');
        $this->form_validation->set_rules('quartos', 'Quartos', 'required|integer');
        $this->form_validation->set_rules('banheiros', 'Banheiros', 'required|integer');
        $this->form_validation->set_rules('vagas', 'Vagas', 'required|integer');

        if ($this->form_validation->run() === FALSE) {
            $data['imovel'] = $this->Imovel_model->get_by_id($id, $role === 'admin' ? null : $user_id);
            $data['title'] = 'Editar Imóvel - ConectCorretores';
            $data['page'] = 'imoveis';
            $this->load->view('imoveis/form', $data);
            return;
        }

        // Preparar dados
        $update_data = [
            'tipo_imovel' => $this->input->post('tipo_imovel'),
            'tipo_negocio' => $this->input->post('tipo_negocio'),
            'preco' => $this->input->post('preco'),
            'endereco' => $this->input->post('endereco'),
            'numero' => $this->input->post('numero'),
            'complemento' => $this->input->post('complemento'),
            'bairro' => $this->input->post('bairro'),
            'cidade' => $this->input->post('cidade'),
            'estado' => strtoupper($this->input->post('estado')),
            'cep' => $this->input->post('cep'),
            'area_privativa' => $this->input->post('area_privativa'),
            'area_total' => $this->input->post('area_total'),
            'quartos' => $this->input->post('quartos'),
            'suites' => $this->input->post('suites'),
            'banheiros' => $this->input->post('banheiros'),
            'vagas' => $this->input->post('vagas'),
            'descricao' => $this->input->post('descricao'),
        ];

        // Atualizar
        if ($this->Imovel_model->update($id, $update_data, $role === 'admin' ? null : $user_id)) {
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
     * Ativar/Desativar imóvel
     */
    public function toggle_status($id) {
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');
        
        $imovel = $this->Imovel_model->get_by_id($id, $role === 'admin' ? null : $user_id);
        
        if (!$imovel) {
            $this->session->set_flashdata('error', 'Imóvel não encontrado.');
            redirect('imoveis');
        }
        
        $novo_status = $imovel->ativo ? 0 : 1;
        
        if ($this->Imovel_model->toggle_status($id, $novo_status, $role === 'admin' ? null : $user_id)) {
            $this->session->set_flashdata('success', 'Status atualizado com sucesso!');
        } else {
            $this->session->set_flashdata('error', 'Erro ao atualizar status.');
        }
        
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
}
