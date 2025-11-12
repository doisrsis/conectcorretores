<?php
/**
 * Admin - Dashboard (Tabler)
 * 
 * @author Rafael Dias - doisr.com.br (11/11/2025 23:47)
 */

$this->load->view('templates/tabler/header');
$this->load->view('templates/tabler/navbar');
$this->load->view('templates/tabler/sidebar');
?>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Admin
                    </div>
                    <h2 class="page-title">
                        Dashboard Administrativo
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            
            <!-- Stats Cards -->
            <div class="row row-deck row-cards mb-3">
                <!-- Total de Corretores -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total de Corretores</div>
                                <div class="ms-auto lh-1">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle text-muted" href="<?php echo base_url('admin/usuarios?role=corretor'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-baseline">
                                <div class="h1 mb-0 me-2"><?php echo $stats->total_corretores; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total de Imóveis -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total de Imóveis</div>
                                <div class="ms-auto lh-1">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle text-muted" href="<?php echo base_url('imoveis'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-baseline">
                                <div class="h1 mb-0 me-2"><?php echo $stats->total_imoveis; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assinaturas Ativas -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Assinaturas Ativas</div>
                                <div class="ms-auto lh-1">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle text-muted" href="<?php echo base_url('admin/assinaturas?status=ativa'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-baseline">
                                <div class="h1 mb-0 me-2"><?php echo $stats->assinaturas_ativas; ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Receita Mensal -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Receita Mensal</div>
                            </div>
                            <div class="d-flex align-items-baseline">
                                <div class="h1 mb-0 me-2">R$ <?php echo number_format($stats->receita_mensal, 2, ',', '.'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listas -->
            <div class="row row-cards">
                <!-- Últimos Corretores -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Últimos Corretores</h3>
                            <div class="card-actions">
                                <a href="<?php echo base_url('admin/usuarios'); ?>" class="btn btn-sm btn-primary">
                                    Ver todos
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recent_users)): ?>
                                <div class="divide-y">
                                    <?php foreach ($recent_users as $user): ?>
                                        <div class="row py-2">
                                            <div class="col-auto">
                                                <span class="avatar"><?php echo strtoupper(substr($user->nome, 0, 2)); ?></span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong><?php echo $user->nome; ?></strong>
                                                </div>
                                                <div class="text-muted"><?php echo $user->email; ?></div>
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <span class="badge bg-<?php echo $user->ativo ? 'green' : 'red'; ?>-lt">
                                                    <?php echo $user->ativo ? 'Ativo' : 'Inativo'; ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty">
                                    <p class="empty-title">Nenhum corretor cadastrado</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Últimas Assinaturas -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Últimas Assinaturas</h3>
                            <div class="card-actions">
                                <a href="<?php echo base_url('admin/assinaturas'); ?>" class="btn btn-sm btn-primary">
                                    Ver todas
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recent_subscriptions)): ?>
                                <div class="divide-y">
                                    <?php foreach ($recent_subscriptions as $sub): ?>
                                        <div class="row py-2">
                                            <div class="col">
                                                <div class="text-truncate">
                                                    <strong><?php echo $sub->user_nome; ?></strong>
                                                </div>
                                                <div class="text-muted">
                                                    <?php echo $sub->plan_nome; ?> - R$ <?php echo number_format($sub->plan_preco, 2, ',', '.'); ?>
                                                </div>
                                                <div class="text-muted small">
                                                    Válido até <?php echo date('d/m/Y', strtotime($sub->data_fim)); ?>
                                                </div>
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <span class="badge bg-<?php echo $sub->status === 'ativa' ? 'green' : 'gray'; ?>-lt">
                                                    <?php echo ucfirst($sub->status); ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty">
                                    <p class="empty-title">Nenhuma assinatura</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php $this->load->view('templates/tabler/footer'); ?>
</div>
