# 🚀 Guia de Início Rápido - CodeIgniter 3

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 18/10/2025  
**Stack:** CodeIgniter 3 + Tailwind CSS + Stripe

---

## ✅ FASE 1 CONCLUÍDA: Configuração Inicial

### O que foi feito:

1. ✅ **CodeIgniter 3 instalado**
2. ✅ **Banco de dados configurado** (`application/config/database.php`)
3. ✅ **Schema SQL criado** (`database/schema.sql`)
4. ✅ **Configurações do CI3**:
   - `config.php` - Base URL e index.php removido
   - `autoload.php` - Database, Session, Form_validation
   - `routes.php` - Todas as rotas definidas
5. ✅ **.htaccess** - URLs amigáveis
6. ✅ **Roadmap completo** criado

---

## 📋 PRÓXIMOS PASSOS

### 1. Importar Banco de Dados

```bash
# Opção 1: Via MySQL CLI
mysql -u root -p corretor_saas < database/schema.sql

# Opção 2: Via phpMyAdmin
1. Abra http://localhost/phpmyadmin
2. Crie o banco: corretor_saas
3. Importe: database/schema.sql
```

### 2. Testar Configuração

```bash
# Iniciar servidor PHP
C:\xampp\php83\php.exe -S localhost:8083

# Acessar
http://localhost:8083
```

Deve aparecer a página de boas-vindas do CodeIgniter.

---

## 📁 Estrutura Atual

```
conectcorretores/
├── application/
│   ├── config/
│   │   ├── config.php ✅ (configurado)
│   │   ├── database.php ✅ (configurado)
│   │   ├── autoload.php ✅ (configurado)
│   │   └── routes.php ✅ (configurado)
│   ├── controllers/ (vazio - próximo passo)
│   ├── models/ (vazio - próximo passo)
│   ├── views/ (vazio - próximo passo)
│   └── libraries/ (vazio - próximo passo)
├── database/
│   └── schema.sql ✅ (criado)
├── .htaccess ✅ (criado)
└── ROADMAP_DESENVOLVIMENTO.md ✅ (criado)
```

---

## 🎯 FASE 2: Desenvolvimento Backend

### Ordem de Desenvolvimento:

#### 1. Models (Semana 1)
```
application/models/
├── User_model.php
├── Plan_model.php
├── Subscription_model.php
└── Imovel_model.php
```

#### 2. Controllers (Semana 2-3)
```
application/controllers/
├── Home.php (landing page)
├── Auth.php (login/register)
├── Dashboard.php (painel corretor)
├── Imoveis.php (CRUD imóveis)
├── Planos.php (listagem planos)
├── Checkout.php (Stripe)
├── Webhook.php (Stripe webhooks)
└── Admin.php (painel admin)
```

#### 3. Views (Semana 4)
```
application/views/
├── templates/
│   ├── header.php
│   ├── footer.php
│   └── sidebar.php
├── home/
│   └── index.php
├── auth/
│   ├── login.php
│   └── register.php
├── dashboard/
│   └── index.php
├── imoveis/
│   ├── index.php
│   ├── create.php
│   └── edit.php
└── admin/
    └── dashboard.php
```

---

## 🛠️ Comandos Úteis

### Iniciar Servidor

```bash
# Porta 8083
C:\xampp\php83\php.exe -S localhost:8083

# Ou usar o script
iniciar_backend.bat
```

### Verificar PHP

```bash
C:\xampp\php83\php.exe -v
# Deve mostrar: PHP 8.3.9
```

### Testar Banco

```bash
mysql -u root -p -e "USE corretor_saas; SHOW TABLES;"
```

---

## 📊 Checklist de Configuração

### Ambiente
- [x] PHP 8.3 instalado
- [x] XAMPP configurado
- [x] CodeIgniter 3 instalado
- [x] Banco de dados criado
- [ ] Composer instalado (para Stripe SDK)
- [ ] Tailwind CSS configurado

