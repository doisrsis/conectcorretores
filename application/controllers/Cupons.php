<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Cupons - ConectCorretores
 * 
 * Métodos públicos para validação de cupons (usado no checkout)
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 10/11/2025
 */
class Cupons extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Verificar se está logado
        if (!$this->session->userdata('logged_in')) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Você precisa estar logado para validar cupons.'
            ]);
            exit;
        }

        $this->load->model('Coupon_model');
    }

    /**
     * Validar cupom (AJAX) - Método público para checkout
     */
    public function validate_ajax() {
        // Definir header JSON
        header('Content-Type: application/json');
        
        $codigo = $this->input->post('codigo');
        $user_id = $this->session->userdata('user_id');
        
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
}
