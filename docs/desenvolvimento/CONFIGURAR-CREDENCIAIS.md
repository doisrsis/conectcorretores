# 🔐 Configurar Credenciais do Projeto

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 06/11/2025

---

## 🎯 Objetivo

Este guia explica como configurar as credenciais necessárias para rodar o projeto localmente.

---

## 📋 Arquivos de Configuração

O projeto usa arquivos `.example.php` como templates. Você precisa criar cópias sem o `.example` e adicionar suas credenciais.

### **Arquivos Necessários:**
```
application/config/email.php (criar a partir do .example)
application/config/stripe.php (criar a partir do .example)
```

---

## 🚀 Passo a Passo

### **1. Copiar Arquivos de Exemplo**

```bash
# No diretório do projeto:
cd application/config

# Copiar templates
copy email.example.php email.php
copy stripe.example.php stripe.php
```

---

### **2. Configurar Email (email.php)**

Editar: `application/config/email.php`

#### **Opção A: Gmail**

```php
$config['smtp_user'] = 'seu-email@gmail.com';
$config['smtp_pass'] = 'sua-senha-de-app';
```

**Como gerar senha de app:**
1. https://myaccount.google.com/security
2. Ativar "Verificação em duas etapas"
3. https://myaccount.google.com/apppasswords
4. Criar senha para "Email"
5. Copiar 16 caracteres

#### **Opção B: SendGrid**

```php
$config['smtp_host'] = 'smtp.sendgrid.net';
$config['smtp_user'] = 'apikey';
$config['smtp_pass'] = 'SUA_API_KEY_SENDGRID';
```

---

### **3. Configurar Stripe (stripe.php)**

Editar: `application/config/stripe.php`

```php
// Chaves de Teste
$config['stripe_test_public_key'] = 'pk_test_xxxxx';
$config['stripe_test_secret_key'] = 'sk_test_xxxxx';

// Webhook Secret
$config['stripe_webhook_secret_test'] = 'whsec_xxxxx';

// Produto ID
$config['stripe_product_id'] = 'prod_xxxxx';
```

**Onde encontrar:**
- Dashboard: https://dashboard.stripe.com/
- API Keys: https://dashboard.stripe.com/apikeys
- Webhooks: https://dashboard.stripe.com/webhooks
- Produtos: https://dashboard.stripe.com/products

---

## ✅ Verificar Configuração

### **Testar Email:**
```
http://localhost/conectcorretores/test_email
```

### **Testar Stripe:**
```
http://localhost/conectcorretores/planos
```

---

## 🔒 Segurança

### **⚠️ NUNCA COMMITAR:**
```
application/config/email.php
application/config/stripe.php
```

### **✅ SEMPRE COMMITAR:**
```
application/config/email.example.php
application/config/stripe.example.php
```

### **Arquivos Protegidos (.gitignore):**
```
application/config/email.php
application/config/stripe.php
```

---

## 📚 Documentação Relacionada

- [Sistema de Emails](SISTEMA-EMAILS-IMPLEMENTADO.md)
- [Configurar Webhook](CONFIGURAR-WEBHOOK-STRIPE-CLI.md)
- [Testar Emails](TESTAR-EMAILS.md)

---

**Credenciais configuradas = Sistema funcionando! 🔐**

Para suporte: Rafael Dias - doisr.com.br
