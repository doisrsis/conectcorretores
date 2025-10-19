# 🌐 Como Usar com Apache (URLs Amigáveis)

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 18/10/2025

---

## ⚠️ Problema Atual

O servidor embutido do PHP (`php -S`) **NÃO suporta `.htaccess`**.

Por isso as URLs não funcionam:
- ❌ `http://localhost:8083/login`
- ❌ `http://localhost:8083/register`

---

## ✅ SOLUÇÃO 1: Usar Apache do XAMPP (RECOMENDADO)

### Passo 1: Iniciar Apache no XAMPP

1. Abra o **XAMPP Control Panel**
2. Clique em **"Start"** no Apache
3. Aguarde ficar verde

### Passo 2: Acessar o Sistema

```
http://localhost/conectcorretores
```

**URLs funcionarão normalmente:**
- ✅ `http://localhost/conectcorretores/login`
- ✅ `http://localhost/conectcorretores/register`
- ✅ `http://localhost/conectcorretores/dashboard`

### Passo 3: Atualizar config.php

Edite `application/config/config.php`:

```php
$config['base_url'] = 'http://localhost/conectcorretores/';
```

---

## ✅ SOLUÇÃO 2: Usar Temporariamente com index.php

Se não quiser usar Apache agora, acesse com `index.php`:

```
http://localhost:8083/index.php/login
http://localhost:8083/index.php/register
http://localhost:8083/index.php/dashboard
```

**Desvantagem:** URLs feias, mas funciona!

---

## ✅ SOLUÇÃO 3: Configurar VirtualHost (AVANÇADO)

Para usar `http://conectcorretores.local`:

### 1. Editar httpd-vhosts.conf

Arquivo: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

Adicionar:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/conectcorretores"
    ServerName conectcorretores.local
    
    <Directory "C:/xampp/htdocs/conectcorretores">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 2. Editar hosts

Arquivo: `C:\Windows\System32\drivers\etc\hosts`

Adicionar:

```
127.0.0.1 conectcorretores.local
```

### 3. Reiniciar Apache

### 4. Acessar

```
http://conectcorretores.local
```

---

## 📋 COMPARAÇÃO

| Método | URL | .htaccess | Facilidade |
|--------|-----|-----------|------------|
| **Apache XAMPP** | `localhost/conectcorretores/login` | ✅ Sim | ⭐⭐⭐⭐⭐ |
| **VirtualHost** | `conectcorretores.local/login` | ✅ Sim | ⭐⭐⭐ |
| **PHP Server** | `localhost:8083/index.php/login` | ❌ Não | ⭐⭐⭐⭐ |

---

## 🎯 RECOMENDAÇÃO

**Use Apache do XAMPP!**

É mais simples e as URLs ficam limpas:
- ✅ URLs amigáveis
- ✅ .htaccess funciona
- ✅ Mais próximo do ambiente de produção

---

## 🔧 Script Rápido

Criei um arquivo `USAR_APACHE.bat` para facilitar:

```batch
@echo off
echo ========================================
echo Configurando para usar Apache
echo ========================================
echo.
echo 1. Abra o XAMPP Control Panel
echo 2. Inicie o Apache
echo 3. Acesse: http://localhost/conectcorretores
echo.
pause
```

---

**Qual solução você prefere usar?** 🚀
