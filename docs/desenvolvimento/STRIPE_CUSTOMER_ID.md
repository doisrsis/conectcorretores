# 📘 Documentação: stripe_customer_id

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 02/11/2025

---

## 🎯 O Que É

`stripe_customer_id` é o **ID único do cliente no Stripe** (formato: `cus_XXXXXXXXXXXXX`).

Cada usuário do sistema pode ter um Customer correspondente no Stripe, que centraliza:
- ✅ Histórico de pagamentos
- ✅ Métodos de pagamento salvos
- ✅ Assinaturas ativas
- ✅ Faturas
- ✅ Dados de cobrança

---

## 📊 Estrutura no Banco

### **Tabela: `users`**
```sql
`stripe_customer_id` varchar(255) DEFAULT NULL COMMENT 'ID do cliente no Stripe'
```

**Características:**
- Tipo: `VARCHAR(255)`
- Permite `NULL` (usuário pode não ter Customer ainda)
- Único por usuário
- Formato: `cus_` + 18 caracteres alfanuméricos

---

## 🔄 Quando É Criado/Salvo

### **1. Durante o Checkout (Automático)**

**Arquivo:** `application/controllers/Planos.php`

#### **Método: `sucesso()` - Linha 266-269**
```php
// Atualizar stripe_customer_id do usuário
$this->User_model->update($user_id, [
    'stripe_customer_id' => $session->customer
]);
```

**Fluxo:**
```
1. Usuário clica em "Assinar Plano"
2. Stripe cria Checkout Session
3. Stripe cria Customer automaticamente (se não existir)
4. Usuário preenche dados do cartão
5. Pagamento aprovado
6. Stripe redireciona para /planos/sucesso
7. Sistema busca session_id
8. Session contém: session->customer (ID do Customer)
9. Sistema salva em users.stripe_customer_id
```

#### **Método: `_handle_checkout_completed()` - Linha 384-387**
```php
// Atualizar stripe_customer_id do usuário (via webhook)
$this->User_model->update($user_id, [
    'stripe_customer_id' => $stripe_customer_id
]);
```

**Fluxo (Webhook):**
```
1. Stripe envia webhook: checkout.session.completed
2. Webhook contém: session->customer
3. Sistema salva em users.stripe_customer_id
```

---

## 🎯 Para Que Serve

### **1. Gerenciar Assinaturas**

Quando o usuário tem `stripe_customer_id`, podemos:
- ✅ Criar novas assinaturas para o mesmo customer
- ✅ Atualizar método de pagamento
- ✅ Ver histórico de pagamentos
- ✅ Gerenciar múltiplas assinaturas

### **2. Customer Portal (Futuro)**

**Arquivo:** `application/libraries/Stripe_lib.php` - Linha 108-114

```php
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
```

**O que é Customer Portal:**
Portal self-service do Stripe onde o cliente pode:
- ✅ Atualizar cartão de crédito
- ✅ Ver faturas
- ✅ Baixar recibos
- ✅ Cancelar assinatura
- ✅ Alterar plano

**Status:** Método criado mas **não implementado** no front-end ainda.

### **3. Evitar Duplicação**

Sem `stripe_customer_id`:
```
Usuário assina Plano A → Stripe cria Customer 1
Usuário cancela
Usuário assina Plano B → Stripe cria Customer 2 ❌
```

Com `stripe_customer_id`:
```
Usuário assina Plano A → Stripe cria Customer 1 → Salva ID
Usuário cancela
Usuário assina Plano B → Usa Customer 1 existente ✅
```

### **4. Histórico Unificado**

Com o mesmo Customer ID:
- ✅ Todas as assinaturas do usuário ficam vinculadas
- ✅ Histórico de pagamentos completo
- ✅ Dados de cobrança centralizados
- ✅ Relatórios e analytics mais precisos

---

## 📋 Exemplo Real

### **Dados no Banco:**
```sql
SELECT id, nome, email, stripe_customer_id 
FROM users 
WHERE id = 1;
```

**Resultado:**
```
+----+---------------+-----------------------------+--------------------+
| id | nome          | email                       | stripe_customer_id |
+----+---------------+-----------------------------+--------------------+
|  1 | Administrador | admin@conectcorretores.com  | cus_TGeGQl2xssJufv |
+----+---------------+-----------------------------+--------------------+
```

### **No Stripe Dashboard:**
```
Customer ID: cus_TGeGQl2xssJufv
Email: admin@conectcorretores.com
Name: Administrador

Subscriptions:
  - sub_1SK38S13H0xINMprET3RgVbN (Plano Mensal - Ativa)

Payment Methods:
  - •••• 4242 (Visa)

Invoices:
  - in_1SK38S13H0xINMpr... (R$ 50,00 - Pago)
```

---

## 🔍 Verificação

### **Usuários com Customer ID:**
```sql
SELECT 
    id, 
    nome, 
    email, 
    stripe_customer_id,
    created_at
FROM users 
WHERE stripe_customer_id IS NOT NULL
ORDER BY created_at DESC;
```

### **Usuários sem Customer ID:**
```sql
SELECT 
    id, 
    nome, 
    email, 
    created_at
FROM users 
WHERE stripe_customer_id IS NULL
ORDER BY created_at DESC;
```

