<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Stripe Library - ConectCorretores
 * 
 * Wrapper para facilitar o uso do Stripe SDK
 * 
 * Autor: Rafael Dias - doisr.com.br
 * Data: 19/10/2025
 */

class Stripe_lib {
    
    private $CI;
    private $stripe_secret_key;
    private $stripe_public_key;
    
    public function __construct() {
        $this->CI =& get_instance();
        
        // Carregar configurações
        $this->CI->config->load('stripe');
        
        // Obter chaves
        $this->stripe_secret_key = $this->CI->config->item('stripe_secret_key');
        $this->stripe_public_key = $this->CI->config->item('stripe_public_key');
        
        // Carregar SDK do Stripe
        require_once(APPPATH . 'libraries/stripe-php/init.php');
        
        // Configurar chave secreta
        \Stripe\Stripe::setApiKey($this->stripe_secret_key);
    }
    
    /**
     * Obter chave pública
     */
    public function get_public_key() {
        return $this->stripe_public_key;
    }
    
    /**
     * Criar sessão de checkout
     */
    public function create_checkout_session($stripe_price_id, $user_data) {
        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => $this->CI->config->item('stripe_payment_methods'),
                'line_items' => [[
                    'price' => $stripe_price_id,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $this->CI->config->item('stripe_success_url'),
                'cancel_url' => $this->CI->config->item('stripe_cancel_url'),
                'customer_email' => $user_data['email'],
                'client_reference_id' => $user_data['user_id'],
                'metadata' => [
                    'user_id' => $user_data['user_id'],
                    'plan_id' => $user_data['plan_id'] ?? null,  // ID do plano do banco
                ]
            ]);
            
            return ['success' => true, 'session_id' => $session->id];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Recuperar sessão de checkout
     */
    public function retrieve_session($session_id) {
        try {
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            return ['success' => true, 'session' => $session];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Criar cliente no Stripe
     */
    public function create_customer($user_data) {
        try {
            $customer = \Stripe\Customer::create([
                'email' => $user_data['email'],
                'name' => $user_data['name'],
                'metadata' => [
                    'user_id' => $user_data['user_id']
                ]
            ]);
            
            return ['success' => true, 'customer_id' => $customer->id];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Criar portal do cliente
     */
    public function create_customer_portal($customer_id, $return_url) {
        try {
            $session = \Stripe\BillingPortal\Session::create([
                'customer' => $customer_id,
                'return_url' => $return_url,
            ]);
            
            return ['success' => true, 'url' => $session->url];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Cancelar assinatura
     */
    public function cancel_subscription($subscription_id) {
        try {
            $subscription = \Stripe\Subscription::retrieve($subscription_id);
            $subscription->cancel();
            
            return ['success' => true];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Recuperar assinatura
     */
    public function retrieve_subscription($subscription_id) {
        try {
            $subscription = \Stripe\Subscription::retrieve($subscription_id);
            return ['success' => true, 'subscription' => $subscription];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Atualizar assinatura (upgrade/downgrade)
     * 
     * @param string $subscription_id ID da assinatura no Stripe
     * @param string $new_price_id ID do novo preço no Stripe
     * @return array Resultado da operação
     */
    public function update_subscription($subscription_id, $new_price_id) {
        try {
            $subscription = \Stripe\Subscription::retrieve($subscription_id);
            
            // Atualizar o item da assinatura com o novo preço
            \Stripe\Subscription::update($subscription_id, [
                'items' => [
                    [
                        'id' => $subscription->items->data[0]->id,
                        'price' => $new_price_id,
                    ],
                ],
                'proration_behavior' => 'always_invoice', // Calcula proporcional e cobra/credita imediatamente
            ]);
            
            return ['success' => true];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    // ========================================
    // GERENCIAMENTO DE PRODUTOS E PREÇOS
    // ========================================
    
    /**
     * Listar todos os produtos do Stripe
     */
    public function list_products($limit = 100) {
        try {
            $products = \Stripe\Product::all(['limit' => $limit, 'active' => true]);
            return ['success' => true, 'products' => $products->data];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Listar todos os preços do Stripe
     */
    public function list_prices($limit = 100) {
        try {
            $prices = \Stripe\Price::all(['limit' => $limit, 'active' => true]);
            return ['success' => true, 'prices' => $prices->data];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Criar produto no Stripe
     */
    public function create_product($name, $description = null) {
        try {
            $product = \Stripe\Product::create([
                'name' => $name,
                'description' => $description,
            ]);
            
            return ['success' => true, 'product' => $product];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Criar preço no Stripe
     */
    public function create_price($product_id, $amount, $currency = 'brl', $interval = 'month') {
        try {
            $price = \Stripe\Price::create([
                'product' => $product_id,
                'unit_amount' => $amount * 100, // Converter para centavos
                'currency' => $currency,
                'recurring' => ['interval' => $interval],
            ]);
            
            return ['success' => true, 'price' => $price];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Atualizar produto no Stripe
     */
    public function update_product($product_id, $data) {
        try {
            $product = \Stripe\Product::update($product_id, $data);
            return ['success' => true, 'product' => $product];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Desativar produto no Stripe
     */
    public function deactivate_product($product_id) {
        try {
            $product = \Stripe\Product::update($product_id, ['active' => false]);
            return ['success' => true, 'product' => $product];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Desativar preço no Stripe
     */
    public function deactivate_price($price_id) {
        try {
            $price = \Stripe\Price::update($price_id, ['active' => false]);
            return ['success' => true, 'price' => $price];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Buscar produto por ID
     */
    public function get_product($product_id) {
        try {
            $product = \Stripe\Product::retrieve($product_id);
            return ['success' => true, 'product' => $product];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Buscar preço por ID
     */
    public function get_price($price_id) {
        try {
            $price = \Stripe\Price::retrieve($price_id);
            return ['success' => true, 'price' => $price];
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
