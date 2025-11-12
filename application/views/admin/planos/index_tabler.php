<?php
/**
 * Admin - Gerenciar Planos (Tabler)
 *
 * @author Rafael Dias - doisr.com.br (12/11/2025 00:13)
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
                        Gerenciar Planos
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="<?php echo base_url('admin/planos_sincronizar'); ?>"
                           class="btn btn-outline-primary"
                           onclick="return confirm('Deseja sincronizar planos do Stripe?')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                            Sincronizar Stripe
                        </a>
                        <a href="<?php echo base_url('admin/planos_criar'); ?>" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Novo Plano
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
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
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
                    </div>
                    <div><?php echo $this->session->flashdata('error'); ?></div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="row row-deck row-cards mb-3">
                <div class="col-sm-6 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total de Planos</div>
                            </div>
                            <div class="h1 mb-0"><?php echo count($plans); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Planos Ativos</div>
                            </div>
                            <div class="h1 mb-0 text-green">
                                <?php echo count(array_filter($plans, function($p) { return $p->ativo == 1; })); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Produtos no Stripe</div>
                            </div>
                            <div class="h1 mb-0 text-purple"><?php echo count($stripe_products); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Planos -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Planos Cadastrados</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Plano</th>
                                <th>Preço</th>
                                <th>Tipo</th>
                                <th>Limite Imóveis</th>
                                <th>Stripe</th>
                                <th>Status</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($plans)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="empty">
                                            <div class="empty-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                                            </div>
                                            <p class="empty-title">Nenhum plano cadastrado</p>
                                            <p class="empty-subtitle text-muted">
                                                Crie um novo plano ou sincronize com o Stripe
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($plans as $plan): ?>
                                    <tr>
                                        <td>
                                            <div><strong><?php echo $plan->nome; ?></strong></div>
                                            <?php if ($plan->descricao): ?>
                                                <div class="text-muted small">
                                                    <?php echo substr($plan->descricao, 0, 50); ?><?php echo strlen($plan->descricao) > 50 ? '...' : ''; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong>R$ <?php echo number_format($plan->preco, 2, ',', '.'); ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-blue-lt"><?php echo ucfirst($plan->tipo); ?></span>
                                        </td>
                                        <td class="text-muted">
                                            <?php echo $plan->limite_imoveis ? $plan->limite_imoveis : 'Ilimitado'; ?>
                                        </td>
                                        <td>
                                            <?php if ($plan->stripe_product_id): ?>
                                                <span class="badge bg-purple-lt">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                    Sincronizado
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-gray-lt">Não sincronizado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($plan->ativo): ?>
                                                <span class="badge bg-green-lt">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge bg-red-lt">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-list flex-nowrap">
                                                <a href="<?php echo base_url('admin/planos_editar/' . $plan->id); ?>"
                                                   class="btn btn-sm btn-primary">
                                                    Editar
                                                </a>
                                                <a href="<?php echo base_url('admin/planos_excluir/' . $plan->id); ?>"
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Deseja realmente excluir este plano?')">
                                                    Excluir
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <?php $this->load->view('templates/tabler/footer'); ?>
</div>
