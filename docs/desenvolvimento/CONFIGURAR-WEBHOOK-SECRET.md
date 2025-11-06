# 🔐 Configurar Webhook Secret do Stripe

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 06/11/2025  
**Prioridade:** 🔴 CRÍTICA - Segurança

---

## ⚠️ Por Que É Urgente?

Atualmente, o webhook secret está vazio:
```php
// application/config/stripe.php
$config['stripe_webhook_secret'] = ''; // ❌ VULNERABILIDADE
```

**Riscos:**
- ❌ Webhooks não são validados
- ❌ Qualquer pessoa pode enviar webhooks falsos
- ❌ Possível fraude e manipulação de dados
- ❌ Assinaturas podem ser ativadas sem pagamento

---

## 📋 Passo a Passo

### **1. Acessar Stripe Dashboard**

1. Acesse: https://dashboard.stripe.com/
2. Faça login com suas credenciais
3. **Importante:** Verifique se está no modo correto:
   - 🧪 **Test Mode** (para desenvolvimento)
   - 🔴 **Live Mode** (para produção)

---

### **2. Configurar Webhook**

#### **Passo 2.1: Acessar Webhooks**
```
Dashboard > Developers > Webhooks
ou
https://dashboard.stripe.com/webhooks
```

#### **Passo 2.2: Adicionar Endpoint**
1. Clicar em **"Add endpoint"** ou **"+ Adicionar endpoint"**
2. Preencher:

**Endpoint URL:**
```
https://seudominio.com.br/planos/webhook
```
⚠️ **Importante:** Substituir `seudominio.com.br` pelo domínio real

**Para desenvolvimento local (teste):**
```
http://localhost/conectcorretores/planos/webhook
```

#### **Passo 2.3: Selecionar Eventos**
Marcar os seguintes eventos:

✅ **checkout.session.completed**
- Quando checkout é finalizado

✅ **invoice.payment_succeeded**
- Quando pagamento é bem-sucedido (renovação)

✅ **invoice.payment_failed**
- Quando pagamento falha

✅ **customer.subscription.updated**
- Quando assinatura é atualizada

✅ **customer.subscription.deleted**
- Quando assinatura é cancelada

**Eventos adicionais recomendados:**
- ✅ customer.subscription.trial_will_end
- ✅ invoice.upcoming
- ✅ payment_method.attached
- ✅ payment_method.detached

#### **Passo 2.4: Salvar**
1. Clicar em **"Add endpoint"**
2. Aguardar confirmação

---

### **3. Copiar Signing Secret**

Após criar o endpoint:

1. Na lista de webhooks, clicar no endpoint criado
2. Procurar seção **"Signing secret"**
3. Clicar em **"Reveal"** ou **"Revelar"**
4. Copiar o secret (formato: `whsec_...`)

**Exemplo:**
```
whsec_1234567890abcdefghijklmnopqrstuvwxyz
```

---

### **4. Adicionar ao Config**

#### **Passo 4.1: Abrir arquivo de configuração**
```
application/config/stripe.php
```

#### **Passo 4.2: Adicionar o secret**

**Para Test Mode:**
```php
// Webhook Secret (Test Mode)
$config['stripe_webhook_secret_test'] = 'whsec_SEU_SECRET_DE_TESTE_AQUI';
```

**Para Live Mode:**
```php
// Webhook Secret (Live Mode)
$config['stripe_webhook_secret_live'] = 'whsec_SEU_SECRET_DE_PRODUCAO_AQUI';
```

#### **Passo 4.3: Configurar secret ativo**
```php
// Obter webhook secret ativo baseado no ambiente
$config['stripe_webhook_secret'] = $config['stripe_environment'] === 'live' 
    ? $config['stripe_webhook_secret_live'] 
    : $config['stripe_webhook_secret_test'];
```

---

### **5. Testar Webhook**

#### **Opção A: Teste pelo Stripe Dashboard**
1. No endpoint criado, clicar em **"Send test webhook"**
2. Selecionar evento: `checkout.session.completed`
3. Clicar em **"Send test webhook"**
4. Verificar resposta (deve ser 200 OK)

