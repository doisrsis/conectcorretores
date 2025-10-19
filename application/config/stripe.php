<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Configurações Stripe - ConectCorretores
 * 
 * Autor: Rafael Dias - doisr.com.br
 * Data: 19/10/2025
 */

// Ambiente (test ou live)
$config['stripe_environment'] = 'test';

// Chaves de Teste
$config['stripe_test_public_key'] = 'pk_test_51SJCoi13H0xINMprQRHLrcAp5BdTFoRkjw7gKeB2Lxf286tOP5xIRmgE98WLJ8SU1mkfAAFoYKqIPM1REhZIQ84h00uzyLxoIS';
$config['stripe_test_secret_key'] = 'sk_test_51SJCoi13H0xINMprSjxPVWOzBDPMk5sBw4sfyJ2u1IkFpPLETabFoH0KRq5gwi3vGYLUdtpvxf6t1Fncs0qLxNCI00X263uU6C';

// Chaves de Produção (adicionar quando for ao ar)
$config['stripe_live_public_key'] = '';
$config['stripe_live_secret_key'] = '';

// Webhook Secret (será gerado depois)
$config['stripe_webhook_secret'] = '';

// Produto ID (criado no Stripe)
$config['stripe_product_id'] = 'prod_TFjLkbDOwkbRWP';

// Obter chaves ativas baseado no ambiente
$config['stripe_public_key'] = $config['stripe_environment'] === 'live' 
    ? $config['stripe_live_public_key'] 
    : $config['stripe_test_public_key'];

$config['stripe_secret_key'] = $config['stripe_environment'] === 'live' 
    ? $config['stripe_live_secret_key'] 
    : $config['stripe_test_secret_key'];

// URLs de retorno
$config['stripe_success_url'] = base_url('planos/sucesso?session_id={CHECKOUT_SESSION_ID}');
$config['stripe_cancel_url'] = base_url('planos/cancelado');

// Moeda
$config['stripe_currency'] = 'brl';

// Métodos de pagamento aceitos
$config['stripe_payment_methods'] = ['card', 'boleto']; // PIX será adicionado quando disponível no Brasil
