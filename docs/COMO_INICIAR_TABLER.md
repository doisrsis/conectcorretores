# 🚀 COMO INICIAR A MIGRAÇÃO TABLER

**Guia Passo a Passo**  
**Autor:** Rafael Dias - doisr.com.br  
**Data:** 11/11/2025  

---

## ✅ PRÉ-REQUISITOS

Antes de começar, certifique-se de que você tem:

- [x] Tabler baixado (✅ Feito!)
- [x] Plano de adaptação criado (✅ Feito!)
- [x] Documentação revisada (✅ Feito!)
- [ ] Backup do código atual
- [ ] Branch Git criada
- [ ] Ambiente de teste pronto

---

## 📋 PASSO 1: BACKUP E GIT

### **1.1 Fazer Backup**

```powershell
# Criar backup completo
cd c:\xampp\htdocs
Compress-Archive -Path conectcorretores -DestinationPath "conectcorretores_backup_$(Get-Date -Format 'yyyyMMdd_HHmmss').zip"
```

### **1.2 Criar Branch**

```powershell
cd c:\xampp\htdocs\conectcorretores

# Criar e mudar para nova branch
git checkout -b feature/tabler-integration

# Verificar branch atual
git branch
```

### **1.3 Commit Inicial**

```powershell
# Adicionar documentação
git add docs/

# Commit
git commit -m "docs: Adicionar planejamento de migração Tabler

- Plano completo de 5 dias
- Comparação visual antes/depois
- Resumo executivo
- Guia de início

Preparação para migração do template Tabler Dashboard"

# Push da branch
git push -u origin feature/tabler-integration
```

---

## 📦 PASSO 2: ORGANIZAR ASSETS DO TABLER

### **2.1 Criar Estrutura de Pastas**

```powershell
# Criar pastas
New-Item -ItemType Directory -Force -Path "assets/tabler/css"
New-Item -ItemType Directory -Force -Path "assets/tabler/js"
New-Item -ItemType Directory -Force -Path "assets/tabler/img"
New-Item -ItemType Directory -Force -Path "assets/tabler/fonts"
New-Item -ItemType Directory -Force -Path "assets/custom/css"
New-Item -ItemType Directory -Force -Path "assets/custom/js"
```

### **2.2 Copiar Assets do Tabler**

**Opção A: Usar CDN (Mais Rápido - Recomendado para início)**

Não precisa copiar nada, apenas usar links CDN nos templates.

**Opção B: Hospedar Localmente (Melhor para produção)**

```powershell
# Copiar CSS
Copy-Item "tabler-temp/tabler-main/dist/css/tabler.min.css" -Destination "assets/tabler/css/"

# Copiar JS
Copy-Item "tabler-temp/tabler-main/dist/js/tabler.min.js" -Destination "assets/tabler/js/"

# Copiar imagens
Copy-Item "tabler-temp/tabler-main/dist/img/*" -Destination "assets/tabler/img/" -Recurse
```

---

## 🎨 PASSO 3: CRIAR TEMPLATES BASE

### **3.1 Criar Estrutura de Templates**

```powershell
# Criar pasta de templates Tabler
New-Item -ItemType Directory -Force -Path "application/views/templates/tabler"
```

### **3.2 Criar Header**

**Arquivo:** `application/views/templates/tabler/header.php`

```php
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?php echo isset($title) ? $title : 'ConectCorretores'; ?></title>
    
    <!-- CSS do Tabler via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet"/>
    
    <!-- CSS Customizado -->
    <link href="<?php echo base_url('assets/custom/css/conectcorretores.css'); ?>" rel="stylesheet"/>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>">
</head>
<body>
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md navbar-light d-print-none">
            <div class="container-xl">
                <!-- Logo -->
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="<?php echo base_url(); ?>">
                        <img src="<?php echo base_url('assets/images/logo.svg'); ?>" width="110" height="32" alt="ConectCorretores" class="navbar-brand-image">
                    </a>
                </h1>
                
                <!-- Menu Mobile -->
                <div class="navbar-nav flex-row order-md-last">
                    <!-- Notificações -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown">
                            <span class="avatar avatar-sm">
                                <?php echo strtoupper(substr($this->session->userdata('nome'), 0, 2)); ?>
                            </span>
                            <div class="d-none d-xl-block ps-2">
                                <div><?php echo $this->session->userdata('nome'); ?></div>
                                <div class="mt-1 small text-muted"><?php echo ucfirst($this->session->userdata('role')); ?></div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="<?php echo base_url('perfil'); ?>" class="dropdown-item">Perfil</a>
                            <a href="<?php echo base_url('configuracoes'); ?>" class="dropdown-item">Configurações</a>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo base_url('logout'); ?>" class="dropdown-item">Sair</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
```

