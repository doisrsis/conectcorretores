🧾 Visão Geral da Integração

Você utilizará o Stripe Checkout para gerenciar assinaturas recorrentes. O fluxo básico é:

Criação de uma Checkout Session: Define os planos de assinatura e os métodos de pagamento aceitos.

Redirecionamento do Cliente: O cliente é redirecionado para o Checkout do Stripe.

Confirmação do Pagamento: Após o pagamento, o cliente é redirecionado de volta ao seu sistema.

🛠️ Passo a Passo da Integração
1. Criação de uma Checkout Session

No backend (PHP), crie uma sessão de checkout com os planos disponíveis e os métodos de pagamento aceitos:

\Stripe\Stripe::setApiKey('sua_chave_secreta');

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card', 'pix'],
    'line_items' => [
        [
            'price_data' => [
                'currency' => 'brl',
                'product_data' => [
                    'name' => 'Plano Mensal',
                ],
                'unit_amount' => 10000, // R$100,00
            ],
            'quantity' => 1,
        ],
    ],
    'mode' => 'subscription',
    'success_url' => 'https://seusite.com/sucesso?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'https://seusite.com/cancelado',
]);

echo json_encode(['id' => $session->id]);


Nota: Certifique-se de que o método de pagamento Pix esteja habilitado no seu painel do Stripe.

2. Redirecionamento do Cliente

No frontend, após receber o session_id, redirecione o cliente para o Stripe Checkout:

const stripe = Stripe('sua_chave_publica');
fetch('/criar_checkout_session', {
    method: 'POST',
})
.then(response => response.json())
.then(sessionId => {
    stripe.redirectToCheckout({ sessionId: sessionId.id });
});

3. Confirmação do Pagamento

Após o pagamento, o Stripe redirecionará o cliente para a URL de sucesso fornecida. Nessa página, você pode recuperar os detalhes da sessão para verificar o status do pagamento:

$session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
if ($session->payment_status == 'paid') {
    // Ative a assinatura do usuário
}

🧾 Gerenciamento de Assinaturas
1. Criação de um Cliente

Antes de criar uma sessão de checkout, crie um cliente no Stripe:

$customer = \Stripe\Customer::create([
    'email' => 'cliente@exemplo.com',
    'name' => 'Nome do Cliente',
]);

2. Criação de uma Assinatura

Após o pagamento, crie uma assinatura para o cliente:

$subscription = \Stripe\Subscription::create([
    'customer' => $customer->id,
    'items' => [['price' => 'id_do_preco']],
    'expand' => ['latest_invoice.payment_intent'],
]);

3. Atualização de Método de Pagamento

Para permitir que o cliente altere seu método de pagamento, forneça um link para o portal do cliente:

$session = \Stripe\BillingPortal\Session::create([
    'customer' => $customer->id,
    'return_url' => 'https://seusite.com/painel',
]);


Redirecione o cliente para a URL fornecida em $session->url.

📦 Recursos Úteis

Documentação do Stripe Checkout

Métodos de Pagamento Suportados

Integração com Pix
