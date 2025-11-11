# 📋 PLANO DE ADAPTAÇÃO - TABLER DASHBOARD

**Projeto:** ConectCorretores  
**Template:** Tabler v1.0 (MIT License)  
**Autor:** Rafael Dias - doisr.com.br  
**Data:** 11/11/2025  

---

## 🎯 OBJETIVO

Migrar o sistema ConectCorretores do design atual para o template Tabler, mantendo todas as funcionalidades existentes e melhorando significativamente a experiência do usuário.

---

## 📊 ANÁLISE DA ESTRUTURA ATUAL

### **Views Existentes:**
```
application/views/
├── admin/ (25 arquivos)
│   ├── dashboard/
│   ├── settings/
│   ├── users/
│   └── ...
├── auth/ (2 arquivos)
│   ├── login.php
│   └── register.php
├── dashboard/ (2 arquivos)
├── emails/ (18 arquivos)
├── imoveis/ (5 arquivos)
│   ├── index.php
│   ├── ver.php
│   ├── novo.php
│   ├── editar.php
│   └── confirmacao.php
├── planos/ (5 arquivos)
├── password/ (2 arquivos)
└── templates/ (4 arquivos)
```

### **Assets Atuais:**
```
assets/
├── css/
├── js/
├── images/
└── ...
```

---

## 🏗️ ESTRUTURA DO TABLER

### **Arquivos Principais:**
```
tabler-main/
├── preview/pages/ (100+ páginas de exemplo)
│   ├── index.html (dashboard)
│   ├── sign-in.html
│   ├── sign-up.html
│   ├── profile.html
│   ├── settings.html
│   ├── users.html
│   ├── tables.html
│   ├── forms.html
│   └── ...
├── src/
│   ├── js/ (JavaScript)
│   └── scss/ (Estilos)
└── static/ (Assets compilados)
```

---

## 📦 FASE 1: PREPARAÇÃO (DIA 1 - MANHÃ)

### **1.1 Criar Estrutura de Assets**
```
assets/
├── tabler/
│   ├── css/
│   │   └── tabler.min.css
│   ├── js/
│   │   └── tabler.min.js
│   ├── img/
│   │   ├── tabler.svg (logo)
│   │   └── illustrations/
│   └── fonts/
└── custom/
    ├── css/
    │   └── conectcorretores.css (customizações)
    └── js/
        └── conectcorretores.js (scripts custom)
```

**Ações:**
- [ ] Criar pasta `assets/tabler/`
- [ ] Copiar CSS compilado do Tabler
- [ ] Copiar JS compilado do Tabler
- [ ] Copiar imagens e ícones
- [ ] Criar arquivo de customizações

### **1.2 Criar Templates Base**
```
application/views/templates/
├── tabler/
│   ├── header.php (head, navbar)
│   ├── sidebar.php (menu lateral)
│   ├── footer.php (rodapé, scripts)
│   └── layout.php (wrapper completo)
```

**Ações:**
- [ ] Criar estrutura de templates
- [ ] Adaptar header do Tabler
- [ ] Adaptar sidebar do Tabler
- [ ] Adaptar footer do Tabler
- [ ] Criar helper para carregar templates

---

## 🎨 FASE 2: LAYOUT BASE (DIA 1 - TARDE)

### **2.1 Header/Navbar**

**Elementos:**
- Logo ConectCorretores
- Menu de navegação
- Busca global
- Notificações
- Perfil do usuário
- Dark mode toggle

**Arquivo:** `application/views/templates/tabler/header.php`

**Referência Tabler:** `preview/pages/index.html` (linhas 1-50)