### **3.3 Criar Sidebar**

**Arquivo:** `application/views/templates/tabler/sidebar.php`

```php
        <!-- Sidebar -->
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="<?php echo base_url(); ?>">
                        <img src="<?php echo base_url('assets/images/logo-white.svg'); ?>" width="110" height="32" alt="ConectCorretores">
                    </a>
                </h1>
                
                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        <!-- Dashboard -->
                        <li class="nav-item <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                            <a class="nav-link" href="<?php echo base_url('dashboard'); ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                                </span>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </li>
                        
                        <!-- Imóveis -->
                        <li class="nav-item dropdown <?php echo ($page == 'imoveis') ? 'active' : ''; ?>">
                            <a class="nav-link dropdown-toggle" href="#navbar-imoveis" data-bs-toggle="dropdown" role="button">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="21" x2="21" y2="21" /><path d="M5 21v-14l8 -4v18" /><path d="M19 21v-10l-6 -4" /><line x1="9" y1="9" x2="9" y2="9.01" /><line x1="9" y1="12" x2="9" y2="12.01" /><line x1="9" y1="15" x2="9" y2="15.01" /><line x1="9" y1="18" x2="9" y2="18.01" /></svg>
                                </span>
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
                        
                        <!-- Planos -->
                        <li class="nav-item <?php echo ($page == 'planos') ? 'active' : ''; ?>">
                            <a class="nav-link" href="<?php echo base_url('planos'); ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="3" /><line x1="3" y1="10" x2="21" y2="10" /><line x1="7" y1="15" x2="7.01" y2="15" /><line x1="11" y1="15" x2="13" y2="15" /></svg>
                                </span>
                                <span class="nav-link-title">Planos</span>
                            </a>
                        </li>
                        
                        <?php if ($this->session->userdata('role') === 'admin'): ?>
                        <!-- Admin -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-admin" data-bs-toggle="dropdown" role="button">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                </span>
                                <span class="nav-link-title">Admin</span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?php echo base_url('admin/users'); ?>">
                                    Usuários
                                </a>
                                <a class="dropdown-item" href="<?php echo base_url('settings'); ?>">
                                    Configurações
                                </a>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </aside>
```

### **3.4 Criar Footer**

**Arquivo:** `application/views/templates/tabler/footer.php`

```php
        <!-- Footer -->
        <footer class="footer footer-transparent d-print-none">
            <div class="container-xl">
                <div class="row text-center align-items-center flex-row-reverse">
                    <div class="col-lg-auto ms-lg-auto">
                        <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item">
                                <a href="<?php echo base_url('docs'); ?>" class="link-secondary">Documentação</a>
                            </li>
                            <li class="list-inline-item">
                                <a href="<?php echo base_url('suporte'); ?>" class="link-secondary">Suporte</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                        <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item">
                                Copyright &copy; <?php echo date('Y'); ?>
                                <a href="https://doisr.com.br" class="link-secondary" target="_blank">Rafael Dias - doisr.com.br</a>
                            </li>
                            <li class="list-inline-item">
                                <a href="<?php echo base_url('changelog'); ?>" class="link-secondary">
                                    v1.8.0
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- JS do Tabler via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
    
    <!-- JS Customizado -->
    <script src="<?php echo base_url('assets/custom/js/conectcorretores.js'); ?>"></script>
</body>
</html>
```

### **3.5 Criar Layout Wrapper**

**Arquivo:** `application/views/templates/tabler/layout.php`

```php
<?php $this->load->view('templates/tabler/header'); ?>
<?php $this->load->view('templates/tabler/sidebar'); ?>

<div class="page-wrapper">
    <!-- Page header -->
    <?php if (isset($page_header)): ?>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <?php echo $page_header; ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <?php echo $content; ?>
        </div>
    </div>
    
    <?php $this->load->view('templates/tabler/footer'); ?>
</div>
```

---

## 🎨 PASSO 4: CRIAR CSS CUSTOMIZADO

