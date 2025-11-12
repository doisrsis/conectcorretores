<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test_tabler Controller
 * 
 * Controller de teste para verificar o layout Tabler
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 11/11/2025
 */
class Test_tabler extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Página de teste do layout
     */
    public function index() {
        // Simular sessão de usuário logado
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_userdata([
                'logged_in' => true,
                'user_id' => 1,
                'nome' => 'Rafael Dias',
                'email' => 'rafael@doisr.com.br',
                'role' => 'admin'
            ]);
        }
        
        // Dados da página
        $data['title'] = 'Teste Tabler';
        $data['page'] = 'dashboard';
        $data['page_header'] = 'Teste do Layout Tabler';
        $data['page_pretitle'] = 'ConectCorretores';
        $data['page_actions'] = '
            <a href="#" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                Novo Imóvel
            </a>
        ';
        
        // Conteúdo da página
        $content = '
            <div class="row row-deck row-cards">
                <!-- Stats Cards -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total de Imóveis</div>
                                <div class="ms-auto lh-1">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle text-secondary" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Últimos 7 dias</a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item active" href="#">Últimos 7 dias</a>
                                            <a class="dropdown-item" href="#">Últimos 30 dias</a>
                                            <a class="dropdown-item" href="#">Últimos 3 meses</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="stats-number">15</div>
                            <div class="stats-change positive">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                                12% este mês
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6 col-lg-3">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="subheader">Imóveis Ativos</div>
                            <div class="stats-number">12</div>
                            <div class="stats-change positive">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                                5% este mês
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6 col-lg-3">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="subheader">Vendas</div>
                            <div class="stats-number">R$ 2.5M</div>
                            <div class="stats-change positive">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                                18% este mês
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6 col-lg-3">
                    <div class="card stats-card">
                        <div class="card-body">
                            <div class="subheader">Usuários</div>
                            <div class="stats-number">8</div>
                            <div class="stats-change positive">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                                2 novos
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Success Message -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                Layout Tabler Funcionando!
                            </h3>
                            <p class="text-secondary">
                                Se você está vendo esta mensagem, o layout base do Tabler foi configurado corretamente com o design <strong>Fluid Vertical</strong>.
                            </p>
                            <div class="mt-3">
                                <h4>Características do Layout:</h4>
                                <ul>
                                    <li>✅ Sidebar vertical fixa à esquerda</li>
                                    <li>✅ Navbar superior com busca e perfil</li>
                                    <li>✅ Conteúdo fluido (sem container limitado)</li>
                                    <li>✅ Menu lateral sempre visível</li>
                                    <li>✅ Dark mode disponível</li>
                                    <li>✅ Totalmente responsivo</li>
                                </ul>
                            </div>
                            <div class="mt-3">
                                <h4>Próximos Passos:</h4>
                                <ol>
                                    <li>Adaptar página de login</li>
                                    <li>Adaptar dashboard real</li>
                                    <li>Adaptar listagem de imóveis</li>
                                    <li>Adaptar formulários</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Test Buttons -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Testes de Funcionalidades</h3>
                        </div>
                        <div class="card-body">
                            <div class="btn-list">
                                <button type="button" class="btn btn-primary" onclick="showToast(\'Sucesso! Toast funcionando.\', \'success\')">
                                    Testar Toast Success
                                </button>
                                <button type="button" class="btn btn-danger" onclick="showToast(\'Erro! Algo deu errado.\', \'error\')">
                                    Testar Toast Error
                                </button>
                                <button type="button" class="btn btn-warning" onclick="showToast(\'Atenção! Verifique os dados.\', \'warning\')">
                                    Testar Toast Warning
                                </button>
                                <button type="button" class="btn btn-info" onclick="showToast(\'Informação importante.\', \'info\')">
                                    Testar Toast Info
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
        
        $data['content'] = $content;
        
        // Carregar view com layout Tabler
        $this->load->view('templates/tabler/layout', $data);
    }
    
    /**
     * Teste de cards
     */
    public function cards() {
        $data['title'] = 'Teste de Cards';
        $data['page'] = 'dashboard';
        $data['page_header'] = 'Galeria de Cards';
        
        $content = '
            <div class="row row-cards">
                <div class="col-md-6 col-lg-4">
                    <div class="card card-imovel">
                        <img src="https://via.placeholder.com/400x200" class="card-img-top" alt="Imóvel">
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge badge-ativo">Ativo</span>
                                <span class="badge bg-blue">Venda</span>
                            </div>
                            <h3 class="card-title">Apartamento</h3>
                            <p class="text-secondary">São Paulo - SP</p>
                            <div class="d-flex gap-3 mb-3">
                                <span>🛏️ 2</span>
                                <span>🚗 1</span>
                                <span>📐 90m²</span>
                            </div>
                            <div class="h2 mb-3">R$ 100.000,00</div>
                            <div class="btn-list">
                                <a href="#" class="btn btn-outline-primary">Ver</a>
                                <a href="#" class="btn btn-primary">Editar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
        
        $data['content'] = $content;
        $this->load->view('templates/tabler/layout', $data);
    }
}
