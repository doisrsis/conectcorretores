<?php
/**
 * Dashboard - Tabler Layout
 * Autor: Rafael Dias - doisr.com.br
 * Data: 11/11/2025
 */

// Preparar dados para o layout
$data_layout = [
    'title' => 'Dashboard',
    'page' => 'dashboard',
    'page_header' => 'Dashboard',
    'page_pretitle' => 'Bem-vindo ao',
    'page_actions' => '
        <a href="' . base_url('imoveis/novo') . '" class="btn btn-primary d-none d-sm-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Novo Imóvel
        </a>
        <a href="' . base_url('imoveis/novo') . '" class="btn btn-primary d-sm-none btn-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
        </a>
    '
];

// Carregar helper de assinatura
$this->load->helper('subscription');
$status_plano = get_status_assinatura($user->id);

// Iniciar conteúdo
ob_start();
?>

<!-- Avisos de Plano -->
<?php if (!$status_plano->tem_plano): ?>
    <!-- Sem Plano -->
    <div class="alert alert-warning alert-important" role="alert">
        <div class="d-flex">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            </div>
            <div>
                <h4 class="alert-title">Você não possui um plano ativo</h4>
                <div class="text-secondary">Seus imóveis estão inativos e não aparecem nas buscas. Escolha um plano para ativá-los e começar a anunciar.</div>
                <div class="mt-3">
                    <a href="<?php echo base_url('planos'); ?>" class="btn btn-warning">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0l8 -11" /></svg>
                        Escolher Plano
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php elseif (isset($status_plano->plano_pendente) && $status_plano->plano_pendente): ?>
    <!-- Plano Pendente -->
    <div class="alert alert-danger alert-important" role="alert">
        <div class="d-flex">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
            </div>
            <div>
                <h4 class="alert-title">⚠️ Problema com o Pagamento - Ação Necessária</h4>
                <div class="text-secondary">
                    <p class="mb-1"><strong>Seu plano está em período de graça.</strong></p>
                    <p>Não conseguimos processar o pagamento da sua assinatura. Seus imóveis continuam ativos temporariamente, mas você precisa atualizar seu método de pagamento para evitar o cancelamento.</p>
                </div>
                <div class="mt-3 btn-list">
                    <a href="<?php echo base_url('planos/portal'); ?>" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                        Atualizar Pagamento
                    </a>
                    <a href="<?php echo base_url('suporte'); ?>" class="btn">Falar com Suporte</a>
                </div>
            </div>
        </div>
    </div>
<?php elseif (isset($status_plano->plano_expirando) && $status_plano->plano_expirando): ?>
    <!-- Plano Expirando -->
    <div class="alert alert-info" role="alert">
        <div class="d-flex">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
            </div>
            <div>
                <h4 class="alert-title">Seu plano está próximo do vencimento</h4>
                <div class="text-secondary">
                    Seu plano <strong><?php echo $subscription->plan_nome; ?></strong> vence em <?php echo $status_plano->dias_restantes; ?> dias (<?php echo date('d/m/Y', strtotime($subscription->data_fim)); ?>).
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="row row-deck row-cards">
    <!-- Total de Imóveis -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total de Imóveis</div>
                </div>
                <div class="h1 mb-3"><?php echo isset($stats->total_imoveis) ? $stats->total_imoveis : 0; ?></div>
                <div class="d-flex mb-2">
                    <div>Ativos: <strong><?php echo isset($stats->imoveis_ativos) ? $stats->imoveis_ativos : 0; ?></strong></div>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-primary" style="width: <?php echo (isset($stats->total_imoveis) && $stats->total_imoveis > 0) ? (($stats->imoveis_ativos ?? 0) / $stats->total_imoveis * 100) : 0; ?>%" role="progressbar"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Imóveis Ativos -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Imóveis Ativos</div>
                    <div class="ms-auto lh-1">
                        <span class="badge bg-green"><?php echo isset($stats->imoveis_ativos) ? $stats->imoveis_ativos : 0; ?></span>
                    </div>
                </div>
                <div class="d-flex align-items-baseline">
                    <div class="h1 mb-0 me-2"><?php echo isset($stats->imoveis_ativos) ? $stats->imoveis_ativos : 0; ?></div>
                    <div class="me-auto">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                            <?php if (isset($stats->total_imoveis) && $stats->total_imoveis > 0): ?>
                                <?php echo round((($stats->imoveis_ativos ?? 0) / $stats->total_imoveis) * 100); ?>%
                            <?php else: ?>
                                0%
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Limite do Plano -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Limite do Plano</div>
                </div>
                <div class="h1 mb-3">
                    <?php if ($subscription && $subscription->plan_limite_imoveis > 0): ?>
                        <?php echo $subscription->plan_limite_imoveis; ?>
                    <?php else: ?>
                        <span class="text-muted">Ilimitado</span>
                    <?php endif; ?>
                </div>
                <?php if ($subscription && $subscription->plan_limite_imoveis > 0): ?>
                    <div class="d-flex mb-2">
                        <div>Usados: <strong><?php echo isset($stats->imoveis_ativos) ? $stats->imoveis_ativos : 0; ?></strong></div>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar <?php echo ((isset($stats->imoveis_ativos) ? $stats->imoveis_ativos : 0) / $subscription->plan_limite_imoveis) > 0.8 ? 'bg-red' : 'bg-blue'; ?>" 
                             style="width: <?php echo min(((isset($stats->imoveis_ativos) ? $stats->imoveis_ativos : 0) / $subscription->plan_limite_imoveis * 100), 100); ?>%" 
                             role="progressbar"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Plano Atual -->
    <div class="col-sm-6 col-lg-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Plano Atual</div>
                </div>
                <div class="h2 mb-2">
                    <?php if ($subscription): ?>
                        <?php echo $subscription->plan_nome; ?>
                    <?php else: ?>
                        <span class="text-muted">Sem Plano</span>
                    <?php endif; ?>
                </div>
                <?php if ($subscription): ?>
                    <div class="text-secondary">
                        Vence em <?php echo date('d/m/Y', strtotime($subscription->data_fim)); ?>
                    </div>
                <?php else: ?>
                    <a href="<?php echo base_url('planos'); ?>" class="btn btn-primary btn-sm mt-2">
                        Escolher Plano
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Seção de Últimos Imóveis removida - Estatísticas futuras serão adicionadas aqui -->

<?php
// Capturar conteúdo
$data_layout['content'] = ob_get_clean();

// Carregar layout
$this->load->view('templates/tabler/layout', $data_layout);
?>
