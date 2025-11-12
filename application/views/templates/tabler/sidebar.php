      <!-- Sidebar -->
      <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <h1 class="navbar-brand navbar-brand-autodark">
            <a href="<?php echo base_url(); ?>">
              <img src="<?php echo base_url('assets/tabler/img/logo_horizontal_branco.png'); ?>" width="180" height="180" alt="ConectCorretores" class="navbar-brand-image">
            </a>
          </h1>
          <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm" style="background-image: url(<?php echo base_url('assets/images/avatar-default.jpg'); ?>)"></span>
                <div class="d-none d-xl-block ps-2">
                  <div><?php echo $this->session->userdata('nome'); ?></div>
                  <div class="mt-1 small text-secondary"><?php echo ucfirst($this->session->userdata('role')); ?></div>
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
          <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
              <!-- Dashboard -->
              <li class="nav-item <?php echo (isset($page) && $page == 'dashboard') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('dashboard'); ?>" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                  </span>
                  <span class="nav-link-title">Dashboard</span>
                </a>
              </li>

              <!-- Imóveis -->
              <li class="nav-item dropdown <?php echo (isset($page) && $page == 'imoveis') ? 'active' : ''; ?>">
                <a class="nav-link dropdown-toggle" href="#navbar-imoveis" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M5 21v-14l8 -4v18" /><path d="M19 21v-10l-6 -4" /><path d="M9 9l0 .01" /><path d="M9 12l0 .01" /><path d="M9 15l0 .01" /><path d="M9 18l0 .01" /></svg>
                  </span>
                  <span class="nav-link-title">Imóveis</span>
                </a>
                <div class="dropdown-menu <?php echo (isset($page) && $page == 'imoveis') ? 'show' : ''; ?>">
                  <div class="dropdown-menu-columns">
                    <div class="dropdown-menu-column">
                      <a class="dropdown-item" href="<?php echo base_url('imoveis'); ?>">
                        Listar Todos
                      </a>
                      <a class="dropdown-item" href="<?php echo base_url('imoveis/novo'); ?>">
                        Adicionar Novo
                      </a>
                    </div>
                  </div>
                </div>
              </li>

              <!-- Planos -->
              <li class="nav-item <?php echo (isset($page) && $page == 'planos') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo base_url('planos'); ?>" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                  </span>
                  <span class="nav-link-title">Planos</span>
                </a>
              </li>

              <?php if ($this->session->userdata('role') === 'admin'): ?>
              <!-- Separador Admin -->
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#navbar-admin" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
                  </span>
                  <span class="nav-link-title">Administração</span>
                </a>
                <div class="dropdown-menu">
                  <div class="dropdown-menu-columns">
                    <div class="dropdown-menu-column">
                      <a class="dropdown-item" href="<?php echo base_url('admin/usuarios'); ?>">
                        Usuários
                      </a>
                      <a class="dropdown-item" href="<?php echo base_url('admin/logs'); ?>">
                        Logs de Atividade
                      </a>
                      <a class="dropdown-item" href="<?php echo base_url('settings'); ?>">
                        Configurações
                      </a>
                    </div>
                  </div>
                </div>
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </aside>
