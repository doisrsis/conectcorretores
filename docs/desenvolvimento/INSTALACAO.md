# 📦 Guia de Instalação - ConectCorretores v1.0.0

**Autor:** Rafael Dias - doisr.com.br  
**Repositório:** https://github.com/doisrsis/conectcorretores

---

## 📋 Requisitos do Sistema

### Obrigatórios
- ✅ **PHP** >= 8.0
- ✅ **MySQL** >= 5.7 ou MariaDB >= 10.2
- ✅ **Apache** >= 2.4 com `mod_rewrite` habilitado
- ✅ **Git** (para clonar o repositório)

### Extensões PHP Necessárias
- ✅ `mysqli`
- ✅ `mbstring`
- ✅ `openssl`
- ✅ `json`
- ✅ `curl`

### Recomendado
- 📦 **Composer** (gerenciador de dependências)
- 🔧 **XAMPP** ou **WAMP** (para desenvolvimento local)

---

## 🚀 Instalação Rápida (Desenvolvimento Local)

### 1️⃣ Clone o Repositório

```bash
# Navegue até a pasta do servidor web
cd c:\xampp\htdocs

# Clone o projeto
git clone https://github.com/doisrsis/conectcorretores.git

# Entre na pasta
cd conectcorretores
```

### 2️⃣ Configure o Banco de Dados

#### Opção A: Via phpMyAdmin (Recomendado)

1. Acesse: http://localhost/phpmyadmin
2. Clique em "Novo" para criar banco
3. Nome: `conectcorretores`
4. Collation: `utf8mb4_unicode_ci`
5. Clique em "Criar"
6. Vá na aba "Importar"
7. Escolha o arquivo: `database/schema.sql`
8. Clique em "Executar"

#### Opção B: Via Script (Windows)

```bash
# Execute o script automático
IMPORTAR_BANCO.bat
```

#### Opção C: Via Linha de Comando

```bash
# Criar banco
mysql -u root -p -e "CREATE DATABASE conectcorretores CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importar schema
mysql -u root -p conectcorretores < database/schema.sql
```

### 3️⃣ Configure o Ambiente

#### Configurar Banco de Dados

```bash
# Copie o arquivo de exemplo
cp application/config/database.example.php application/config/database.php

# Edite com suas credenciais
# Notepad, VSCode, ou qualquer editor
notepad application/config/database.php
```

**Edite as linhas:**
```php
'hostname' => 'localhost',
'username' => 'root',        // Seu usuário MySQL
'password' => '',            // Sua senha MySQL
'database' => 'conectcorretores',
```

#### Configurar Aplicação

```bash
# Copie o arquivo de exemplo
cp application/config/config.example.php application/config/config.php

# Edite a base_url
notepad application/config/config.php
```

**Edite a linha:**
```php
$config['base_url'] = 'http://localhost/conectcorretores/';
```

**Gere uma chave de criptografia:**
```php
// Gere uma chave única (32 caracteres)
// Use: bin2hex(random_bytes(16))
$config['encryption_key'] = 'sua_chave_secreta_32_caracteres';
```

### 4️⃣ Configure o Apache

#### Habilitar mod_rewrite

**XAMPP:**
1. Abra: `C:\xampp\apache\conf\httpd.conf`
2. Procure: `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Remova o `#` do início
4. Salve e reinicie o Apache

**Verificar .htaccess:**
```apache
# Arquivo já está configurado em:
# /conectcorretores/.htaccess

# Verifique se RewriteBase está correto:
RewriteBase /conectcorretores/
```

### 5️⃣ Configure Permissões (Linux/Mac)

```bash
# Dar permissão de escrita
chmod -R 755 application/cache
chmod -R 755 application/logs
chmod -R 755 uploads
```

### 6️⃣ Inicie o Servidor

**XAMPP:**
1. Abra o XAMPP Control Panel
2. Start Apache
3. Start MySQL

**Ou use o script:**
```bash
USAR_APACHE.bat
```

### 7️⃣ Acesse o Sistema

```
http://localhost/conectcorretores
```

### 8️⃣ Faça Login

**Credenciais do Administrador:**
- Email: `admin@conectcorretores.com`
- Senha: `password`

⚠️ **IMPORTANTE:** Altere a senha após o primeiro login!

---

## 🌐 Instalação em Produção

### 1️⃣ Requisitos Adicionais

- ✅ Domínio configurado
- ✅ Certificado SSL (HTTPS)
- ✅ Servidor com PHP e MySQL
- ✅ Acesso SSH

### 2️⃣ Upload dos Arquivos

```bash
# Via Git (Recomendado)
cd /var/www/html
git clone https://github.com/doisrsis/conectcorretores.git
cd conectcorretores

# Ou via FTP
# Faça upload de todos os arquivos
```

### 3️⃣ Configure o Banco de Dados

```bash
# Criar banco
mysql -u seu_usuario -p -e "CREATE DATABASE conectcorretores CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importar
mysql -u seu_usuario -p conectcorretores < database/schema.sql
```

### 4️⃣ Configure os Arquivos