#### **Opção B: Teste Real**
1. Fazer um checkout de teste
2. Verificar logs do sistema
3. Confirmar que assinatura foi criada

---

## 📝 Arquivo Completo Atualizado

```php
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
$config['stripe_test_public_key'] = 'pk_test_51SJCoi13H0xINMprQRHLrcAp5BdTFoRkjw7gKeB2Lxf286tOP5xIRmgE98WLJ8SU1mkfAAFoYKqIPM1REhZIQ84h00uzyLxoIS';
$config['stripe_test_secret_key'] = 'sk_test_51SJCoi13H0xINMprSjxPVWOzBDPMk5sBw4sfyJ2u1IkFpPLETabFoH0KRq5gwi3vGYLUdtpvxf6t1Fncs0qLxNCI00X263uU6C';

// Chaves de Produção
$config['stripe_live_public_key'] = '';
$config['stripe_live_secret_key'] = '';

// Webhook Secrets
$config['stripe_webhook_secret_test'] = ''; // ⚠️ ADICIONAR AQUI
$config['stripe_webhook_secret_live'] = ''; // ⚠️ ADICIONAR AQUI

// Produto ID
$config['stripe_product_id'] = 'prod_TFjLkbDOwkbRWP';

// Obter chaves ativas baseado no ambiente
$config['stripe_public_key'] = $config['stripe_environment'] === 'live' 
    ? $config['stripe_live_public_key'] 
    : $config['stripe_test_public_key'];

$config['stripe_secret_key'] = $config['stripe_environment'] === 'live' 
    ? $config['stripe_live_secret_key'] 
    : $config['stripe_test_secret_key'];

$config['stripe_webhook_secret'] = $config['stripe_environment'] === 'live' 
    ? $config['stripe_webhook_secret_live'] 
    : $config['stripe_webhook_secret_test'];
```

---

## ✅ Checklist de Verificação

- [ ] Acessei Stripe Dashboard
- [ ] Criei endpoint de webhook
- [ ] Selecionei todos os eventos necessários
- [ ] Copiei o signing secret
- [ ] Adicionei secret no config/stripe.php
- [ ] Testei webhook pelo dashboard
- [ ] Webhook retorna 200 OK
- [ ] Sistema valida assinatura do webhook

---

## 🔍 Verificar se Está Funcionando

### **No código (application/controllers/Planos.php):**

O método `webhook()` já está preparado para validar:

```php
public function webhook() {
    $payload = @file_get_contents('php://input');
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

    $this->config->load('stripe');
    $webhook_secret = $this->config->item('stripe_webhook_secret');

    try {
        if ($webhook_secret) {
            // ✅ COM SECRET: Valida assinatura
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $webhook_secret);
        } else {
            // ❌ SEM SECRET: Aceita qualquer webhook (INSEGURO)
            $event = json_decode($payload);
        }
        
        // Processar evento...
    } catch (\Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
```

**Após configurar o secret:**
- ✅ Webhooks serão validados
- ✅ Webhooks falsos serão rejeitados
- ✅ Sistema estará seguro

---

## 🚨 Troubleshooting

### **Erro: "No signatures found matching the expected signature"**
**Causa:** Secret incorreto ou não configurado  
**Solução:** Verificar se copiou o secret corretamente

### **Erro: "Webhook signature verification failed"**
**Causa:** Secret de ambiente errado (test vs live)  
**Solução:** Verificar se `stripe_environment` está correto

### **Erro: 404 Not Found**
**Causa:** URL do webhook incorreta  
**Solução:** Verificar URL no Stripe Dashboard

### **Erro: 500 Internal Server Error**
**Causa:** Erro no código PHP  
**Solução:** Verificar logs do servidor

---

## 📚 Referências

- [Stripe Webhooks Documentation](https://stripe.com/docs/webhooks)
- [Webhook Signature Verification](https://stripe.com/docs/webhooks/signatures)
- [Testing Webhooks](https://stripe.com/docs/webhooks/test)

---

## ⏱️ Tempo Estimado

- **Configuração:** 10-15 minutos
- **Testes:** 5-10 minutos
- **Total:** 15-25 minutos

---

**Configuração crítica para segurança do sistema! 🔐**

Para suporte: Rafael Dias - doisr.com.br
