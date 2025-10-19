# 🚀 Deploy Rápido - ConectCorretores

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 19/10/2025

---

## ⚡ Alterações Obrigatórias

### 1️⃣ **Base URL** → `application/config/config.php`
```php
$config['base_url'] = 'https://seudominio.com.br/';
```

### 2️⃣ **Banco de Dados** → `application/config/database.php`
```php
'hostname' => 'localhost',
'username' => 'seucpanel_usuario',
'password' => 'senha_mysql',
'database' => 'seucpanel_conectcorretores',
```

### 3️⃣ **Stripe** → `application/config/stripe.php`
```php
// Manter em 'test' até testar tudo
$config['stripe_environment'] = 'test';

// Depois de testar, mudar para:
$config['stripe_environment'] = 'live';

// E adicionar chaves de produção:
$config['stripe_live_public_key'] = 'pk_live_xxxxx';
$config['stripe_live_secret_key'] = 'sk_live_xxxxx';
```

---

## 📋 Checklist Rápido

### Antes do Upload
- [ ] Alterar `base_url` em `config.php`
- [ ] Alterar credenciais do banco em `database.php`
- [ ] Manter Stripe em modo `test`

### No cPanel
- [ ] Criar banco de dados MySQL
- [ ] Criar usuário MySQL
- [ ] Adicionar usuário ao banco
- [ ] Importar `database/schema.sql` via phpMyAdmin

### Após Upload
- [ ] Verificar permissões:
  - `uploads/` → 777
  - `application/cache/` → 777
  - `application/logs/` → 777
- [ ] Testar login
- [ ] Testar cadastro de imóvel
- [ ] Testar checkout Stripe (modo test)

### Configurar Webhook Stripe
1. Acesse: https://dashboard.stripe.com/webhooks
2. URL: `https://seudominio.com.br/planos/webhook`
3. Eventos:
   - `checkout.session.completed`
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`
   - `customer.subscription.deleted`
4. Copiar Webhook Secret
5. Adicionar em `stripe.php`:
   ```php
   $config['stripe_webhook_secret'] = 'whsec_xxxxx';
   ```

### Ativar Produção
Após testar tudo em modo test:
```php
$config['stripe_environment'] = 'live';
```

---

## 🆘 Problemas Comuns

| Problema | Solução |
|----------|---------|
| Erro 500 | Verificar permissões e logs |
| Página branca | Verificar `application/logs/` |
| Stripe não funciona | Verificar SSL/HTTPS ativo |
| Upload não funciona | Permissão 777 em `uploads/` |

---

## 📚 Documentação Completa

Ver: `doc_stripe/deploy-cpanel.md`

---

**Boa sorte! 🎉**