**Customizações:**
```php
<!-- Logo -->
<a href="<?php echo base_url(); ?>" class="navbar-brand">
    <img src="<?php echo base_url('assets/images/logo.svg'); ?>" alt="ConectCorretores">
</a>

<!-- User Menu -->
<div class="nav-item dropdown">
    <a href="#" class="nav-link d-flex lh-1" data-bs-toggle="dropdown">
        <span class="avatar"><?php echo substr($this->session->userdata('nome'), 0, 2); ?></span>
        <div class="d-none d-xl-block ps-2">
            <div><?php echo $this->session->userdata('nome'); ?></div>
            <div class="mt-1 small text-muted"><?php echo $this->session->userdata('email'); ?></div>
        </div>
    </a>
    <div class="dropdown-menu">
        <a href="<?php echo base_url('perfil'); ?>" class="dropdown-item">Perfil</a>
        <a href="<?php echo base_url('configuracoes'); ?>" class="dropdown-item">Configurações</a>
        <div class="dropdown-divider"></div>
        <a href="<?php echo base_url('logout'); ?>" class="dropdown-item">Sair</a>
    </div>
</div>
```

### **2.2 Sidebar/Menu Lateral**

**Estrutura do Menu:**
```
📊 Dashboard
🏠 Imóveis
   ├── Listar Todos
   ├── Adicionar Novo
   └── Categorias
💳 Planos
   ├── Meu Plano
   ├── Assinar Plano
   └── Histórico
📧 Emails
👥 Usuários (admin)
⚙️ Configurações (admin)
   ├── Geral
   ├── Email
   ├── Pagamentos
   └── Cupons
```

**Arquivo:** `application/views/templates/tabler/sidebar.php`

**Referência Tabler:** `preview/pages/index.html` (sidebar)

**Customizações:**
```php
<aside class="navbar navbar-vertical navbar-expand-lg">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <!-- Dashboard -->
            <li class="nav-item <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('dashboard'); ?>">
                    <span class="nav-link-icon">📊</span>
                    <span class="nav-link-title">Dashboard</span>
                </a>
            </li>
            
            <!-- Imóveis -->
            <li class="nav-item dropdown <?php echo ($page == 'imoveis') ? 'active' : ''; ?>">
                <a class="nav-link dropdown-toggle" href="#navbar-imoveis" data-bs-toggle="dropdown">
                    <span class="nav-link-icon">🏠</span>
                    <span class="nav-link-title">Imóveis</span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo base_url('imoveis'); ?>">
                        Listar Todos
                    </a>
                    <a class="dropdown-item" href="<?php echo base_url('imoveis/novo'); ?>">
                        Adicionar Novo
                    </a>
                </div>
            </li>
            
            <!-- Admin Only -->
            <?php if ($this->session->userdata('role') === 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('admin/users'); ?>">
                    <span class="nav-link-icon">👥</span>
                    <span class="nav-link-title">Usuários</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</aside>
```

### **2.3 Footer**

**Elementos:**
- Copyright
- Links úteis
- Versão do sistema
- Scripts JS

**Arquivo:** `application/views/templates/tabler/footer.php`

---

## 📄 FASE 3: PÁGINAS DE AUTENTICAÇÃO (DIA 2 - MANHÃ)

### **3.1 Login**

**Arquivo Atual:** `application/views/auth/login.php`  
**Referência Tabler:** `preview/pages/sign-in.html`

**Elementos:**
- Logo centralizado
- Formulário de login
- Link "Esqueci minha senha"
- Link "Criar conta"
- Validação de erros

**Melhorias:**
- [ ] Design moderno e limpo
- [ ] Animações suaves
- [ ] Feedback visual de erros
- [ ] Loading state no botão

### **3.2 Registro**

**Arquivo Atual:** `application/views/auth/register.php`  
**Referência Tabler:** `preview/pages/sign-up.html`

**Elementos:**
- Formulário de cadastro
- Validação em tempo real
- Termos de uso
- Link para login

### **3.3 Recuperação de Senha**

**Arquivo Atual:** `application/views/password/forgot.php`  
**Referência Tabler:** `preview/pages/forgot-password.html`

---