**Arquivo:** `assets/custom/css/conectcorretores.css`

```css
/**
 * ConectCorretores - Customizações Tabler
 * Autor: Rafael Dias - doisr.com.br
 * Data: 11/11/2025
 */

/* Cores do Brand */
:root {
    --tblr-primary: #667eea;
    --tblr-primary-rgb: 102, 126, 234;
    --tblr-secondary: #764ba2;
    --tblr-secondary-rgb: 118, 75, 162;
}

/* Logo */
.navbar-brand-image {
    max-height: 40px;
    width: auto;
}

/* Cards de Imóveis */
.card-imovel {
    transition: all 0.3s ease;
    cursor: pointer;
}

.card-imovel:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Badges de Status */
.badge-disponivel {
    background-color: #10b981;
    color: white;
}

.badge-inativo-tempo {
    background-color: #f59e0b;
    color: white;
}

.badge-vendido {
    background-color: #3b82f6;
    color: white;
}

.badge-alugado {
    background-color: #8b5cf6;
    color: white;
}

/* Animações */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}
```

---

## 💻 PASSO 5: CRIAR JS CUSTOMIZADO

**Arquivo:** `assets/custom/js/conectcorretores.js`

```javascript
/**
 * ConectCorretores - Scripts Customizados
 * Autor: Rafael Dias - doisr.com.br
 * Data: 11/11/2025
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('ConectCorretores - Tabler Integration v1.8.0');
    
    // Confirmação de ações
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    confirmButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // Toast notifications
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade-in`;
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.page-body .container-xl');
        if (container) {
            container.insertBefore(toast, container.firstChild);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }
    };
});
```

---

## ✅ PASSO 6: TESTAR LAYOUT BASE

### **6.1 Criar Página de Teste**

**Arquivo:** `application/controllers/Test_tabler.php`

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_tabler extends CI_Controller {
    
    public function index() {
        // Simular sessão
        $this->session->set_userdata([
            'logged_in' => true,
            'nome' => 'Rafael Dias',
            'email' => 'rafael@doisr.com.br',
            'role' => 'admin'
        ]);
        
        $data['title'] = 'Teste Tabler';
        $data['page'] = 'dashboard';
        $data['page_header'] = 'Teste do Layout Tabler';
        $data['content'] = '<div class="card"><div class="card-body"><h3>Layout Tabler funcionando!</h3><p>Se você está vendo esta mensagem, o layout base está configurado corretamente.</p></div></div>';
        
        $this->load->view('templates/tabler/layout', $data);
    }
}
```

### **6.2 Acessar Teste**

```
http://localhost/conectcorretores/test_tabler
```

**Verificar:**
- [ ] Header aparece corretamente
- [ ] Sidebar aparece corretamente
- [ ] Footer aparece corretamente
- [ ] CSS do Tabler carrega
- [ ] Menu funciona
- [ ] Responsivo funciona

---

## 📝 PASSO 7: COMMIT DO PROGRESSO

```powershell
# Adicionar arquivos
git add .

# Commit
git commit -m "feat: Implementar layout base do Tabler

- Criar estrutura de templates (header, sidebar, footer, layout)
- Adicionar assets do Tabler via CDN
- Criar CSS customizado com cores do brand
- Criar JS customizado com funções auxiliares
- Adicionar controller de teste

Fase 1 completa: Setup e Layout Base"

# Push
git push origin feature/tabler-integration
```

---

## 🎯 PRÓXIMOS PASSOS

Após completar estes passos, você terá:

✅ Layout base do Tabler funcionando  
✅ Templates criados e organizados  
✅ CSS e JS customizados  
✅ Estrutura pronta para adaptar páginas  

**Próximo:** Começar a adaptar as páginas seguindo o `PLANO_ADAPTACAO_TABLER.md`

---

## 🆘 PROBLEMAS COMUNS

### **CSS não carrega:**
- Verificar URL do CDN
- Verificar permissões de pasta
- Limpar cache do navegador

### **Sidebar não aparece:**
- Verificar se Bootstrap JS está carregando
- Verificar console do navegador
- Verificar estrutura HTML

### **Erro 404 em assets:**
- Verificar base_url() no config
- Verificar caminhos dos arquivos
- Verificar .htaccess

---

**Pronto para começar?** Siga os passos acima e depois continue com o plano de adaptação! 🚀
