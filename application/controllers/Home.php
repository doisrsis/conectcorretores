<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Home - ConectCorretores
 * 
 * Página inicial (landing page)
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Página inicial
     */
    public function index() {
        // Se já estiver logado, redirecionar para dashboard
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            
            if ($role === 'admin') {
                redirect('admin/dashboard');
            } else {
                redirect('dashboard');
            }
            return;
        }

        // Carregar view da landing page
        $data['title'] = 'ConectCorretores - Gestão de Imóveis para Corretores';
        $this->load->view('home/index', $data);
    }
}
