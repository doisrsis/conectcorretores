# 🧪 Testar Webhooks e Emails

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 06/11/2025

---

## 📊 Status Atual

### ✅ **Emails Funcionando (Ações Diretas):**
- Boas-Vindas (cadastro)
- Upgrade Confirmado
- Downgrade Confirmado
- Cancelamento Confirmado

### ❌ **Emails NÃO Funcionando (Webhooks):**
- Assinatura Ativada (checkout.session.completed)
- Pagamento Confirmado (invoice.payment_succeeded)

---

## 🎯 Objetivo

Identificar por que os emails de webhook não estão sendo enviados.

---

## 🔍 Teste Passo a Passo

### **PRÉ-REQUISITOS:**

1. ✅ Logs habilitados (`log_threshold = 4`)
2. ✅ Stripe CLI rodando
3. ✅ Webhook secret configurado

---

### **TESTE 1: Verificar Se Webhook Chega**

#### **Passo 1: Limpar Logs Antigos**

Delete o arquivo:
```
application/logs/log-2025-11-06.php
```

#### **Passo 2: Fazer Assinatura**

1. Acessar: http://localhost/conectcorretores/planos
2. Escolher plano
3. Clicar "Assinar Agora"
4. Completar checkout (4242 4242 4242 4242)

#### **Passo 3: Verificar Stripe CLI**

No terminal do Stripe CLI, deve aparecer:
```
2025-11-06 19:40:00   --> checkout.session.completed [evt_xxxxx]
2025-11-06 19:40:01   <-- [200] POST http://localhost/conectcorretores/planos/webhook
```

**✅ Se aparecer [200]:** Webhook chegou!  
**❌ Se aparecer [400]:** Erro no processamento!  
**❌ Se não aparecer nada:** Webhook não foi enviado!

#### **Passo 4: Verificar Logs**

Abrir: `application/logs/log-2025-11-06.php`

**Procurar por:**
```
========== WEBHOOK RECEBIDO ==========
```

**Se encontrar:** Webhook chegou ao sistema ✅  
**Se NÃO encontrar:** Webhook não chegou ❌

---

### **TESTE 2: Verificar Se Email Foi Tentado**

No mesmo arquivo de log, procurar por:
```
--- Tentando enviar email de assinatura ativada ---
```

**Deve mostrar:**
```
INFO --> Usuário encontrado: email@teste.com
INFO --> Assinatura encontrada: SIM
INFO --> Chamando email_lib->send_subscription_activated()
```

**Se encontrar:** Sistema tentou enviar ✅  
**Se NÃO encontrar:** Sistema não tentou enviar ❌

---

### **TESTE 3: Verificar Se Email Foi Enviado**

Procurar por:
```
=== ENVIANDO EMAIL ===
```

**Deve mostrar:**
```
INFO --> === ENVIANDO EMAIL ===
INFO --> Para: email@teste.com
INFO --> Assunto: Sua assinatura foi ativada!
INFO --> Template: subscription_activated
INFO --> Template renderizado com sucesso
INFO --> Tentando enviar email...
INFO --> Email enviado com SUCESSO!
INFO --> ======================
```

**Se mostrar "SUCESSO":** Email foi enviado ✅  
**Se mostrar "FALHA":** Email falhou ❌  
**Se não aparecer:** Email não foi tentado ❌

---

## 🐛 Cenários de Erro

### **Cenário 1: Webhook Não Chega**

**Sintomas:**
- Stripe CLI não mostra nada
- Log não tem "WEBHOOK RECEBIDO"

**Causa:** Stripe CLI não está rodando ou URL incorreta

**Solução:**
```bash
stripe listen --forward-to http://localhost/conectcorretores/planos/webhook
```

---

### **Cenário 2: Webhook Chega Mas Falha [400]**

**Sintomas:**
- Stripe CLI mostra [400]
- Log tem "ERRO no webhook"

**Causa:** Erro no processamento (dados faltando, etc.)

**Solução:**
1. Ver mensagem de erro no log
2. Verificar se plan_id existe no metadata
3. Verificar se usuário existe

---

### **Cenário 3: Webhook OK Mas Email Não Tenta**

**Sintomas:**
- Stripe CLI mostra [200]
- Log tem "WEBHOOK RECEBIDO"
- Log NÃO tem "Tentando enviar email"

