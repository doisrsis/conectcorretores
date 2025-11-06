# 🔧 Configurar Webhook com Stripe CLI

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 06/11/2025  
**Prioridade:** 🔴 CRÍTICA

---

## 🎯 Objetivo

Configurar webhook do Stripe para receber eventos em tempo real durante desenvolvimento local.

---

## 📋 Pré-requisitos

- ✅ XAMPP rodando
- ✅ Projeto ConectCorretores funcionando
- ✅ Chaves do Stripe atualizadas (✓ Feito!)
- ⏳ Stripe CLI instalada

---

## 🚀 Passo a Passo

### **1. Baixar e Instalar Stripe CLI**

#### **Windows:**

**Opção A: Via Scoop (Recomendado)**
```powershell
# Instalar Scoop (se não tiver)
iwr -useb get.scoop.sh | iex

# Instalar Stripe CLI
scoop bucket add stripe https://github.com/stripe/scoop-stripe-cli.git
scoop install stripe
```

**Opção B: Download Direto**
1. Acessar: https://github.com/stripe/stripe-cli/releases/latest
2. Baixar: `stripe_X.X.X_windows_x86_64.zip`
3. Extrair para: `C:\stripe-cli\`
4. Adicionar ao PATH:
   - Painel de Controle > Sistema > Configurações Avançadas
   - Variáveis de Ambiente
   - PATH > Editar > Novo: `C:\stripe-cli`

**Verificar instalação:**
```bash
stripe --version
```

---

### **2. Fazer Login no Stripe**

```bash
stripe login
```

**O que vai acontecer:**
1. Abrirá navegador automaticamente
2. Pedirá para autorizar acesso
3. Clicar em "Allow access"
4. Voltar ao terminal

**Confirmação:**
```
Done! The Stripe CLI is configured for [sua-conta] with account id acct_xxxxx
```

---

### **3. Configurar Endpoint do Webhook**

#### **URL do seu projeto:**
```
http://localhost/conectcorretores/planos/webhook
```

**Importante:** Ajustar se seu projeto estiver em outra pasta.

---

### **4. Iniciar Listener (Desenvolvimento)**

```bash
stripe listen --forward-to localhost/conectcorretores/planos/webhook
```

**Saída esperada:**
```
> Ready! Your webhook signing secret is whsec_xxxxxxxxxxxxxx (^C to quit)
```

**⚠️ IMPORTANTE:** Copie o `whsec_xxxxxxxxxxxxxx` que apareceu!

---

### **5. Adicionar Webhook Secret ao Config**

#### **Abrir arquivo:**
```
application/config/stripe.php
```

#### **Adicionar o secret:**
```php
// Webhook Secrets
$config['stripe_webhook_secret_test'] = 'whsec_SEU_SECRET_AQUI'; // ✅ COLAR AQUI
$config['stripe_webhook_secret_live'] = ''; // Para produção
```

**Exemplo:**
```php
$config['stripe_webhook_secret_test'] = 'whsec_1234567890abcdefghijklmnopqrstuvwxyz';
```

---

### **6. Testar Webhook**

#### **Em outro terminal (manter o listener rodando):**

```bash
# Testar evento de checkout completado
stripe trigger checkout.session.completed
```

**Saída esperada no listener:**
```
2025-11-06 16:30:00   --> checkout.session.completed [evt_xxxxx]
2025-11-06 16:30:01   <-- [200] POST http://localhost/conectcorretores/planos/webhook [evt_xxxxx]
```

✅ **200** = Sucesso!  
❌ **400/500** = Erro (verificar logs)

---

### **7. Testar Outros Eventos**

```bash
# Pagamento bem-sucedido
stripe trigger invoice.payment_succeeded

# Falha no pagamento
stripe trigger invoice.payment_failed

# Assinatura atualizada
stripe trigger customer.subscription.updated

