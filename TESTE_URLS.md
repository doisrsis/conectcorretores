# 🧪 Teste de URLs - ConectCorretores

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 18/10/2025

---

## ✅ Configuração Correta Aplicada

### Arquivos Atualizados:

1. ✅ `.htaccess` → `RewriteBase /conectcorretores/`
2. ✅ `config.php` → `base_url = 'http://localhost/conectcorretores/'`

---

## 🧪 TESTE AGORA

### Passo 1: Limpar Cache do Navegador

**Chrome/Edge:**
- Pressione `Ctrl + Shift + Delete`
- Marque "Imagens e arquivos em cache"
- Clique em "Limpar dados"

**Ou use modo anônimo:**
- Pressione `Ctrl + Shift + N`

### Passo 2: Acessar as URLs

Teste cada uma dessas URLs:

```
✅ http://localhost/conectcorretores
✅ http://localhost/conectcorretores/login
✅ http://localhost/conectcorretores/register
✅ http://localhost/conectcorretores/dashboard
```

### Passo 3: Verificar Redirecionamento

**Comportamento Esperado:**

1. **Home (`/`)** → Mostra landing page
2. **Login (`/login`)** → Mostra formulário de login
3. **Register (`/register`)** → Mostra formulário de cadastro
4. **Dashboard (`/dashboard`)** → Redireciona para `/login` (se não logado)

---

## 🔍 Se Ainda Não Funcionar

### Verificar mod_rewrite no Apache

1. Abra: `C:\xampp\apache\conf\httpd.conf`
2. Procure por: `LoadModule rewrite_module`
3. Certifique-se que **NÃO** tem `#` na frente
4. Deve estar assim:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
5. Se tiver `#`, remova e reinicie o Apache

### Verificar AllowOverride

No mesmo arquivo `httpd.conf`, procure:

```apache
<Directory "C:/xampp/htdocs">
    AllowOverride All
</Directory>
```

Se estiver `AllowOverride None`, mude para `All`.

---

## 🎯 Teste Rápido

Execute este comando no navegador:

```
http://localhost/conectcorretores/index.php/login
```

**Se funcionar com `index.php`:**
- ✅ CodeIgniter está OK
- ❌ Problema é no `.htaccess` ou `mod_rewrite`

**Se NÃO funcionar:**
- ❌ Problema é no CodeIgniter ou configuração

---

## 📋 Checklist de Diagnóstico

- [ ] Apache está rodando no XAMPP
- [ ] Acessou `http://localhost/conectcorretores`
- [ ] Limpou cache do navegador
- [ ] Testou em modo anônimo
- [ ] Verificou `mod_rewrite` habilitado
- [ ] Verificou `AllowOverride All`
- [ ] Testou com `index.php` na URL

---

## 🚀 URLs de Teste

Copie e cole no navegador:

```
http://localhost/conectcorretores
http://localhost/conectcorretores/login
http://localhost/conectcorretores/register
http://localhost/conectcorretores/logout
http://localhost/conectcorretores/dashboard
http://localhost/conectcorretores/planos
```

---

**Teste agora e me diga o resultado! 🎯**
