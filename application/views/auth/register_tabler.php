<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* ConectCorretores - Registro
* Autor: Rafael Dias - doisr.com.br
* Data: 11/11/2025
-->
<html lang="pt-BR">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Criar Conta - ConectCorretores</title>
    <!-- CSS do Tabler via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet"/>
    <!-- CSS Customizado -->
    <link href="<?php echo base_url('assets/custom/css/conectcorretores.css'); ?>" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body class="d-flex flex-column">
    <script src="<?php echo base_url('assets/custom/js/demo-theme.min.js'); ?>"></script>
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <a href="<?php echo base_url(); ?>" class="navbar-brand navbar-brand-autodark">
            <img src="<?php echo base_url('assets/tabler/img/logo_vertical.png'); ?>" height="80" alt="ConectCorretores">
          </a>
        </div>
        <form class="card card-md" action="<?php echo base_url('register'); ?>" method="post" autocomplete="off" novalidate>
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Criar nova conta</h2>
            
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <div class="d-flex">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
                </div>
                <div><?php echo $this->session->flashdata('error'); ?></div>
              </div>
              <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php endif; ?>
            
            <?php if (validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <div class="d-flex">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
                </div>
                <div><?php echo validation_errors(); ?></div>
              </div>
              <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php endif; ?>
            
            <div class="mb-3">
              <label class="form-label">Nome Completo</label>
              <input type="text" name="nome" class="form-control" placeholder="Seu nome completo" value="<?php echo set_value('nome'); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="seu@email.com" value="<?php echo set_value('email'); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Telefone/WhatsApp</label>
              <input type="tel" name="telefone" class="form-control" placeholder="(00) 00000-0000" value="<?php echo set_value('telefone'); ?>" data-mask="telefone">
            </div>
            <div class="mb-3">
              <label class="form-label">Senha</label>
              <div class="input-group input-group-flat">
                <input type="password" name="senha" class="form-control" placeholder="Sua senha" autocomplete="off" required>
                <span class="input-group-text">
                  <a href="#" class="link-secondary" title="Mostrar senha" data-bs-toggle="tooltip" onclick="togglePassword(event, 'senha')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                  </a>
                </span>
              </div>
              <small class="form-hint">Mínimo de 6 caracteres</small>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirmar Senha</label>
              <div class="input-group input-group-flat">
                <input type="password" name="confirmar_senha" class="form-control" placeholder="Confirme sua senha" autocomplete="off" required>
                <span class="input-group-text">
                  <a href="#" class="link-secondary" title="Mostrar senha" data-bs-toggle="tooltip" onclick="togglePassword(event, 'confirmar_senha')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                  </a>
                </span>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-check">
                <input type="checkbox" class="form-check-input" name="termos" required/>
                <span class="form-check-label">Concordo com os <a href="<?php echo base_url('termos'); ?>" tabindex="-1">termos de uso</a> e <a href="<?php echo base_url('privacidade'); ?>" tabindex="-1">política de privacidade</a>.</span>
              </label>
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">Criar conta</button>
            </div>
          </div>
        </form>
        <div class="text-center text-secondary mt-3">
          Já tem uma conta? <a href="<?php echo base_url('login'); ?>">Fazer login</a>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
    <script src="https://unpkg.com/imask"></script>
    <script>
      function togglePassword(e, fieldName) {
        e.preventDefault();
        const input = e.target.closest('.input-group').querySelector('input');
        if (input.type === 'password') {
          input.type = 'text';
        } else {
          input.type = 'password';
        }
      }
      
      // Máscara de telefone
      const telefoneInput = document.querySelector('[data-mask="telefone"]');
      if (telefoneInput) {
        IMask(telefoneInput, {
          mask: '(00) 00000-0000'
        });
      }
    </script>
  </body>
</html>
