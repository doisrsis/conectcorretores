# 🧪 Guia de Testes - ConectCorretores

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 18/10/2025

---

## ✅ Sistema Pronto para Testar!

### O que foi criado:

#### **Backend (100%)**
- ✅ 4 Models completos
- ✅ 3 Controllers funcionais
- ✅ Sistema de autenticação
- ✅ Validações

#### **Frontend (100%)**
- ✅ Templates base (header/footer)
- ✅ Landing page
- ✅ Página de login
- ✅ Página de registro
- ✅ Página 404
- ✅ Design responsivo com Tailwind CSS

---

## 🚀 COMO TESTAR

### **Passo 1: Importar Banco de Dados**

```bash
# Via MySQL CLI
mysql -u root -p corretor_saas < database/schema.sql

# Ou via phpMyAdmin
1. Abra http://localhost/phpmyadmin
2. Crie o banco: corretor_saas
3. Importe: database/schema.sql
```

### **Passo 2: Iniciar Servidor**

```bash
# Execute o script
INICIAR.bat

# Ou manualmente
C:\xampp\php83\php.exe -S localhost:8083
```

### **Passo 3: Acessar o Sistema**

```
http://localhost:8083
```

---

## 📋 CHECKLIST DE TESTES

### 1. Landing Page ✅
- [ ] Acessar `http://localhost:8083`
- [ ] Verificar se a página carrega
- [ ] Clicar em "Cadastrar"
- [ ] Clicar em "Entrar"
- [ ] Verificar responsividade (mobile)

### 2. Registro de Usuário ✅
- [ ] Acessar `http://localhost:8083/register`
- [ ] Preencher formulário:
  - Nome: Seu Nome
  - Email: teste@email.com
  - Telefone: (11) 98765-4321
  - WhatsApp: (11) 98765-4321
  - Senha: 123456
  - Confirmar Senha: 123456
- [ ] Clicar em "Criar Conta"
- [ ] Verificar se foi criado com sucesso
- [ ] Verificar se foi redirecionado para `/planos`

### 3. Login ✅
- [ ] Fazer logout (se estiver logado)
- [ ] Acessar `http://localhost:8083/login`
- [ ] Tentar login com dados incorretos
  - Verificar mensagem de erro
- [ ] Fazer login com:
  - Email: teste@email.com
  - Senha: 123456
- [ ] Verificar se foi redirecionado para `/dashboard`

### 4. Validações ✅
- [ ] Tentar registrar com email duplicado
- [ ] Tentar registrar com senha < 6 caracteres
- [ ] Tentar registrar com senhas diferentes
- [ ] Tentar login com email inválido
- [ ] Verificar mensagens de erro

### 5. Sessão ✅
- [ ] Fazer login
- [ ] Verificar se nome aparece (quando implementar dashboard)
- [ ] Fazer logout
- [ ] Tentar acessar área protegida
- [ ] Verificar se redireciona para login

---

## 🔐 CREDENCIAIS DE TESTE

### Admin (Criado no Schema)
```
Email: admin@conectcorretores.com
Senha: password
```

### Corretor (Criar via Registro)
```
Email: teste@email.com
Senha: 123456
```

---

## 🌐 URLs do Sistema

| Página | URL |
|--------|-----|
| **Home** | http://localhost:8083 |
| **Login** | http://localhost:8083/login |
| **Registro** | http://localhost:8083/register |
| **Logout** | http://localhost:8083/logout |
| **Dashboard** | http://localhost:8083/dashboard (próximo) |
| **Admin** | http://localhost:8083/admin (próximo) |

---

## 🐛 Possíveis Erros e Soluções

### Erro: "Database connection failed"

**Solução:**
1. Verificar se MySQL está rodando
2. Verificar credenciais em `application/config/database.php`
3. Verificar se banco `corretor_saas` existe

### Erro: "404 Page Not Found"

**Solução:**
1. Verificar se `.htaccess` existe na raiz
2. Verificar se `mod_rewrite` está habilitado no Apache
3. Verificar `config['index_page'] = '';` em `config.php`

### Erro: "Session: Configured save path is not writable"

**Solução:**
```bash
# Dar permissão nas pastas
chmod 777 application/cache
chmod 777 application/logs
```

### Erro: "Email já cadastrado"

**Solução:**
- Use outro email
- Ou delete o usuário do banco:
```sql
DELETE FROM users WHERE email = 'teste@email.com';
```

---

## ✅ FUNCIONALIDADES IMPLEMENTADAS

### Autenticação
- ✅ Registro de corretor
- ✅ Login com validação
- ✅ Logout
- ✅ Hash de senha (password_hash)
- ✅ Sessões
- ✅ Mensagens de feedback (flash)
- ✅ Validações de formulário
- ✅ Proteção contra email duplicado

### Interface
- ✅ Design moderno com Tailwind CSS
- ✅ Responsivo (mobile/tablet/desktop)
- ✅ Ícones SVG
- ✅ Mensagens de sucesso/erro
- ✅ Auto-hide de alerts
- ✅ Formulários estilizados

### Segurança
- ✅ CSRF Protection (CodeIgniter)
- ✅ XSS Filtering
- ✅ SQL Injection Protection
- ✅ Password Hashing
- ✅ Validação server-side

---

## 📊 PRÓXIMOS PASSOS

Após testar o sistema de autenticação:

### 1. Dashboard do Corretor
- [ ] Controller: Dashboard
- [ ] View: dashboard/index
- [ ] Estatísticas
- [ ] Listagem de imóveis

### 2. CRUD de Imóveis
- [ ] Controller: Imoveis
- [ ] Views: index, create, edit
- [ ] Upload de imagens

### 3. Planos e Checkout
- [ ] Controller: Planos
- [ ] Controller: Checkout
- [ ] Integração Stripe

### 4. Painel Admin
- [ ] Controller: Admin
- [ ] Dashboard admin
- [ ] Gerenciar usuários

---

## 📝 RELATÓRIO DE TESTES

Preencha após testar:

```
Data: ___/___/2025
Testador: _______________

[ ] Landing page funcionando
[ ] Registro funcionando
[ ] Login funcionando
[ ] Logout funcionando
[ ] Validações funcionando
[ ] Mensagens de erro/sucesso
[ ] Responsividade OK
[ ] Sem erros no console

Observações:
_________________________________
_________________________________
_________________________________
```

---

## 🎯 TESTE RÁPIDO (5 minutos)

```bash
# 1. Importar banco
mysql -u root -p corretor_saas < database/schema.sql

# 2. Iniciar servidor
INICIAR.bat

# 3. Abrir navegador
http://localhost:8083

# 4. Criar conta
- Ir para /register
- Preencher formulário
- Clicar em "Criar Conta"

# 5. Fazer login
- Ir para /login
- Email: seu@email.com
- Senha: sua_senha
- Clicar em "Entrar"

# 6. Verificar
- Deve redirecionar para /planos
- (Ainda não implementado, vai dar 404)
```

---

**Sistema pronto para testes! 🚀**

**© 2025 Rafael Dias - doisr.com.br**
