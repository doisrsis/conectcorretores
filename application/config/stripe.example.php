<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Configurações Stripe - ConectCorretores
 *
 * Autor: Rafael Dias - doisr.com.br
 * Data: 19/10/2025
 * Atualizado: 06/11/2025
 */

// Ambiente (test ou live)
$config['stripe_environment'] = 'test';

// Chaves de Teste
$config['stripe_test_public_key'] = ''; // pk_test_xxxxx
$config['stripe_test_secret_key'] = ''; // sk_test_xxxxx

// Chaves de Produção (adicionar quando for ao ar)
$config['stripe_live_public_key'] = '';
$config['stripe_live_secret_key'] = '';

// Webhook Secrets
$config['stripe_webhook_secret_test'] = ''; // whsec_xxxxx
$config['stripe_webhook_secret_live'] = '';

// Produto ID (criado no Stripe)
$config['stripe_product_id'] = ''; // prod_xxxxx

// Obter chaves ativas baseado no ambiente
$config['stripe_public_key'] = $config['stripe_environment'] === 'live'
    ? $config['stripe_live_public_key']
    : $config['stripe_test_public_key'];

$config['stripe_secret_key'] = $config['stripe_environment'] === 'live'
    ? $config['stripe_live_secret_key']
    : $config['stripe_test_secret_key'];

// Obter webhook secret ativo baseado no ambiente
$config['stripe_webhook_secret'] = $config['stripe_environment'] === 'live'
    ? $config['stripe_webhook_secret_live']
    : $config['stripe_webhook_secret_test'];

// URLs de retorno
$config['stripe_success_url'] = base_url('planos/sucesso?session_id={CHECKOUT_SESSION_ID}');
$config['stripe_cancel_url'] = base_url('planos/cancelado');

// Moeda
$config['stripe_currency'] = 'brl';

// Métodos de pagamento aceitos
$config['stripe_payment_methods'] = ['card', 'boleto']; // PIX será adicionado quando disponível no Brasil
