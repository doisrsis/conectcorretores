<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* ConectCorretores - Recuperação de Senha
* Autor: Rafael Dias - doisr.com.br
* Data: 11/11/2025
-->
<html lang="pt-BR">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Esqueci Minha Senha - ConectCorretores</title>
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
        <form class="card card-md" action="<?php echo base_url('password/send_reset'); ?>" method="post" autocomplete="off">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Esqueceu sua senha?</h2>
            <p class="text-secondary mb-4">Digite seu endereço de e-mail e enviaremos instruções para redefinir sua senha.</p>
            
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <div class="d-flex">
                <div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                </div>
                <div><?php echo $this->session->flashdata('success'); ?></div>
              </div>
              <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php endif; ?>
            
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
            
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
            </div>
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                Enviar instruções
              </button>
            </div>
          </div>
        </form>
        <div class="text-center text-secondary mt-3">
          Lembrou sua senha? <a href="<?php echo base_url('login'); ?>">Voltar para login</a>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
  </body>
</html>
