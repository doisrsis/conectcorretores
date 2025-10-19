# 🚀 Checklist de Deploy no cPanel - ConectCorretores

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 19/10/2025

---

## 📋 Antes de Fazer Upload

### 1. **Configurar Base URL**

Edite: `application/config/config.php`

```php
// LOCALHOST (desenvolvimento)
$config['base_url'] = 'http://localhost/conectcorretores/';

// PRODUÇÃO (alterar para seu domínio)
$config['base_url'] = 'https://seudominio.com.br/';
```

---

### 2. **Configurar Banco de Dados**

Edite: `application/config/database.php`

```php
// LOCALHOST
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'conectcorretores',
);

// PRODUÇÃO (usar dados do cPanel)
$db['default'] = array(
    'hostname' => 'localhost',  // Geralmente é localhost
    'username' => 'seucpanel_usuario',  // Usuário do MySQL no cPanel
    'password' => 'senha_forte_aqui',   // Senha do MySQL
    'database' => 'seucpanel_conectcorretores',  // Nome do banco
);
```

**Como obter esses dados no cPanel:**
1. Acesse **MySQL Databases**
2. Crie um banco de dados
3. Crie um usuário
4. Adicione o usuário ao banco
5. Anote: nome do banco, usuário e senha

---

### 3. **Configurar Stripe para Produção**

Edite: `application/config/stripe.php`

```php
// Mudar de 'test' para 'live' quando for produção
$config['stripe_environment'] = 'live';

// Adicionar chaves de produção (obter no Stripe Dashboard)
$config['stripe_live_public_key'] = 'pk_live_xxxxxxxxxxxxx';
$config['stripe_live_secret_key'] = 'sk_live_xxxxxxxxxxxxx';

// Webhook Secret (configurar depois)
$config['stripe_webhook_secret'] = 'whsec_xxxxxxxxxxxxx';
```

**⚠️ IMPORTANTE:** Mantenha em `test` até testar tudo no servidor!

---

### 4. **Configurar .htaccess**

Verifique se existe: `public_html/.htaccess`

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Se estiver em subpasta, ajuste a RewriteBase
    # RewriteBase /
    
    # Redirecionar para HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Remover index.php da URL
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>

# Proteger arquivos sensíveis
<FilesMatch "^(\.htaccess|\.gitignore|composer\.json|composer\.lock)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Proteger diretórios
Options -Indexes
```

---

## 📤 Upload dos Arquivos

### Opção 1: FTP/SFTP (Recomendado)
1. Use **FileZilla** ou **WinSCP**
2. Conecte via FTP/SFTP
3. Faça upload de todos os arquivos para `public_html/`

### Opção 2: File Manager do cPanel
1. Compacte o projeto em `.zip`
2. Acesse **File Manager** no cPanel
3. Faça upload do `.zip` para `public_html/`
4. Extraia o arquivo

---

## 🗄️ Configurar Banco de Dados

### 1. Criar Banco no cPanel

1. Acesse **MySQL Databases**
2. Crie banco: `seucpanel_conectcorretores`
3. Crie usuário: `seucpanel_user`
4. Adicione usuário ao banco com **ALL PRIVILEGES**

### 2. Importar Schema

1. Acesse **phpMyAdmin**
2. Selecione o banco criado
3. Clique em **Importar**
4. Selecione: `database/schema.sql`
5. Execute

### 3. Importar Dados Iniciais (se houver)

```sql
-- Inserir planos de exemplo
INSERT INTO plans (nome, descricao, preco, tipo, stripe_price_id, limite_imoveis, ativo) VALUES
('Básico', 'Plano ideal para começar', 49.90, 'mensal', 'price_xxxxx', 10, 1),
('Profissional', 'Plano mais popular', 99.90, 'mensal', 'price_xxxxx', 50, 1),
('Premium', 'Recursos ilimitados', 199.90, 'mensal', 'price_xxxxx', NULL, 1);
```

---

## 🔐 Permissões de Arquivos

Execute via SSH ou File Manager:

```bash
# Diretórios
chmod 755 application/
chmod 755 system/
chmod 755 assets/
chmod 777 application/cache/
chmod 777 application/logs/
chmod 777 uploads/

# Arquivos
chmod 644 index.php
chmod 644 .htaccess
chmod 600 application/config/database.php
chmod 600 application/config/stripe.php
```

---

## 🔗 Configurar Webhook do Stripe

### 1. No Stripe Dashboard

1. Acesse: https://dashboard.stripe.com/webhooks
2. Clique em **Add endpoint**
3. Configure:
   - **URL**: `https://seudominio.com.br/planos/webhook`
   - **Eventos**:
     - `checkout.session.completed`
     - `invoice.payment_succeeded`
     - `invoice.payment_failed`
     - `customer.subscription.deleted`
4. Copie o **Webhook Secret** (whsec_xxxxx)

### 2. Atualizar Configuração

Edite: `application/config/stripe.php`

```php
$config['stripe_webhook_secret'] = 'whsec_xxxxxxxxxxxxx';
```

---

## ✅ Checklist Final

Antes de ir ao ar, verifique:

- [ ] **Base URL** configurada corretamente
- [ ] **Banco de dados** importado e funcionando
- [ ] **Credenciais do banco** corretas em `database.php`
- [ ] **Stripe em modo TEST** inicialmente
- [ ] **Webhook configurado** e testado
- [ ] **SSL/HTTPS** ativo (obrigatório para Stripe)
- [ ] **Permissões** de arquivos corretas
- [ ] **.htaccess** funcionando (URLs amigáveis)
- [ ] **Uploads/** com permissão de escrita
- [ ] **Cache/** com permissão de escrita
- [ ] **Logs/** com permissão de escrita

---

## 🧪 Testar em Produção

### 1. Teste Básico
- [ ] Acessar homepage
- [ ] Fazer login
- [ ] Cadastrar imóvel
- [ ] Upload de imagens

### 2. Teste Stripe (Modo Test)
- [ ] Visualizar planos
- [ ] Clicar em "Assinar"
- [ ] Pagar com cartão teste: `4242 4242 4242 4242`
- [ ] Verificar se assinatura foi criada no banco
- [ ] Verificar se webhook foi recebido

### 3. Ativar Produção
Após todos os testes:

```php
// application/config/stripe.php
$config['stripe_environment'] = 'live';
```

---

## 🆘 Problemas Comuns

### Erro 500 - Internal Server Error
- Verificar permissões dos arquivos
- Verificar logs em `application/logs/`
- Verificar se `.htaccess` está correto

### Página em branco
- Ativar display_errors temporariamente
- Verificar logs do PHP no cPanel

### Stripe não funciona
- Verificar se SSL/HTTPS está ativo
- Verificar chaves do Stripe
- Verificar webhook secret

### Upload de imagens não funciona
- Verificar permissão da pasta `uploads/` (777)
- Verificar limite de upload no PHP (php.ini)

---

## 📞 Suporte

Se precisar de ajuda:
- Email: suporte@conectcorretores.com.br
- Documentação Stripe: https://stripe.com/docs

---

**Boa sorte com o deploy! 🚀**
