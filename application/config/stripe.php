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

// Chaves de Teste (Atualizadas em 06/11/2025)
$config['stripe_test_public_key'] = 'pk_test_51SJCoQ0CRJ9ato0iKBZpI1TvHki2u77ummHWrcIzMRiF053B7QQH1iw5DH4QUqj9W3vFDSeb32mwaCCTp4F38Sq500n2LNAOG9';
$config['stripe_test_secret_key'] = 'sk_test_51SJCoQ0CRJ9ato0ia7ro8uQjZXc2KRUUldCCRpdCD2FLY8xTTr5fxKgB7aYNwMfsFlzHL1mGWzFP8WJYjrL2s0C400EaCiBAQQ';

// Chaves de Produção (adicionar quando for ao ar)
$config['stripe_live_public_key'] = '';
$config['stripe_live_secret_key'] = '';

// Webhook Secrets
$config['stripe_webhook_secret_test'] = 'whsec_ec8c20dc8d6e6f313cdfcb6f2ea7247cecb81223b7d5f0b5d76be54620cbd26d'; // ⚠️ CONFIGURAR: Obter do Stripe Dashboard > Webhooks
$config['stripe_webhook_secret_live'] = ''; // ⚠️ CONFIGURAR: Obter do Stripe Dashboard > Webhooks (Live Mode)

// Produto ID (criado no Stripe)
$config['stripe_product_id'] = 'prod_TFjLkbDOwkbRWP';

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
