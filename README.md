# 🏢 ConectCorretores

> Sistema SaaS completo para gestão de imóveis e corretores de imóveis

[![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/doisrsis/conectcorretores)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4.svg)](https://www.php.net/)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3.1.13-EE4623.svg)](https://codeigniter.com/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

---

## 📋 Sobre o Projeto

**ConectCorretores** é uma plataforma SaaS moderna e completa para corretores de imóveis gerenciarem seus anúncios, clientes e negócios de forma profissional e eficiente.

### ✨ Principais Funcionalidades

- 🏠 **Gestão Completa de Imóveis** - CRUD completo com fotos, detalhes e localização
- 👥 **Sistema de Usuários** - Corretores e administradores com permissões
- 💳 **Planos e Assinaturas** - Sistema de monetização com diferentes níveis
- 📊 **Dashboard Intuitivo** - Estatísticas e métricas em tempo real
- 🔐 **Autenticação Segura** - Login, registro e recuperação de senha
- 📱 **Design Responsivo** - Interface moderna com Tailwind CSS
- 🎨 **UI/UX Moderna** - Componentes interativos com Alpine.js
- 🔍 **Busca e Filtros** - Sistema avançado de pesquisa de imóveis
- 📈 **Painel Admin** - Gerenciamento completo do sistema

---

## 🚀 Tecnologias

### Backend
- **PHP 8.3** - Linguagem de programação
- **CodeIgniter 3.1.13** - Framework PHP MVC
- **MySQL 8.0** - Banco de dados relacional

### Frontend
- **Tailwind CSS** - Framework CSS utility-first
- **Alpine.js** - Framework JavaScript reativo
- **Lucide Icons** - Biblioteca de ícones moderna

### Ferramentas
- **XAMPP** - Ambiente de desenvolvimento local
- **Git** - Controle de versão
- **Composer** - Gerenciador de dependências PHP

---

## 📦 Instalação

### Pré-requisitos

- PHP >= 8.0
- MySQL >= 5.7
- Apache com mod_rewrite habilitado
- Composer (opcional)

### Passo a Passo

1. **Clone o repositório**
```bash
git clone https://github.com/doisrsis/conectcorretores.git
cd conectcorretores
```

2. **Configure o banco de dados**
```bash
# Crie o banco de dados
mysql -u root -p -e "CREATE DATABASE conectcorretores CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importe o schema
mysql -u root -p conectcorretores < database/schema.sql
```

3. **Configure o ambiente**
```bash
# Copie os arquivos de exemplo
cp application/config/database.example.php application/config/database.php
cp application/config/config.example.php application/config/config.php

# Edite as configurações
# - database.php: Configure suas credenciais do MySQL
# - config.php: Configure a base_url
```

4. **Configure o Apache**

Certifique-se de que o `mod_rewrite` está habilitado e o `.htaccess` está funcionando.

5. **Acesse o sistema**
```
http://localhost/conectcorretores
```

### Credenciais Padrão

**Administrador:**
- Email: `admin@conectcorretores.com`
- Senha: `password`

⚠️ **IMPORTANTE:** Altere a senha padrão após o primeiro login!

---

## 📁 Estrutura do Projeto

```
conectcorretores/
├── application/
│   ├── controllers/      # Controllers MVC
│   │   ├── Auth.php      # Autenticação
│   │   ├── Dashboard.php # Dashboard do corretor
│   │   ├── Admin.php     # Painel administrativo
│   │   ├── Imoveis.php   # Gestão de imóveis
│   │   └── Planos.php    # Gestão de planos
│   ├── models/           # Models MVC
│   │   ├── User_model.php
│   │   ├── Imovel_model.php
│   │   ├── Plan_model.php
│   │   └── Subscription_model.php
│   ├── views/            # Views MVC
│   │   ├── templates/    # Templates reutilizáveis
│   │   ├── auth/         # Páginas de autenticação
│   │   ├── dashboard/    # Dashboard do corretor
│   │   ├── admin/        # Painel admin
│   │   ├── imoveis/      # Páginas de imóveis
│   │   └── planos/       # Páginas de planos
│   └── config/           # Configurações
├── database/
│   └── schema.sql        # Schema do banco de dados
├── assets/               # Arquivos estáticos
├── uploads/              # Upload de imagens
└── system/               # Core do CodeIgniter

```

---

## 🎯 Funcionalidades Detalhadas

### 👤 Sistema de Autenticação
- ✅ Registro de novos corretores
- ✅ Login com email e senha
- ✅ Logout seguro
- ✅ Recuperação de senha (estrutura pronta)
- ✅ Proteção de rotas
- ✅ Sessões seguras

### 🏠 Gestão de Imóveis
- ✅ Cadastro completo de imóveis
- ✅ Upload de múltiplas fotos
- ✅ Edição e exclusão
- ✅ Ativar/Desativar anúncios
- ✅ Marcar como destaque
- ✅ Filtros avançados (tipo, cidade, preço)
- ✅ Busca por texto
- ✅ Paginação

### 💼 Painel do Corretor
- ✅ Dashboard com estatísticas
- ✅ Últimos imóveis cadastrados
- ✅ Status da assinatura
- ✅ Ações rápidas
- ✅ Edição de perfil
- ✅ Alteração de senha

### 🔐 Painel Administrativo
- ✅ Dashboard com métricas globais
- ✅ Gerenciamento de usuários
- ✅ Gerenciamento de planos
- ✅ Visualização de assinaturas
- ✅ Relatórios (estrutura pronta)
- ✅ Filtros e buscas

### 💳 Sistema de Planos
- ✅ Múltiplos planos (Básico, Profissional, Premium)
- ✅ Limites por plano
- ✅ Página pública de planos
- ✅ Escolha de plano (estrutura pronta)
- ✅ Cancelamento de assinatura

---

## 🛣️ Rotas Principais

### Públicas
```
GET  /                    # Landing page
GET  /login               # Página de login
POST /login               # Processar login
GET  /register            # Página de registro
POST /register            # Processar registro
GET  /planos              # Planos públicos
```

### Corretor (Autenticado)
```
GET  /dashboard           # Dashboard principal
GET  /perfil              # Ver/editar perfil
POST /perfil              # Atualizar perfil
GET  /imoveis             # Listar imóveis
GET  /imoveis/novo        # Cadastrar imóvel
POST /imoveis/novo        # Salvar imóvel
GET  /imoveis/ver/{id}    # Ver detalhes
GET  /imoveis/editar/{id} # Editar imóvel
POST /imoveis/editar/{id} # Atualizar imóvel
GET  /imoveis/deletar/{id}# Deletar imóvel
```

### Admin (Autenticado + Admin)
```
GET  /admin/dashboard     # Dashboard admin
GET  /admin/usuarios      # Gerenciar usuários
GET  /admin/assinaturas   # Ver assinaturas
GET  /admin/planos        # Gerenciar planos
GET  /admin/relatorios    # Relatórios
```

---

## 🎨 Design System

### Cores Principais
```css
Primary:   #3B82F6 (Blue)
Secondary: #10B981 (Green)
Danger:    #EF4444 (Red)
Warning:   #F59E0B (Orange)
```

### Componentes
- Cards com sombras suaves
- Botões com estados hover/active
- Formulários com validação visual
- Modais e alertas
- Tabelas responsivas
- Badges e tags
- Navegação lateral (sidebar)

---

## 🔒 Segurança

- ✅ Senhas hasheadas com `password_hash()`
- ✅ Proteção contra SQL Injection (Active Record)
- ✅ Proteção contra XSS (CodeIgniter)
- ✅ Proteção contra CSRF (CodeIgniter)
- ✅ Validação de formulários server-side
- ✅ Sessões seguras
- ✅ Controle de acesso por roles

---

## 📊 Banco de Dados

### Tabelas Principais

- **users** - Usuários do sistema
- **plans** - Planos de assinatura
- **subscriptions** - Assinaturas ativas
- **imoveis** - Imóveis cadastrados
- **imovel_fotos** - Fotos dos imóveis

### Relacionamentos

```
users (1) -----> (N) imoveis
users (1) -----> (1) subscriptions
plans (1) -----> (N) subscriptions
imoveis (1) ---> (N) imovel_fotos
```

---

## 🧪 Testes

```bash
# Executar testes (quando implementados)
./vendor/bin/phpunit
```

---

## 📈 Roadmap

### v1.0.0 (Atual) ✅
- [x] Sistema de autenticação
- [x] CRUD de imóveis
- [x] Dashboard do corretor
- [x] Painel administrativo
- [x] Sistema de planos
- [x] Design responsivo

### v1.1.0 (Próxima)
- [ ] Integração com gateway de pagamento
- [ ] Sistema de favoritos
- [ ] Compartilhamento em redes sociais
- [ ] Exportação de relatórios PDF
- [ ] Notificações por email

### v1.2.0 (Futuro)
- [ ] API REST
- [ ] App mobile (React Native)
- [ ] Chat em tempo real
- [ ] Agendamento de visitas
- [ ] CRM integrado

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Por favor:

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/NovaFuncionalidade`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/NovaFuncionalidade`)
5. Abra um Pull Request

---

## 📝 Changelog

Veja [CHANGELOG.md](CHANGELOG.md) para detalhes das versões.

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja [LICENSE](LICENSE) para mais detalhes.

---

## 👨‍💻 Autor

**Rafael Dias**
- Website: [doisr.com.br](https://doisr.com.br)
- GitHub: [@doisrsis](https://github.com/doisrsis)

---

## 🙏 Agradecimentos

- CodeIgniter Framework
- Tailwind CSS
- Alpine.js
- Comunidade Open Source

---

## 📞 Suporte

Para suporte, abra uma [issue](https://github.com/doisrsis/conectcorretores/issues) no GitHub.

---

<div align="center">

**Desenvolvido com ❤️ por [Rafael Dias](https://doisr.com.br)**

⭐ Se este projeto te ajudou, considere dar uma estrela!

</div>