```bash
# Database
cp application/config/database.example.php application/config/database.php
nano application/config/database.php

# Config
cp application/config/config.example.php application/config/config.php
nano application/config/config.php
```

**Configure:**
```php
// database.php
'hostname' => 'localhost',
'username' => 'seu_usuario_mysql',
'password' => 'sua_senha_mysql',
'database' => 'conectcorretores',

// config.php
$config['base_url'] = 'https://seudominio.com.br/';
$config['encryption_key'] = 'gere_uma_chave_unica_32_chars';
$config['log_threshold'] = 1; // Apenas erros
```

### 5️⃣ Configure Permissões

```bash
# Permissões de pastas
chmod -R 755 application/cache
chmod -R 755 application/logs
chmod -R 755 uploads

# Proprietário (ajuste conforme seu servidor)
chown -R www-data:www-data application/cache
chown -R www-data:www-data application/logs
chown -R www-data:www-data uploads
```

### 6️⃣ Configure o Apache/Nginx

#### Apache (.htaccess)

O arquivo `.htaccess` já está configurado. Certifique-se de que:

```apache
# httpd.conf ou virtual host
<Directory /var/www/html/conectcorretores>
    AllowOverride All
    Require all granted
</Directory>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name seudominio.com.br;
    root /var/www/html/conectcorretores;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 7️⃣ Configure SSL (HTTPS)

```bash
# Usando Certbot (Let's Encrypt)
sudo certbot --apache -d seudominio.com.br

# Ou
sudo certbot --nginx -d seudominio.com.br
```

### 8️⃣ Segurança Adicional

```bash
# Proteger arquivos sensíveis
chmod 600 application/config/database.php
chmod 600 application/config/config.php

# Desabilitar listagem de diretórios
# Já configurado no .htaccess
```

---

## 🔧 Configurações Avançadas

### Configurar Email (SMTP)

Edite `application/config/email.php`:

```php
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.gmail.com';
$config['smtp_port'] = 587;
$config['smtp_user'] = 'seu-email@gmail.com';
$config['smtp_pass'] = 'sua-senha-app';
$config['smtp_crypto'] = 'tls';
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
```

### Configurar Upload de Arquivos

Edite `php.ini`:

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M
```

### Configurar Cache

```php
// application/config/config.php
$config['cache_path'] = APPPATH . 'cache/';
```

### Configurar Logs

```php
// application/config/config.php
$config['log_threshold'] = 1; // Produção: apenas erros
$config['log_path'] = APPPATH . 'logs/';
```

---

## ✅ Verificação Pós-Instalação

### Checklist

- [ ] Banco de dados criado e importado
- [ ] Arquivos de configuração criados
- [ ] Base URL configurada corretamente
- [ ] Apache/Nginx funcionando
- [ ] mod_rewrite habilitado
- [ ] Permissões configuradas
- [ ] SSL configurado (produção)
- [ ] Login funcionando
- [ ] Dashboard carregando
- [ ] Cadastro de imóveis funcionando

### Testar URLs

```bash
# Home
http://localhost/conectcorretores

# Login
http://localhost/conectcorretores/login

# Dashboard (após login)
http://localhost/conectcorretores/dashboard

# Admin (após login como admin)
http://localhost/conectcorretores/admin
```

---

## 🐛 Troubleshooting

### Erro 404 em todas as páginas

**Causa:** mod_rewrite não habilitado ou .htaccess não funcionando

**Solução:**
```bash
# Habilitar mod_rewrite
sudo a2enmod rewrite
sudo service apache2 restart

# Verificar AllowOverride
# httpd.conf: AllowOverride All
```

### Erro de conexão com banco

**Causa:** Credenciais incorretas

**Solução:**
```bash
# Verificar credenciais em:
application/config/database.php

# Testar conexão:
mysql -u root -p
```

### Página em branco

**Causa:** Erro PHP não exibido

**Solução:**
```php
// index.php (temporariamente)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ou verificar logs:
application/logs/log-YYYY-MM-DD.php
```

### Erro de sessão

**Causa:** Tabela ci_sessions não existe

**Solução:**
```sql
-- Reimportar schema.sql
mysql -u root -p conectcorretores < database/schema.sql
```

### Erro de permissão

**Causa:** Pastas sem permissão de escrita

**Solução:**
```bash
chmod -R 755 application/cache
chmod -R 755 application/logs
chmod -R 755 uploads
```

---

## 📞 Suporte

### Documentação
- [README.md](README.md) - Visão geral
- [CHANGELOG.md](CHANGELOG.md) - Histórico de versões
- [GIT_COMANDOS.md](GIT_COMANDOS.md) - Comandos Git

### Comunidade
- GitHub Issues: https://github.com/doisrsis/conectcorretores/issues
- Email: contato@doisr.com.br

---

## 🎯 Próximos Passos

Após a instalação:

1. ✅ Altere a senha do admin
2. ✅ Configure o SMTP para emails
3. ✅ Personalize o sistema
4. ✅ Cadastre os planos
5. ✅ Convide corretores
6. ✅ Configure backup automático

---

**Desenvolvido com ❤️ por Rafael Dias - [doisr.com.br](https://doisr.com.br)**
