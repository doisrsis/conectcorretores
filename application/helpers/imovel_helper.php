<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper de Imóveis - ConectCorretores
 * 
 * Funções auxiliares para manipulação de imóveis
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 11/11/2025
 */

if (!function_exists('pode_gerenciar_imoveis')) {
    /**
     * Verifica se o usuário pode gerenciar imóveis
     * 
     * @param int $user_id ID do usuário
     * @return bool
     */
    function pode_gerenciar_imoveis($user_id) {
        $CI =& get_instance();
        $CI->load->model('Subscription_model');
        
        // Admin sempre pode
        if ($CI->session->userdata('role') === 'admin') {
            return true;
        }
        
        // Verificar se tem assinatura ativa
        $subscription = $CI->Subscription_model->get_active_by_user($user_id);
        
        return $subscription !== null;
    }
}

if (!function_exists('mensagem_bloqueio_imovel')) {
    /**
     * Retorna mensagem de bloqueio para gerenciamento de imóveis
     * 
     * @return string
     */
    function mensagem_bloqueio_imovel() {
        return 'Você precisa de um plano ativo para gerenciar imóveis. Escolha um plano para continuar.';
    }
}

if (!function_exists('formatar_tipo_imovel')) {
    /**
     * Formata o tipo de imóvel para exibição
     * 
     * @param string $tipo
     * @return string
     */
    function formatar_tipo_imovel($tipo) {
        $tipos = [
            'casa' => 'Casa',
            'apartamento' => 'Apartamento',
            'terreno' => 'Terreno',
            'comercial' => 'Comercial',
            'rural' => 'Rural',
            'galpao' => 'Galpão',
            'chacara' => 'Chácara',
            'sitio' => 'Sítio',
            'fazenda' => 'Fazenda',
            'cobertura' => 'Cobertura',
            'loft' => 'Loft',
            'flat' => 'Flat',
            'studio' => 'Studio'
        ];
        
        return isset($tipos[$tipo]) ? $tipos[$tipo] : ucfirst($tipo);
    }
}

if (!function_exists('formatar_tipo_negocio')) {
    /**
     * Formata o tipo de negócio para exibição
     * 
     * @param string $tipo
     * @return string
     */
    function formatar_tipo_negocio($tipo) {
        $tipos = [
            'compra' => 'Venda',
            'aluguel' => 'Aluguel',
            'temporada' => 'Temporada'
        ];
        
        return isset($tipos[$tipo]) ? $tipos[$tipo] : ucfirst($tipo);
    }
}

if (!function_exists('formatar_status_publicacao')) {
    /**
     * Formata o status de publicação para exibição
     * 
     * @param string $status
     * @return array ['texto' => string, 'classe' => string]
     */
    function formatar_status_publicacao($status) {
        $status_map = [
            'ativo' => ['texto' => 'Publicado', 'classe' => 'success'],
            'inativo_manual' => ['texto' => 'Desativado', 'classe' => 'secondary'],
            'inativo_sem_plano' => ['texto' => 'Sem Plano', 'classe' => 'warning'],
            'inativo_plano_vencido' => ['texto' => 'Plano Vencido', 'classe' => 'danger'],
            'inativo_por_tempo' => ['texto' => 'Expirado', 'classe' => 'warning'],
            'inativo_vendido' => ['texto' => 'Vendido', 'classe' => 'info'],
            'inativo_alugado' => ['texto' => 'Alugado', 'classe' => 'info']
        ];
        
        return isset($status_map[$status]) ? $status_map[$status] : ['texto' => 'Desconhecido', 'classe' => 'secondary'];
    }
}

if (!function_exists('calcular_valor_m2')) {
    /**
     * Calcula o valor por m²
     * 
     * @param float $preco
     * @param float $area
     * @return float
     */
    function calcular_valor_m2($preco, $area) {
        if ($area <= 0) {
            return 0;
        }
        
        return $preco / $area;
    }
}

if (!function_exists('formatar_preco')) {
    /**
     * Formata preço para exibição
     * 
     * @param float $preco
     * @return string
     */
    function formatar_preco($preco) {
        return 'R$ ' . number_format($preco, 2, ',', '.');
    }
}

if (!function_exists('formatar_area')) {
    /**
     * Formata área para exibição
     * 
     * @param float $area
     * @return string
     */
    function formatar_area($area) {
        return number_format($area, 2, ',', '.') . ' m²';
    }
}
