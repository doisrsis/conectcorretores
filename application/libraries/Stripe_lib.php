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
}
