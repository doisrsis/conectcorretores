# 🗺️ Roadmap de Desenvolvimento - ConectCorretores

**Autor:** Rafael Dias - doisr.com.br  
**Data Início:** 18/10/2025  
**Stack:** CodeIgniter 3 + Tailwind CSS + Stripe + MySQL

---

## 📊 Visão Geral do Projeto

**Objetivo:** Sistema SaaS para corretores gerenciarem imóveis com assinatura recorrente.

**Tecnologias:**
- ✅ Backend: CodeIgniter 3
- ✅ Frontend: Tailwind CSS
- ✅ Pagamento: Stripe API
- ✅ Banco: MySQL
- ✅ Deploy: cPanel

---

## 📅 FASE 1: Planejamento e Configuração Inicial (2-4 dias)

### ✅ 1.1 Configuração do Ambiente (1-2 dias)
- [x] Configurar ambiente local (XAMPP)
- [x] Instalar CodeIgniter 3
- [x] Configurar banco de dados
- [ ] Instalar Tailwind CSS
- [ ] Configurar autoload e rotas
- [ ] Criar estrutura de pastas

**Status:** 60% Concluído

### 🔄 1.2 Estrutura do Banco de Dados (1-2 dias)
- [ ] Criar script SQL completo
- [ ] Criar tabelas: users, plans, subscriptions, imoveis
- [ ] Configurar relacionamentos
- [ ] Popular dados iniciais (planos)
- [ ] Testar conexão

**Status:** Pendente

---

## 📅 FASE 2: Desenvolvimento do Backend (15-19 dias)

### 2.1 Cadastro de Usuário/Corretor (3-4 dias)
- [ ] Model: User_model
- [ ] Controller: Auth
- [ ] Validações de formulário
- [ ] Hash de senha
- [ ] Sistema de sessão
- [ ] Redirecionamento pós-cadastro

### 2.2 Integração com Stripe (4-5 dias)
- [ ] Instalar Stripe PHP SDK
- [ ] Criar Stripe_lib
- [ ] Controller: Checkout
- [ ] Criar sessão de checkout
- [ ] Webhook para confirmação
- [ ] Gravar assinatura no banco
- [ ] Testar pagamentos teste

### 2.3 CRUD de Imóveis (5-6 dias)
- [ ] Model: Imovel_model
- [ ] Controller: Imoveis
- [ ] Criar imóvel
- [ ] Listar imóveis do corretor
- [ ] Editar imóvel
- [ ] Excluir imóvel
- [ ] Upload de imagens (opcional)
- [ ] Filtros e busca

### 2.4 Painel Administrativo (3-4 dias)
- [ ] Controller: Admin
- [ ] Dashboard com estatísticas
- [ ] Gerenciar corretores
- [ ] Gerenciar assinaturas
- [ ] Relatórios
- [ ] Middleware de autenticação admin

---

## 📅 FASE 3: Desenvolvimento do Frontend (10-14 dias)

### 3.1 Dashboard do Corretor (3-4 dias)
- [ ] Layout base com Tailwind
- [ ] Sidebar responsiva
- [ ] Header com perfil
- [ ] Cards de estatísticas
- [ ] Listagem de imóveis
- [ ] Ações rápidas

### 3.2 Formulários (2-3 dias)
- [ ] Cadastro de corretor
- [ ] Login
- [ ] Checkout de planos
- [ ] Validação client-side

### 3.3 Cadastro de Imóveis (3-4 dias)
- [ ] Formulário completo
- [ ] Upload de imagens
- [ ] Preview
- [ ] Validações
- [ ] Feedback visual

### 3.4 Exibição de Imóveis (2-3 dias)
- [ ] Cards de imóveis
- [ ] Filtros
- [ ] Paginação
- [ ] Modal de detalhes
- [ ] Ações (editar/excluir)

---

## 📅 FASE 4: Testes e Deploy (6 dias)

### 4.1 Testes de Funcionalidade (2-3 dias)
- [ ] Testar cadastro
- [ ] Testar login/logout
- [ ] Testar checkout Stripe
- [ ] Testar CRUD imóveis
- [ ] Testar painel admin
- [ ] Corrigir bugs

### 4.2 Performance e Segurança (2 dias)
- [ ] Otimizar queries
- [ ] Validar inputs
- [ ] Proteção CSRF
- [ ] XSS prevention
- [ ] SQL injection prevention
- [ ] Teste de carga

### 4.3 Deploy (2 dias)
- [ ] Preparar ambiente cPanel
- [ ] Upload de arquivos
- [ ] Configurar banco produção
- [ ] Configurar .htaccess
- [ ] Testar em produção
- [ ] Documentação final

---

## 📊 Cronograma Estimado

| Fase | Duração | Data Início | Data Fim |
|------|---------|-------------|----------|
| Fase 1 | 2-4 dias | 18/10/2025 | 22/10/2025 |
| Fase 2 | 15-19 dias | 22/10/2025 | 10/11/2025 |
| Fase 3 | 10-14 dias | 10/11/2025 | 24/11/2025 |
| Fase 4 | 6 dias | 24/11/2025 | 30/11/2025 |
| **TOTAL** | **33-43 dias** | **18/10** | **30/11** |

---

## 🎯 Prioridades

### Alta Prioridade
1. ✅ Configuração inicial
2. 🔄 Banco de dados
3. Cadastro de usuário
4. Integração Stripe
5. CRUD de imóveis

### Média Prioridade
1. Dashboard corretor
2. Painel admin
3. Filtros e busca

### Baixa Prioridade
1. Upload de imagens
2. Relatórios avançados
3. Notificações

---

## 📁 Estrutura de Arquivos

```
conectcorretores/
├── application/
│   ├── config/
│   │   ├── database.php ✅
│   │   ├── config.php
│   │   ├── routes.php
│   │   └── autoload.php
│   ├── controllers/
│   │   ├── Auth.php
│   │   ├── Dashboard.php
│   │   ├── Imoveis.php
│   │   ├── Checkout.php
│   │   └── Admin.php
│   ├── models/
│   │   ├── User_model.php
│   │   ├── Imovel_model.php
│   │   ├── Plan_model.php
│   │   └── Subscription_model.php
│   ├── views/
│   │   ├── templates/
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── imoveis/
│   │   └── admin/
│   └── libraries/
│       └── Stripe_lib.php
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
└── database/
    └── schema.sql
```

---

## 🔧 Configurações Necessárias

### Stripe
- [ ] Criar conta Stripe
- [ ] Obter chaves API (test/live)
- [ ] Configurar webhooks
- [ ] Criar produtos/preços

### cPanel
- [ ] Domínio configurado
- [ ] SSL instalado
- [ ] PHP 7.4+
- [ ] MySQL configurado

---

## 📝 Notas de Desenvolvimento

### Boas Práticas
- ✅ Código comentado em português
- ✅ Padrão MVC
- ✅ Validações server-side
- ✅ Segurança (CSRF, XSS, SQL Injection)
- ✅ Responsividade mobile-first
- ✅ SEO otimizado

### Convenções
- Controllers: PascalCase
- Models: PascalCase + _model
- Views: snake_case
- Métodos: camelCase
- Variáveis: snake_case

---

**© 2025 Rafael Dias - doisr.com.br**
