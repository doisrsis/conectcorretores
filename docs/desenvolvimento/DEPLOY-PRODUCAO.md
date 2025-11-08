# 🚀 GUIA DE DEPLOY PARA PRODUÇÃO

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 08/11/2025  
**Projeto:** ConectCorretores

---

## 📋 PRÉ-REQUISITOS

- [ ] Código testado em ambiente de desenvolvimento
- [ ] Migrations executadas localmente
- [ ] Testes de email funcionando
- [ ] Credenciais de produção preparadas
- [ ] Backup do banco de dados de produção

---

## 🔄 WORKFLOW DE DEPLOY

### **1. DESENVOLVIMENTO LOCAL** 💻

```bash
# Desenvolver e testar
# Validar funcionalidades
# Testar emails (http://localhost/conectcorretores/test_email)
```

### **2. COMMIT E PUSH** 📦

```bash
git add .
git commit -m "feat: descrição da funcionalidade"
git tag -a v1.x.x -m "Descrição da versão"
git push origin main
git push origin v1.x.x
```

### **3. DEPLOY PARA PRODUÇÃO** 🚀

#### **Via SSH:**
```bash
# Conectar no servidor
ssh usuario@conectcorretores.doisr.com.br

# Ir para o diretório
cd /home/conectcorretores/public_html

# Puxar atualizações
git pull origin main

# Executar migrations (se houver)
php index.php migrate
```

#### **Via FTP:**
1. Sincronizar arquivos via plugin FTP da IDE
2. Verificar se todos os arquivos foram enviados
3. Executar migrations manualmente (se necessário)

---

## ⚙️ CONFIGURAÇÃO INICIAL (PRIMEIRA VEZ)

### **1. Clonar Repositório no Servidor**

```bash
cd /home/conectcorretores
git clone https://github.com/doisrsis/conectcorretores.git public_html
cd public_html
```

### **2. Criar Arquivos de Configuração**

```bash
cd application/config

# Copiar templates
cp config.example.php config.php
cp database.example.php database.php
cp email.example.php email.php
cp stripe.example.php stripe.php
```

### **3. Configurar `config.php`**

```php
$config['base_url'] = 'https://conectcorretores.doisr.com.br/';
$config['encryption_key'] = 'GERAR_CHAVE_UNICA_AQUI';
$config['log_threshold'] = 1; // 0 em produção, 1 para erros
```

**Gerar encryption_key:**
```bash
php -r "echo bin2hex(random_bytes(16));"
```

### **4. Configurar `database.php`**

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'conectcorretores_user',
    'password' => 'SENHA_DO_BANCO',
    'database' => 'conectcorretores_db',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => FALSE, // FALSE em produção
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => FALSE
);
```

### **5. Configurar `email.php`**

```php
// Protocolo de envio
$config['email_protocol'] = 'smtp';

// Configurações SMTP - ValueServer
$config['smtp_host'] = 'br61-cp.valueserver.com.br';
$config['smtp_port'] = 465;
$config['smtp_crypto'] = 'ssl'; // SSL para porta 465

// Credenciais SMTP
$config['smtp_user'] = 'noreply@conectcorretores.com.br';
$config['smtp_pass'] = 'U248nKFUVgksm[&O@2025';

// Configurações de email
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config['wordwrap'] = TRUE;

// Remetente padrão
$config['from_email'] = 'noreply@conectcorretores.com.br';
$config['from_name'] = 'ConectCorretores';

// URLs do sistema
$config['site_url'] = base_url();
$config['site_name'] = 'ConectCorretores';

// Configurações de produção
$config['email_debug'] = FALSE; // FALSE em produção
$config['email_log'] = TRUE;
```

### **6. Configurar `stripe.php`**

```php
// Chaves de PRODUÇÃO do Stripe
$config['stripe_secret_key'] = 'sk_live_XXXXXXXXXX';
$config['stripe_publishable_key'] = 'pk_live_XXXXXXXXXX';
$config['stripe_webhook_secret'] = 'whsec_XXXXXXXXXX';