# Assinatura cancelada
stripe trigger customer.subscription.deleted
```

---

## 🔄 Workflow de Desenvolvimento

### **Sempre que for desenvolver:**

1. **Iniciar XAMPP**
2. **Iniciar Stripe Listener**
   ```bash
   stripe listen --forward-to localhost/conectcorretores/planos/webhook
   ```
3. **Desenvolver normalmente**
4. **Testar webhooks quando necessário**

---

## 📝 Criar Webhook Permanente (Produção)

Quando for para produção, criar webhook permanente:

### **Via Stripe Dashboard:**

1. Acessar: https://dashboard.stripe.com/webhooks
2. Clicar em **"Add endpoint"**
3. **Endpoint URL:**
   ```
   https://seudominio.com.br/planos/webhook
   ```
4. **Eventos a selecionar:**
   - ✅ checkout.session.completed
   - ✅ invoice.payment_succeeded
   - ✅ invoice.payment_failed
   - ✅ customer.subscription.updated
   - ✅ customer.subscription.deleted
   - ✅ customer.subscription.trial_will_end (opcional)
   - ✅ invoice.upcoming (opcional)

5. **Copiar Signing Secret**
6. **Adicionar ao config:**
   ```php
   $config['stripe_webhook_secret_live'] = 'whsec_xxxxx';
   ```

---

## 🔍 Verificar se Está Funcionando

### **1. Verificar Logs do Sistema**

```php
// application/controllers/Planos.php
// Adicionar temporariamente no método webhook():

log_message('info', 'Webhook recebido: ' . $event->type);
```

**Ver logs:**
```
application/logs/log-2025-11-06.php
```

---

### **2. Fazer Checkout de Teste**

1. Acessar: `/planos`
2. Escolher um plano
3. Usar cartão de teste:
   ```
   Número: 4242 4242 4242 4242
   Data: 12/34
   CVC: 123
   ```
4. Verificar se assinatura foi criada
5. Verificar logs do listener

---

## 🚨 Troubleshooting

### **Erro: "stripe: command not found"**
**Causa:** Stripe CLI não instalada ou não está no PATH  
**Solução:** Reinstalar e adicionar ao PATH

### **Erro: "Failed to connect to localhost"**
**Causa:** XAMPP não está rodando ou URL incorreta  
**Solução:** Verificar XAMPP e URL do projeto

### **Erro: "Webhook signature verification failed"**
**Causa:** Secret incorreto ou não configurado  
**Solução:** Verificar se copiou o secret corretamente

### **Erro: "No signatures found"**
**Causa:** Webhook secret vazio no config  
**Solução:** Adicionar secret ao config/stripe.php

### **Listener não recebe eventos**
**Causa:** Listener não está rodando  
**Solução:** Iniciar `stripe listen` novamente

---

## 📊 Eventos Disponíveis

### **Eventos Principais:**
```
checkout.session.completed      - Checkout finalizado
invoice.payment_succeeded       - Pagamento bem-sucedido
invoice.payment_failed          - Falha no pagamento
customer.subscription.created   - Assinatura criada
customer.subscription.updated   - Assinatura atualizada
customer.subscription.deleted   - Assinatura cancelada
```

### **Eventos Adicionais:**
```
customer.subscription.trial_will_end  - Trial vai expirar
invoice.upcoming                      - Fatura próxima (7 dias antes)
payment_method.attached               - Método de pagamento adicionado
payment_method.detached               - Método de pagamento removido
customer.updated                      - Cliente atualizado
```

---

## 💡 Dicas

### **1. Manter Listener Rodando**
Use um terminal dedicado para o listener durante desenvolvimento.

### **2. Logs Detalhados**
```bash
stripe listen --forward-to localhost/conectcorretores/planos/webhook --print-json
```

### **3. Filtrar Eventos**
```bash
stripe listen --events checkout.session.completed,invoice.payment_succeeded --forward-to localhost/conectcorretores/planos/webhook
```

### **4. Ver Eventos Recentes**
```bash
stripe events list
```

### **5. Ver Detalhes de um Evento**
```bash
stripe events retrieve evt_xxxxx
```

---

## 📚 Referências

- [Stripe CLI Documentation](https://stripe.com/docs/stripe-cli)
- [Webhook Testing](https://stripe.com/docs/webhooks/test)
- [Event Types](https://stripe.com/docs/api/events/types)

---

## ✅ Checklist

- [ ] Stripe CLI instalada
- [ ] Login realizado
- [ ] Listener iniciado
- [ ] Webhook secret copiado
- [ ] Secret adicionado ao config
- [ ] Eventos testados
- [ ] Webhooks retornando 200
- [ ] Assinaturas sendo criadas

---

## 🎯 Status Atual

✅ **Chaves atualizadas** no config/stripe.php  
⏳ **Webhook secret** aguardando configuração  
⏳ **Listener** aguardando inicialização

---

## 🚀 Próximo Passo

Execute no terminal:
```bash
stripe login
stripe listen --forward-to localhost/conectcorretores/planos/webhook
```

Copie o `whsec_xxxxx` e adicione ao config!

---

**Webhook configurado = Sistema seguro e funcional! 🔐**

Para suporte: Rafael Dias - doisr.com.br