## 📊 FASE 4: DASHBOARD (DIA 2 - TARDE)

### **4.1 Dashboard Principal**

**Arquivo Atual:** `application/views/dashboard/index.php`  
**Referência Tabler:** `preview/pages/index.html`

**Cards de Estatísticas:**
```php
<div class="row row-deck row-cards">
    <!-- Total de Imóveis -->
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total de Imóveis</div>
                </div>
                <div class="h1 mb-3"><?php echo $total_imoveis; ?></div>
                <div class="d-flex mb-2">
                    <div>Ativos: <?php echo $imoveis_ativos; ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mais cards... -->
</div>
```

**Gráficos:**
- Imóveis por tipo
- Imóveis por cidade
- Evolução mensal
- Taxa de conversão

**Referência Tabler:** `preview/pages/charts.html`

---

## 🏠 FASE 5: MÓDULO DE IMÓVEIS (DIA 3)

### **5.1 Listagem de Imóveis**

**Arquivo Atual:** `application/views/imoveis/index.php`  
**Referência Tabler:** `preview/pages/cards.html` + `preview/pages/datatables.html`

**Layout:**
- Grid de cards (3 colunas)
- Filtros laterais
- Busca avançada
- Paginação
- Badges de status coloridos

**Melhorias:**
- [ ] Cards mais bonitos
- [ ] Hover effects
- [ ] Quick actions
- [ ] Skeleton loading

### **5.2 Visualizar Imóvel**

**Arquivo Atual:** `application/views/imoveis/ver.php`  
**Referência Tabler:** `preview/pages/profile.html`

**Elementos:**
- Galeria de imagens (lightbox)
- Informações detalhadas
- Mapa de localização
- Botões de ação
- Histórico de alterações

### **5.3 Formulário de Imóvel**

**Arquivo Atual:** `application/views/imoveis/novo.php` e `editar.php`  
**Referência Tabler:** `preview/pages/form-elements.html`

**Melhorias:**
- [ ] Wizard multi-step
- [ ] Upload de imagens com preview
- [ ] Validação em tempo real
- [ ] Autocomplete de endereço
- [ ] Máscaras de input

### **5.4 Confirmação de Validação**

**Arquivo Atual:** `application/views/imoveis/confirmacao.php`  
**Referência Tabler:** `preview/pages/empty.html`

**Manter:** Design atual está bom, apenas ajustar cores

---

## 💳 FASE 6: MÓDULO DE PLANOS (DIA 3 - TARDE)

### **6.1 Página de Planos**

**Arquivo Atual:** `application/views/planos/index.php`  
**Referência Tabler:** `preview/pages/pricing.html`

**Elementos:**
- Cards de planos
- Comparação de features
- Botões de ação
- Badge "Mais Popular"

### **6.2 Checkout**

**Arquivo Atual:** `application/views/planos/checkout.php`  
**Referência Tabler:** `preview/pages/payment-providers.html`

---

## ⚙️ FASE 7: ÁREA ADMINISTRATIVA (DIA 4)

### **7.1 Usuários**

**Arquivo Atual:** `application/views/admin/users/index.php`  
**Referência Tabler:** `preview/pages/users.html`

**Elementos:**
- Tabela de usuários
- Filtros e busca
- Ações rápidas
- Modal de edição

### **7.2 Configurações**

**Arquivo Atual:** `application/views/admin/settings/*.php`  
**Referência Tabler:** `preview/pages/settings.html`

**Abas:**
- Geral
- Email
- Pagamentos
- Cupons
- Notificações

---

## 🎨 FASE 8: CUSTOMIZAÇÕES (DIA 4 - TARDE)

### **8.1 Cores e Branding**

**Arquivo:** `assets/custom/css/conectcorretores.css`

