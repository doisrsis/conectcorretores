<?php 
/**
 * Página de Planos - Usuários Logados
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 11/11/2025
 */
$this->load->view('templates/tabler/header'); 
?>

<div class="page">
    <?php $this->load->view('templates/tabler/sidebar'); ?>
    <?php $this->load->view('templates/tabler/navbar'); ?>
    
    <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            Assinaturas
                        </div>
                        <h2 class="page-title">
                            Planos e Preços
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page body -->
        <div class="page-body">
            <div class="container-xl">
                
                <!-- Mensagens -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                            </div>
                            <div>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
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
                            <div>
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        </div>
                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                <?php endif; ?>

                <!-- Assinatura Atual -->
                <?php if ($current_subscription): ?>
                    <?php if ($current_subscription->is_trial): ?>
                        <?php 
                            $days_left = ceil((strtotime($current_subscription->trial_ends_at) - time()) / 86400);
                            $is_expiring_soon = $days_left <= 3;
                        ?>
                        <!-- Trial Ativo -->
                        <div class="card mb-3 bg-<?php echo $is_expiring_soon ? 'warning' : 'info'; ?>-lt">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="fs-1 me-2">🎁</span>
                                            <div>
                                                <div class="text-muted small">Período de Teste Gratuito</div>
                                                <h3 class="mb-0"><?php echo $current_subscription->plan_nome; ?></h3>
                                            </div>
                                        </div>
                                        <div class="text-<?php echo $is_expiring_soon ? 'warning' : 'info'; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
                                            <?php if ($days_left > 1): ?>
                                                Restam <strong><?php echo $days_left; ?> dias</strong> de teste gratuito
                                            <?php elseif ($days_left == 1): ?>
                                                <strong>Último dia</strong> de teste gratuito!
                                            <?php else: ?>
                                                Seu teste expira <strong>hoje</strong>!
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            Expira em <?php echo date('d/m/Y', strtotime($current_subscription->trial_ends_at)); ?>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <div class="mb-3">
                                            <div class="text-muted small">Após o trial:</div>
                                            <div class="h2 mb-0">R$ <?php echo number_format($current_subscription->plan_preco, 2, ',', '.'); ?></div>
                                            <div class="text-muted">/<?php echo $current_subscription->plan_tipo; ?></div>
                                        </div>
                                        <a href="<?php echo base_url('planos/escolher/' . $current_subscription->plan_id); ?>" 
                                           class="btn btn-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                            Continuar com Plano Pago
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Assinatura Paga -->
                        <div class="card mb-3 bg-success-lt">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="avatar avatar-lg bg-success text-white me-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                            </span>
                                            <div>
                                                <div class="text-muted small">Plano Atual</div>
                                                <h3 class="mb-0"><?php echo $current_subscription->plan_nome; ?></h3>
                                            </div>
                                        </div>
                                        <?php if (isset($current_subscription->current_period_end) && $current_subscription->current_period_end): ?>
                                            <div class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
                                                Próxima cobrança em <?php echo date('d/m/Y', strtotime($current_subscription->current_period_end)); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                Assinatura ativa
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-auto text-end">
                                        <div class="h2 mb-1">R$ <?php echo number_format($current_subscription->plan_preco, 2, ',', '.'); ?></div>
                                        <div class="text-muted">/<?php echo $current_subscription->plan_tipo; ?></div>
                                        <a href="<?php echo base_url('planos/cancelar'); ?>" class="btn btn-outline-danger btn-sm mt-2">
                                            Cancelar Assinatura
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Planos -->
                <div class="row row-cards">
                    <?php foreach ($plans as $plan): ?>
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
                                        <?php if ($current_subscription && $current_subscription->plan_id == $plan->id): ?>
                                            <button class="btn btn-success w-100" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                Plano Atual
                                            </button>
                                        <?php else: ?>
                                            <a href="<?php echo base_url('planos/escolher/' . $plan->id); ?>" 
                                               class="btn btn-<?php echo $plan->nome === 'Profissional' ? 'primary' : ''; ?> w-100">
                                                <?php echo $current_subscription ? 'Mudar para este Plano' : 'Escolher plano'; ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <?php $this->load->view('templates/tabler/footer'); ?>
