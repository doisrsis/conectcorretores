<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller de Erros - ConectCorretores
 * 
 * Páginas de erro personalizadas
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 18/10/2025
 */
class Errors extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Página 404
     */
    public function page_404() {
        $this->output->set_status_header('404');
        
        $data['title'] = 'Página não encontrada - 404';
        $data['heading'] = 'Página não encontrada';
        $data['message'] = 'A página que você está procurando não existe.';
        
        $this->load->view('errors/error_404', $data);
    }
}