```css
:root {
    --tblr-primary: #667eea; /* Cor principal */
    --tblr-secondary: #764ba2; /* Cor secundária */
    --tblr-success: #10b981;
    --tblr-danger: #ef4444;
    --tblr-warning: #f59e0b;
    --tblr-info: #3b82f6;
}

/* Logo */
.navbar-brand img {
    max-height: 40px;
}

/* Cards customizados */
.card-imovel {
    transition: transform 0.2s;
}

.card-imovel:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* Badges personalizados */
.badge-disponivel { background: var(--tblr-success); }
.badge-vendido { background: var(--tblr-info); }
.badge-alugado { background: var(--tblr-purple); }
.badge-inativo { background: var(--tblr-secondary); }
```

### **8.2 JavaScript Customizado**

**Arquivo:** `assets/custom/js/conectcorretores.js`

```javascript
// Máscaras de input
document.addEventListener('DOMContentLoaded', function() {
    // Máscara de telefone
    const telefoneInputs = document.querySelectorAll('[data-mask="telefone"]');
    telefoneInputs.forEach(input => {
        IMask(input, {
            mask: '(00) 00000-0000'
        });
    });
    
    // Máscara de CPF
    const cpfInputs = document.querySelectorAll('[data-mask="cpf"]');
    cpfInputs.forEach(input => {
        IMask(input, {
            mask: '000.000.000-00'
        });
    });
    
    // Máscara de CEP
    const cepInputs = document.querySelectorAll('[data-mask="cep"]');
    cepInputs.forEach(input => {
        IMask(input, {
            mask: '00000-000'
        });
    });
});

// Confirmação de ações
function confirmarAcao(mensagem) {
    return confirm(mensagem);
}

// Toast notifications
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible`;
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.page-wrapper').prepend(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}
```

---

## 📧 FASE 9: EMAILS (MANTER ATUAL)

**Decisão:** Manter templates de email atuais, pois já estão funcionando bem.

**Arquivos:**
- `application/views/emails/*.php`

**Ação:** Apenas ajustar cores para combinar com novo branding.

---

## ✅ FASE 10: TESTES E AJUSTES FINAIS (DIA 5)

### **10.1 Checklist de Testes**

**Responsividade:**
- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

**Navegadores:**
- [ ] Chrome
- [ ] Firefox
- [ ] Edge
- [ ] Safari

**Funcionalidades:**
- [ ] Login/Logout
- [ ] CRUD de imóveis
- [ ] Upload de imagens
- [ ] Filtros e busca
- [ ] Paginação
- [ ] Formulários
- [ ] Validações
- [ ] Notificações
- [ ] Emails

**Performance:**
- [ ] Tempo de carregamento < 2s
- [ ] Imagens otimizadas
- [ ] CSS/JS minificados
- [ ] Cache configurado

### **10.2 Ajustes Finais**

- [ ] Corrigir bugs encontrados
- [ ] Ajustar espaçamentos
- [ ] Melhorar animações
- [ ] Otimizar imagens
- [ ] Documentar mudanças

---

## 📦 ESTRUTURA FINAL DE ARQUIVOS

```
conectcorretores/
├── application/
│   └── views/
│       ├── templates/
│       │   └── tabler/
│       │       ├── header.php
│       │       ├── sidebar.php
│       │       ├── footer.php
│       │       └── layout.php
│       ├── auth/
│       │   ├── login.php (NOVO)
│       │   └── register.php (NOVO)
│       ├── dashboard/
│       │   └── index.php (NOVO)
│       ├── imoveis/
│       │   ├── index.php (NOVO)
│       │   ├── ver.php (NOVO)
│       │   ├── novo.php (NOVO)
│       │   └── editar.php (NOVO)
│       ├── planos/
│       │   └── index.php (NOVO)
│       └── admin/
│           ├── users/
│           └── settings/
├── assets/
│   ├── tabler/
│   │   ├── css/
│   │   │   └── tabler.min.css
│   │   ├── js/
│   │   │   └── tabler.min.js
│   │   └── img/
│   └── custom/
│       ├── css/
│       │   └── conectcorretores.css
│       └── js/
│           └── conectcorretores.js
└── docs/
    └── PLANO_ADAPTACAO_TABLER.md (este arquivo)
```

---

## 🎯 CRONOGRAMA DETALHADO

### **DIA 1 (11/11/2025) - SEGUNDA**
- **Manhã (4h):**
  - ✅ Download e análise do Tabler
  - ✅ Criação do plano de adaptação
  - [ ] Setup da estrutura de assets
  - [ ] Criação dos templates base

- **Tarde (4h):**
  - [ ] Adaptação do header
  - [ ] Adaptação do sidebar
  - [ ] Adaptação do footer
  - [ ] Teste do layout base

### **DIA 2 (12/11/2025) - TERÇA**
- **Manhã (4h):**
  - [ ] Página de login
  - [ ] Página de registro
  - [ ] Página de recuperação de senha
  - [ ] Testes de autenticação

- **Tarde (4h):**
  - [ ] Dashboard principal
  - [ ] Cards de estatísticas
  - [ ] Gráficos
  - [ ] Widgets

### **DIA 3 (13/11/2025) - QUARTA**
- **Manhã (4h):**
  - [ ] Listagem de imóveis
  - [ ] Visualização de imóvel
  - [ ] Filtros e busca

- **Tarde (4h):**
  - [ ] Formulário de novo imóvel
  - [ ] Formulário de edição
  - [ ] Upload de imagens
  - [ ] Página de planos

### **DIA 4 (14/11/2025) - QUINTA**
- **Manhã (4h):**
  - [ ] Área administrativa
  - [ ] Gestão de usuários
  - [ ] Configurações

- **Tarde (4h):**
  - [ ] Customizações CSS
  - [ ] JavaScript customizado
  - [ ] Ajustes de branding

### **DIA 5 (15/11/2025) - SEXTA**
- **Manhã (4h):**
  - [ ] Testes de responsividade
  - [ ] Testes de navegadores
  - [ ] Testes de funcionalidades

- **Tarde (4h):**
  - [ ] Correção de bugs
  - [ ] Otimizações
  - [ ] Documentação
  - [ ] Deploy

---

## 📝 NOTAS IMPORTANTES

### **Manter Funcionalidades:**
- ✅ Sistema de autenticação
- ✅ CRUD de imóveis
- ✅ Sistema de planos/assinaturas
- ✅ Integração com Stripe
- ✅ Sistema de emails
- ✅ Validação de imóveis (60 dias)
- ✅ Cron jobs
- ✅ Permissões (admin/corretor)

### **Melhorias de UX:**
- ✅ Feedback visual em todas as ações
- ✅ Loading states
- ✅ Animações suaves
- ✅ Tooltips informativos
- ✅ Validação em tempo real
- ✅ Mensagens de erro claras

### **Performance:**
- ✅ CSS/JS minificados
- ✅ Imagens otimizadas
- ✅ Lazy loading
- ✅ Cache de assets

### **Acessibilidade:**
- ✅ Contraste adequado
- ✅ Navegação por teclado
- ✅ Labels descritivos
- ✅ ARIA attributes

---

## 🚀 PRÓXIMOS PASSOS

1. **Revisar este plano** e aprovar
2. **Criar branch** `feature/tabler-integration`
3. **Iniciar Fase 1** (Setup)
4. **Commits frequentes** a cada fase
5. **Testes contínuos** durante desenvolvimento
6. **Deploy em staging** antes de produção

---

## 📞 SUPORTE

**Dúvidas ou sugestões?**
- Documentação Tabler: https://tabler.io/docs
- Preview Tabler: https://preview.tabler.io/
- GitHub Tabler: https://github.com/tabler/tabler

---

**Status:** 📋 Planejamento Completo  
**Próximo:** 🚀 Iniciar Fase 1  
**Estimativa Total:** 5 dias (40 horas)