// Ambiente
$config['stripe_mode'] = 'live'; // 'live' em produção
```

### **7. Definir Permissões**

```bash
# Permissões de arquivos
chmod 640 application/config/*.php
chmod 755 application/logs
chmod 755 application/cache
chmod 755 uploads

# Proprietário (ajustar conforme servidor)
chown -R www-data:www-data application/logs
chown -R www-data:www-data application/cache
chown -R www-data:www-data uploads
```

### **8. Configurar .htaccess**

Verificar se o `.htaccess` está correto na raiz:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]

# Forçar HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### **9. Executar Migrations**

```bash
# Via SSH
php index.php migrate

# Ou via browser (apenas em desenvolvimento)
https://conectcorretores.doisr.com.br/migrate
```

### **10. Testar Email**

```bash
# Acessar (apenas em desenvolvimento)
https://conectcorretores.doisr.com.br/test_email
```

---

## ✅ CHECKLIST DE DEPLOY

### **Antes do Deploy:**
- [ ] Código testado localmente
- [ ] Migrations testadas
- [ ] Emails testados
- [ ] Backup do banco de dados
- [ ] Versão commitada no Git
- [ ] Tag criada (vX.X.X)

### **Durante o Deploy:**
- [ ] Pull do código no servidor
- [ ] Verificar arquivos de configuração
- [ ] Executar migrations
- [ ] Limpar cache
- [ ] Testar funcionalidades críticas

### **Após o Deploy:**
- [ ] Testar login
- [ ] Testar cadastro
- [ ] Testar checkout
- [ ] Testar envio de emails
- [ ] Verificar logs de erro
- [ ] Monitorar por 24h

---

## 🔙 ROLLBACK (SE NECESSÁRIO)

### **Via Git:**
```bash
# Ver tags disponíveis
git tag

# Voltar para versão anterior
git checkout v1.4.0

# Ou reverter commit específico
git revert <commit-hash>
git push origin main
```

### **Via Backup:**
```bash
# Restaurar arquivos
cp -r /backup/public_html/* /home/conectcorretores/public_html/

# Restaurar banco de dados
mysql -u user -p database < backup.sql
```

---

## 📊 MONITORAMENTO

### **Logs do Sistema:**
```bash
tail -f application/logs/log-*.php
```

### **Logs do Apache/Nginx:**
```bash
tail -f /var/log/apache2/error.log
tail -f /var/log/nginx/error.log
```

### **Logs do PHP:**
```bash
tail -f /var/log/php-fpm/error.log
```

---

## 🔐 SEGURANÇA

### **Arquivos Sensíveis (NUNCA commitar):**
- `application/config/config.php`
- `application/config/database.php`
- `application/config/email.php`
- `application/config/stripe.php`

### **Verificar .gitignore:**
```
application/config/database.php
application/config/config.php
application/config/email.php
application/config/stripe.php
```

---

## 🆘 TROUBLESHOOTING

### **Erro: "The configuration file email.php does not exist"**
**Solução:** Copiar `email.example.php` para `email.php` e configurar

### **Erro: "Unable to connect to database"**
**Solução:** Verificar credenciais em `database.php`

### **Erro: "404 Not Found"**
**Solução:** Verificar `.htaccess` e mod_rewrite do Apache

### **Emails não enviando:**
**Solução:** 
1. Verificar credenciais SMTP em `email.php`
2. Testar com `test_email` controller
3. Verificar logs em `application/logs/`

---

## 📞 CONTATO

**Desenvolvedor:** Rafael Dias  
**Site:** doisr.com.br  
**Email:** doisr.sistemas@gmail.com

---

## 📝 HISTÓRICO DE DEPLOYS

| Data | Versão | Descrição | Status |
|------|--------|-----------|--------|
| 08/11/2025 | v1.5.0 | Sistema de Configurações | ✅ |
| 07/11/2025 | v1.4.0 | Recuperação de Senha | ✅ |
| 06/11/2025 | v1.3.0 | Sistema de Emails | ✅ |

---

**Última atualização:** 08/11/2025
