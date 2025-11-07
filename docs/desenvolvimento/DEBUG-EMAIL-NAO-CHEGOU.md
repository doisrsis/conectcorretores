# 🔍 Debug: Email Não Chegou Após Assinatura

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 06/11/2025

---

## 🐛 Problema

Email de "Assinatura Ativada" não está chegando após completar o checkout.

---

## 🔍 Diagnóstico

### **Possíveis Causas:**

1. ❌ **Stripe CLI não está escutando**
   - Webhooks não chegam ao sistema
   
2. ❌ **Logs não estão habilitados**
   - Não conseguimos ver o que está acontecendo
   
3. ❌ **Webhook secret incorreto**
   - Stripe rejeita o webhook
   
4. ❌ **Email não está sendo enviado**
   - Erro no SMTP ou na biblioteca

---

## ✅ Solução Passo a Passo

### **PASSO 1: Habilitar Logs**

**Editar:** `application/config/config.php`

**Procurar por:** `log_threshold`

**Alterar para:**
```php
$config['log_threshold'] = 4; // 0=Off, 1=Error, 2=Debug, 3=Info, 4=All
```

**Salvar o arquivo.**

---

### **PASSO 2: Iniciar Stripe CLI**

#### **Opção A: Usar Script (Recomendado)**

Execute:
```
scripts\windows\iniciar-stripe-cli.bat
```

#### **Opção B: Manualmente**

Abrir PowerShell/CMD e executar:

```bash
stripe listen --forward-to http://localhost/conectcorretores/planos/webhook
```

**⚠️ IMPORTANTE:** Copie o **webhook signing secret** que aparece!

Exemplo:
```
> Ready! Your webhook signing secret is whsec_xxxxxxxxxxxxx
```

---

### **PASSO 3: Configurar Webhook Secret**

**Editar:** `application/config/stripe.php`

**Procurar por:** `stripe_webhook_secret_test`

**Colar o secret copiado:**
```php
$config['stripe_webhook_secret_test'] = 'whsec_xxxxxxxxxxxxx';
```

**Salvar o arquivo.**

---

### **PASSO 4: Testar Assinatura**

1. **Acessar:** http://localhost/conectcorretores/planos
2. **Escolher um plano**
3. **Clicar:** "Assinar Agora"
4. **Completar checkout** com cartão de teste:
   ```
   4242 4242 4242 4242
   Data: 12/25
   CVC: 123
   ```
5. **Aguardar redirecionamento**

---

### **PASSO 5: Verificar Stripe CLI**

No terminal do Stripe CLI, você deve ver:

```
2025-11-06 19:10:00   --> checkout.session.completed [evt_xxxxx]
2025-11-06 19:10:01   <-- [200] POST http://localhost/conectcorretores/planos/webhook [evt_xxxxx]
```

**✅ Se aparecer [200]:** Webhook foi recebido com sucesso!  
**❌ Se aparecer [400] ou [500]:** Houve erro no processamento!

---

### **PASSO 6: Verificar Logs do Sistema**

**Abrir:** `application/logs/log-2025-11-06.php`

**Procurar por:**
```
========== WEBHOOK RECEBIDO ==========
```

**Você deve ver:**
```
INFO - 2025-11-06 19:10:01 --> ========== WEBHOOK RECEBIDO ==========
INFO - 2025-11-06 19:10:01 --> Webhook Secret configurado: SIM
INFO - 2025-11-06 19:10:01 --> Evento recebido: checkout.session.completed
INFO - 2025-11-06 19:10:01 --> Processando checkout.session.completed
INFO - 2025-11-06 19:10:01 --> --- Iniciando _handle_checkout_completed ---
INFO - 2025-11-06 19:10:01 --> User ID: 123
INFO - 2025-11-06 19:10:01 --> Subscription ID: sub_xxxxx
INFO - 2025-11-06 19:10:01 --> Customer ID: cus_xxxxx
INFO - 2025-11-06 19:10:01 --> Plan ID do metadata: 1
INFO - 2025-11-06 19:10:01 --> Plano encontrado: Profissional
INFO - 2025-11-06 19:10:01 --> Imóveis reativados
INFO - 2025-11-06 19:10:01 --> --- Tentando enviar email de assinatura ativada ---
INFO - 2025-11-06 19:10:01 --> Usuário encontrado: usuario@email.com
INFO - 2025-11-06 19:10:01 --> Assinatura encontrada: SIM
INFO - 2025-11-06 19:10:01 --> Chamando email_lib->send_subscription_activated()
INFO - 2025-11-06 19:10:02 --> Email enviado: SUCESSO
INFO - 2025-11-06 19:10:02 --> --- Fim _handle_checkout_completed ---
INFO - 2025-11-06 19:10:02 --> Webhook processado com sucesso
INFO - 2025-11-06 19:10:02 --> ========================================
```