### Arquivos de Configuração
- [x] `config.php` - Base URL
- [x] `database.php` - Conexão MySQL
- [x] `autoload.php` - Libraries e helpers
- [x] `routes.php` - Rotas definidas
- [x] `.htaccess` - URLs amigáveis

### Banco de Dados
- [ ] Schema importado
- [ ] Tabelas criadas (users, plans, subscriptions, imoveis)
- [ ] Dados iniciais (admin, planos)
- [ ] Views criadas
- [ ] Triggers criados

---

## 🎨 Próximo: Instalar Tailwind CSS

### Opção 1: CDN (Rápido para desenvolvimento)

Adicionar no `<head>` das views:

```html
<script src="https://cdn.tailwindcss.com"></script>
```

### Opção 2: Build (Produção)

```bash
# Instalar Node.js e npm
# Depois:
npm init -y
npm install -D tailwindcss
npx tailwindcss init

# Criar arquivo CSS
# Compilar
npx tailwindcss -i ./assets/css/input.css -o ./assets/css/output.css --watch
```

---

## 📝 Convenções do Projeto

### Nomenclatura

- **Controllers:** `PascalCase.php` (ex: `Auth.php`, `Dashboard.php`)
- **Models:** `PascalCase_model.php` (ex: `User_model.php`)
- **Views:** `snake_case.php` (ex: `login.php`, `create_imovel.php`)
- **Métodos:** `camelCase` ou `snake_case`
- **Variáveis:** `snake_case`

### Comentários

```php
/**
 * Nome do Método
 * 
 * Descrição do que faz
 * 
 * @param tipo $parametro Descrição
 * @return tipo Descrição
 * @author Rafael Dias - doisr.com.br
 */
public function metodo($parametro) {
    // Código
}
```

---

## 🔐 Segurança

### Já Implementado:
- ✅ CSRF Protection (CodeIgniter)
- ✅ XSS Filtering
- ✅ SQL Injection Protection (Active Record)
- ✅ Password Hashing (será implementado)

### A Implementar:
- [ ] Validação de inputs
- [ ] Sanitização de dados
- [ ] Rate limiting
- [ ] Session security

---

## 📚 Recursos

### Documentação
- [CodeIgniter 3](https://codeigniter.com/userguide3/)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Stripe PHP](https://stripe.com/docs/api/php)

### Ferramentas
- [phpMyAdmin](http://localhost/phpmyadmin)
- [Stripe Dashboard](https://dashboard.stripe.com/test/dashboard)

---

## 🐛 Troubleshooting

### Erro: "404 Page Not Found"

**Solução:**
1. Verificar `.htaccess` existe na raiz
2. Verificar `mod_rewrite` está habilitado
3. Verificar `config['index_page'] = '';`

### Erro: "Database connection failed"

**Solução:**
1. Verificar MySQL está rodando
2. Verificar credenciais em `database.php`
3. Verificar banco `corretor_saas` existe

### Erro: "Session: Configured save path is not writable"

**Solução:**
```bash
# Dar permissão na pasta de sessões
chmod 777 application/cache
chmod 777 application/logs
```

---

## ✅ Status Atual

| Item | Status |
|------|--------|
| PHP 8.3 | ✅ Instalado |
| CodeIgniter 3 | ✅ Configurado |
| Banco de Dados | ⏳ Aguardando import |
| Rotas | ✅ Definidas |
| Models | ⏳ Próximo passo |
| Controllers | ⏳ Próximo passo |
| Views | ⏳ Próximo passo |
| Tailwind CSS | ⏳ Próximo passo |
| Stripe | ⏳ Próximo passo |

---

## 🎯 Próxima Tarefa

**Importar o schema SQL e começar a criar os Models!**

```bash
# 1. Importar banco
mysql -u root -p corretor_saas < database/schema.sql

# 2. Verificar
mysql -u root -p -e "USE corretor_saas; SHOW TABLES;"

# 3. Criar primeiro Model (User_model.php)
```

---

**Pronto para começar o desenvolvimento! 🚀**

**© 2025 Rafael Dias - doisr.com.br**
