<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
* 
* ConectCorretores - Login
* Autor: Rafael Dias - doisr.com.br
* Data: 11/11/2025
-->
<html lang="pt-BR">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Login - ConectCorretores</title>
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
        <div class="card card-md">
          <div class="card-body">
            <h2 class="h2 text-center mb-4">Entre na sua conta</h2>
            
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
            
            <form action="<?php echo base_url('login'); ?>" method="post" autocomplete="off" novalidate>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="seu@email.com" autocomplete="off" value="<?php echo set_value('email'); ?>" required>
              </div>
              <div class="mb-2">
                <label class="form-label">
                  Senha
                  <span class="form-label-description">
                    <a href="<?php echo base_url('esqueci-senha'); ?>">Esqueci minha senha</a>
                  </span>
                </label>
                <div class="input-group input-group-flat">
                  <input type="password" name="senha" class="form-control" placeholder="Sua senha" autocomplete="off" required>
                  <span class="input-group-text">
                    <a href="#" class="link-secondary" title="Mostrar senha" data-bs-toggle="tooltip" onclick="togglePassword(event)">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                    </a>
                  </span>
                </div>
              </div>
              <div class="mb-2">
                <label class="form-check">
                  <input type="checkbox" class="form-check-input" name="lembrar"/>
                  <span class="form-check-label">Lembrar-me neste dispositivo</span>
                </label>
              </div>
              <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
              </div>
            </form>
          </div>
          <div class="hr-text">ou</div>
          <div class="card-body">
            <div class="row">
              <div class="col">
                <a href="<?php echo base_url('register'); ?>" class="btn w-100">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                  Criar nova conta
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center text-secondary mt-3">
          Não tem uma conta? <a href="<?php echo base_url('register'); ?>" tabindex="-1">Cadastre-se gratuitamente</a>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
    <script>
      function togglePassword(e) {
        e.preventDefault();
        const input = e.target.closest('.input-group').querySelector('input');
        if (input.type === 'password') {
          input.type = 'text';
        } else {
          input.type = 'password';
        }
      }
    </script>
  </body>
</html>
