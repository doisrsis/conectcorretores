<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Planos e Preços - ConectCorretores</title>
    
    <!-- CSS files -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler-vendors.min.css" rel="stylesheet"/>
    
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

<body>
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md navbar-light d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="<?php echo base_url(); ?>">
                        <img src="<?php echo base_url('assets/images/logo.svg'); ?>" width="110" height="32" alt="ConectCorretores" class="navbar-brand-image">
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <a href="<?php echo base_url('login'); ?>" class="btn btn-outline-primary me-2">
                        Entrar
                    </a>
                    <a href="<?php echo base_url('register'); ?>" class="btn btn-primary">
                        Cadastrar
                    </a>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="page-wrapper">
            <div class="container-xl">
            
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                Planos e Preços
                            </h2>
                            <div class="text-muted mt-1">
                                Teste grátis por 7 dias e escolha o plano ideal para gerenciar seus imóveis de forma profissional
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-cards">
                        <?php foreach ($plans as $index => $plan): ?>
                            <div class="col-lg-4">
                                <div class="card card-md">
                                    <?php if ($plan->nome === 'Profissional'): ?>
                                        <div class="ribbon ribbon-top bg-yellow">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body text-center">
                                        <div class="text-uppercase text-muted font-weight-medium"><?php echo $plan->nome; ?></div>
                                        <div class="display-5 fw-bold my-3">R$ <?php echo number_format($plan->preco, 0, ',', '.'); ?></div>
                                        <ul class="list-unstyled lh-lg">
                                            <li><strong><?php echo $plan->limite_imoveis ? $plan->limite_imoveis . ' imóveis' : 'Imóveis ilimitados'; ?></strong></li>
                                            <li>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                Painel completo
                                            </li>
                                            <li>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                Suporte por email
                                            </li>
                                            <?php if ($plan->nome !== 'Básico'): ?>
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                    Relatórios avançados
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($plan->nome === 'Premium'): ?>
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                    Suporte prioritário
                                                </li>
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                    API de integração
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                        <div class="text-center mt-4">
                                            <a href="<?php echo base_url('register'); ?>" class="btn btn-<?php echo $plan->nome === 'Profissional' ? 'primary' : ''; ?> w-100">Escolher plano</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
            </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer footer-transparent d-print-none">
            <div class="container-xl">
                <div class="row text-center align-items-center flex-row-reverse">
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                        <ul class="list-inline list-inline-dots mb-0">
                            <li class="list-inline-item">
                                Copyright &copy; <?php echo date('Y'); ?>
                                <a href="https://doisr.com.br" class="link-secondary">Rafael Dias - doisr.com.br</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Libs JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js" defer></script>
</body>
</html>
