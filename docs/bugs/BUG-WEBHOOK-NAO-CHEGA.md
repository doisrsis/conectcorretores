# 🐛 Bug: Webhook do Stripe Não Chega ao Sistema

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 07/11/2025  
**Status:** ⏳ Pendente (Para resolver depois)

---

## 🐛 Descrição do Bug

Emails de **Assinatura Ativada** e **Pagamento Confirmado** não são enviados porque o webhook do Stripe não está chegando ao sistema.

---

## 📊 Situação Atual

### ✅ **Funcionando:**
- Código de webhook está correto
- Código de envio de email está correto
- SMTP configurado e funcionando
- Templates de email funcionando
- Teste manual funciona (test_webhook/checkout_completed)

### ❌ **NÃO Funcionando:**
- Webhook não chega do Stripe para o sistema
- Testado em localhost (com Stripe CLI)
- Testado em ambiente online
- Mesmo comportamento em ambos

---

## 🔍 Análise

### **Emails que Funcionam (Ações Diretas):**
```
✅ Boas-Vindas (cadastro)
✅ Upgrade Confirmado
✅ Downgrade Confirmado
✅ Cancelamento Confirmado
```

### **Emails que NÃO Funcionam (Webhooks):**
```
❌ Assinatura Ativada (checkout.session.completed)
❌ Pagamento Confirmado (invoice.payment_succeeded)
❌ Falha no Pagamento (invoice.payment_failed)
```

---

## 🧪 Testes Realizados

### **Teste 1: Manual (Sucesso)**
```
URL: /test_webhook/checkout_completed
Resultado: ✅ Email enviado com sucesso
Conclusão: Código está funcionando
```

### **Teste 2: Localhost com Stripe CLI**
```
Stripe CLI: Rodando
Webhook Secret: Configurado
Resultado: ❌ Eventos não chegam
```

### **Teste 3: Ambiente Online**
```
Webhook configurado no dashboard
Resultado: ❌ Eventos não chegam
```

---

## 🎯 Causa Provável

Possíveis causas a investigar:

1. **Configuração de Webhook no Stripe Dashboard**
   - URL pode estar incorreta
   - Eventos podem não estar selecionados
   - Webhook pode estar desabilitado

2. **Firewall/Servidor**
   - Servidor pode estar bloqueando requisições do Stripe
   - Porta pode estar fechada
   - SSL pode estar com problema

3. **Configuração do CodeIgniter**
   - CSRF pode estar bloqueando webhook
   - .htaccess pode estar interferindo
   - Roteamento pode estar incorreto

---

## 🔧 Soluções Temporárias

Enquanto o webhook não funciona:

### **Opção 1: Enviar Email na Página de Sucesso**

Modificar `/planos/sucesso` para enviar email:

```php
public function sucesso() {
    // ... código existente ...
    
    // Enviar email de assinatura ativada
    $user = $this->User_model->get_by_id($user_id);
    $subscription = $this->Subscription_model->get_active_by_user($user_id);
    $plan = $this->Plan_model->get_by_id($subscription->plan_id);
    
    if ($user && $subscription && $plan) {
        $this->email_lib->send_subscription_activated($user, $plan, $subscription);
    }
}
```

### **Opção 2: CRON Job**

Criar job que verifica novas assinaturas sem email enviado:

```php
// Verificar assinaturas criadas nas últimas 24h sem email
// Enviar email pendente
```

### **Opção 3: Fila de Emails**

Criar tabela de fila de emails:
- Adicionar email na fila quando assinatura é criada
- Processar fila periodicamente

---

## 📋 Checklist para Resolver Depois

- [ ] Verificar configuração de webhook no Stripe Dashboard
- [ ] Verificar URL do webhook
- [ ] Verificar eventos selecionados
- [ ] Testar webhook com Stripe CLI em ambiente limpo
- [ ] Verificar logs do servidor web
- [ ] Verificar firewall
- [ ] Verificar SSL
- [ ] Verificar CSRF do CodeIgniter
- [ ] Verificar .htaccess
- [ ] Testar com Postman/Insomnia
- [ ] Adicionar logs mais detalhados
- [ ] Verificar headers da requisição

---

## 🔗 Arquivos Relacionados

```
application/controllers/Planos.php (webhook())
application/controllers/Test_webhook.php (teste manual)
application/libraries/Email_lib.php
docs/desenvolvimento/PROBLEMA-WEBHOOK-IDENTIFICADO.md
```

---

## 💡 Notas

- O código está funcionando corretamente
- O problema é na comunicação Stripe → Sistema
- Não é problema de SMTP ou templates
- Não é problema de código PHP
- Provavelmente é configuração de infraestrutura

---

## 🚀 Próximos Passos (Quando Retomar)

1. Verificar dashboard do Stripe
2. Verificar logs do webhook no Stripe
3. Testar com ngrok ou similar
4. Verificar configurações do servidor
5. Implementar solução temporária se necessário

---

**Bug documentado para resolução futura.**

Para suporte: Rafael Dias - doisr.com.br