---

## 🔍 Análise dos Logs

### **Cenário 1: Webhook Não Chegou**

**Log vazio ou sem "WEBHOOK RECEBIDO"**

**Causa:** Stripe CLI não está rodando ou URL incorreta

**Solução:**
1. Verificar se Stripe CLI está rodando
2. Verificar URL: `http://localhost/conectcorretores/planos/webhook`
3. Reiniciar Stripe CLI

---

### **Cenário 2: Webhook Chegou Mas Falhou**

**Log mostra erro:**
```
ERROR - 2025-11-06 19:10:01 --> ERRO no webhook: ...
```

**Causa:** Erro no processamento (dados faltando, banco de dados, etc.)

**Solução:**
1. Ler mensagem de erro completa
2. Verificar se plano existe
3. Verificar se usuário existe

---

### **Cenário 3: Email Não Foi Enviado**

**Log mostra:**
```
Email enviado: FALHA
```

**Causa:** Erro no SMTP ou biblioteca de email

**Solução:**
1. Verificar `application/config/email.php`
2. Testar email manualmente: `http://localhost/conectcorretores/test_email/subscription_activated`
3. Verificar credenciais SMTP

---

### **Cenário 4: Email Enviado Mas Não Chegou**

**Log mostra:**
```
Email enviado: SUCESSO
```

**Mas email não chegou**

**Causa:** Email caiu em spam ou SMTP não enviou

**Solução:**
1. Verificar pasta de spam
2. Verificar se email está correto
3. Testar com outro email
4. Verificar logs do Gmail (se usando Gmail)

---

## 🧪 Teste Manual de Email

Para testar se o email funciona isoladamente:

```
http://localhost/conectcorretores/test_email/subscription_activated
```

Se este teste funcionar, o problema está no webhook.  
Se este teste falhar, o problema está no SMTP.

---

## 📊 Checklist de Verificação

- [ ] Logs habilitados (`log_threshold = 4`)
- [ ] Stripe CLI rodando
- [ ] Webhook secret configurado
- [ ] Checkout completado
- [ ] Stripe CLI mostra [200]
- [ ] Log do sistema criado
- [ ] Log mostra "WEBHOOK RECEBIDO"
- [ ] Log mostra "Email enviado: SUCESSO"
- [ ] Email chegou na caixa de entrada

---

## 🔧 Comandos Úteis

### **Ver últimas linhas do log:**
```powershell
Get-Content application/logs/log-2025-11-06.php -Tail 100
```

### **Buscar por erro:**
```powershell
Select-String -Path application/logs/log-2025-11-06.php -Pattern "ERROR"
```

### **Buscar por webhook:**
```powershell
Select-String -Path application/logs/log-2025-11-06.php -Pattern "WEBHOOK"
```

### **Buscar por email:**
```powershell
Select-String -Path application/logs/log-2025-11-06.php -Pattern "Email enviado"
```

---

## 💡 Dicas

1. **Sempre deixe Stripe CLI rodando** durante desenvolvimento
2. **Verifique logs após cada teste** para identificar problemas
3. **Teste email manualmente** antes de testar via webhook
4. **Use cartão de teste** do Stripe para evitar cobranças reais

---

## 📚 Referências

- [Stripe CLI Docs](https://stripe.com/docs/stripe-cli)
- [Stripe Webhooks](https://stripe.com/docs/webhooks)
- [CodeIgniter Logging](https://codeigniter.com/userguide3/general/errors.html)

---

**Debug sistemático = Problema resolvido! 🔍**

Para suporte: Rafael Dias - doisr.com.br