**Causa:** Usuário ou assinatura não encontrados

**Solução:**
1. Verificar se user_id está correto
2. Verificar se assinatura foi criada no banco
3. Ver log para mensagem de erro específica

---

### **Cenário 4: Email Tentado Mas Falha**

**Sintomas:**
- Log tem "Tentando enviar email"
- Log tem "FALHA ao enviar email"

**Causa:** Erro no SMTP

**Solução:**
1. Ver debug do email no log
2. Verificar credenciais SMTP
3. Testar email manualmente:
   ```
   http://localhost/conectcorretores/test_email/subscription_activated
   ```

---

### **Cenário 5: Email Enviado Mas Não Chega**

**Sintomas:**
- Log tem "Email enviado com SUCESSO"
- Email não chegou na caixa

**Causa:** Email caiu em spam ou problema no servidor SMTP

**Solução:**
1. Verificar pasta de spam
2. Verificar se email está correto
3. Testar com outro email
4. Verificar logs do Gmail

---

## 🧪 Teste Manual de Webhook

Para simular webhook sem fazer checkout real:

```bash
stripe trigger checkout.session.completed
```

Isso vai:
1. Criar evento fake no Stripe
2. Enviar para seu webhook local
3. Processar como se fosse real

**⚠️ ATENÇÃO:** Dados serão fake, pode dar erro se IDs não existirem.

---

## 📋 Checklist de Debug

Marque cada item conforme testa:

- [ ] Logs habilitados
- [ ] Stripe CLI rodando
- [ ] Webhook secret configurado
- [ ] Checkout completado
- [ ] Stripe CLI mostra evento
- [ ] Stripe CLI mostra [200]
- [ ] Log criado
- [ ] Log tem "WEBHOOK RECEBIDO"
- [ ] Log tem "Tentando enviar email"
- [ ] Log tem "ENVIANDO EMAIL"
- [ ] Log tem "Email enviado com SUCESSO"
- [ ] Email chegou na caixa

---

## 🔧 Comandos Úteis

### **Ver log em tempo real:**
```powershell
Get-Content application/logs/log-2025-11-06.php -Wait -Tail 50
```

### **Buscar por webhook:**
```powershell
Select-String -Path application/logs/log-2025-11-06.php -Pattern "WEBHOOK"
```

### **Buscar por email:**
```powershell
Select-String -Path application/logs/log-2025-11-06.php -Pattern "ENVIANDO EMAIL"
```

### **Ver últimas 100 linhas:**
```powershell
Get-Content application/logs/log-2025-11-06.php -Tail 100
```

---

## 📊 Exemplo de Log Completo (Sucesso)

```
INFO --> ========== WEBHOOK RECEBIDO ==========
INFO --> Webhook Secret configurado: SIM
INFO --> Evento recebido: checkout.session.completed
INFO --> Processando checkout.session.completed
INFO --> --- Iniciando _handle_checkout_completed ---
INFO --> User ID: 123
INFO --> Subscription ID: sub_xxxxx
INFO --> Customer ID: cus_xxxxx
INFO --> Plan ID do metadata: 1
INFO --> Plano encontrado: Profissional
INFO --> Imóveis reativados
INFO --> --- Tentando enviar email de assinatura ativada ---
INFO --> Usuário encontrado: usuario@email.com
INFO --> Assinatura encontrada: SIM
INFO --> Chamando email_lib->send_subscription_activated()
INFO --> === ENVIANDO EMAIL ===
INFO --> Para: usuario@email.com
INFO --> Assunto: Sua assinatura foi ativada!
INFO --> Template: subscription_activated
INFO --> Template renderizado com sucesso
INFO --> Tentando enviar email...
INFO --> Email enviado com SUCESSO!
INFO --> ======================
INFO --> Email enviado: SUCESSO
INFO --> --- Fim _handle_checkout_completed ---
INFO --> Webhook processado com sucesso
INFO --> ========================================
```

---

## 💡 Próximos Passos

Após identificar o problema:

1. **Se webhook não chega:** Corrigir Stripe CLI
2. **Se email não tenta:** Corrigir lógica do webhook
3. **Se email falha:** Corrigir SMTP
4. **Se email enviado mas não chega:** Verificar spam/servidor

---

**Debug sistemático = Problema encontrado! 🔍**

Para suporte: Rafael Dias - doisr.com.br