**Por que pode ser NULL:**
- Usuário cadastrado mas nunca assinou plano
- Cadastro em andamento
- Erro no processo de checkout

---

## 🚀 Uso Futuro (Implementações Planejadas)

### **1. Customer Portal**

**Adicionar botão no Dashboard:**
```php
// Em: application/controllers/Dashboard.php

public function customer_portal() {
    $user_id = $this->session->userdata('user_id');
    $user = $this->User_model->get_by_id($user_id);
    
    if (!$user->stripe_customer_id) {
        $this->session->set_flashdata('error', 'Você não possui assinaturas ativas.');
        redirect('dashboard');
        return;
    }
    
    $result = $this->stripe_lib->create_customer_portal(
        $user->stripe_customer_id,
        base_url('dashboard')
    );
    
    if ($result['success']) {
        redirect($result['url']);
    } else {
        $this->session->set_flashdata('error', 'Erro ao acessar portal: ' . $result['error']);
        redirect('dashboard');
    }
}
```

**Adicionar link na view:**
```html
<!-- Em: application/views/dashboard/index.php -->

<?php if ($subscription && $user->stripe_customer_id): ?>
    <a href="<?php echo base_url('dashboard/customer_portal'); ?>" 
       class="btn-secondary">
        <svg>...</svg>
        Gerenciar Assinatura
    </a>
<?php endif; ?>
```

### **2. Criar Customer Manualmente**

**Se usuário não tem Customer ID:**
```php
// Em: application/controllers/Planos.php

private function _ensure_customer($user) {
    if ($user->stripe_customer_id) {
        return $user->stripe_customer_id;
    }
    
    // Criar customer
    $result = $this->stripe_lib->create_customer([
        'email' => $user->email,
        'name' => $user->nome,
        'user_id' => $user->id
    ]);
    
    if ($result['success']) {
        // Salvar ID
        $this->User_model->update($user->id, [
            'stripe_customer_id' => $result['customer_id']
        ]);
        
        return $result['customer_id'];
    }
    
    return null;
}
```

### **3. Sincronizar Dados**

**Atualizar dados do Customer no Stripe:**
```php
public function sync_customer_data() {
    $user_id = $this->session->userdata('user_id');
    $user = $this->User_model->get_by_id($user_id);
    
    if (!$user->stripe_customer_id) {
        return;
    }
    
    try {
        \Stripe\Customer::update($user->stripe_customer_id, [
            'name' => $user->nome,
            'phone' => $user->telefone,
            'address' => [
                'line1' => $user->endereco
            ]
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Erro ao sincronizar customer: ' . $e->getMessage());
    }
}
```

---

## 📊 Relacionamento com Outras Tabelas

### **Tabela: `subscriptions`**

```sql
CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL,
  `stripe_customer_id` varchar(255) DEFAULT NULL,  -- Duplicado aqui
  ...
);
```

**Por que duplicar?**
- ✅ Facilita queries (não precisa JOIN com users)
- ✅ Histórico (se usuário mudar de customer)
- ✅ Performance (índice direto)

**Relação:**
```
users.stripe_customer_id → Stripe Customer
  └── subscriptions.stripe_customer_id → Mesma referência
      └── subscriptions.stripe_subscription_id → Stripe Subscription
```

---

## ⚠️ Importante

### **Não Deletar Customer no Stripe**

Se deletar Customer no Stripe:
- ❌ Perde histórico de pagamentos
- ❌ Perde faturas
- ❌ Assinaturas são canceladas
- ❌ `stripe_customer_id` fica inválido

**Solução:** Apenas desativar usuário no sistema, manter Customer no Stripe.

### **Não Compartilhar Customer**

Cada usuário deve ter seu próprio Customer:
- ❌ Não usar mesmo Customer para múltiplos usuários
- ✅ Um usuário = Um Customer

---

## 🔧 Troubleshooting

### **Problema: stripe_customer_id é NULL**

**Causas:**
1. Usuário nunca assinou plano
2. Erro no checkout
3. Webhook não foi processado

**Solução:**
```sql
-- Verificar se tem assinatura
SELECT * FROM subscriptions WHERE user_id = X;

-- Se tem assinatura mas não tem customer_id:
UPDATE users 
SET stripe_customer_id = (
    SELECT stripe_customer_id 
    FROM subscriptions 
    WHERE user_id = X 
    LIMIT 1
)
WHERE id = X;
```

### **Problema: Customer ID inválido no Stripe**

**Verificar:**
```
1. Acessar Stripe Dashboard
2. Buscar por customer ID
3. Se não existir, limpar campo:
```

```sql
UPDATE users 
SET stripe_customer_id = NULL 
WHERE id = X;
```

---

## 📝 Resumo

| Aspecto | Detalhes |
|---------|----------|
| **O que é** | ID único do cliente no Stripe |
| **Formato** | `cus_` + 18 caracteres |
| **Quando salva** | Após checkout bem-sucedido |
| **Onde salva** | `users.stripe_customer_id` |
| **Para que serve** | Gerenciar assinaturas, portal, histórico |
| **Pode ser NULL** | Sim (usuário sem assinatura) |
| **Implementado** | ✅ Salvar ID, ❌ Customer Portal |

---

**Documentação completa! 📚**

Para suporte: Rafael Dias - doisr.com.br
