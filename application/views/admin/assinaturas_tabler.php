<?php
/**
 * Admin - Gerenciar Assinaturas (Tabler)
 * 
 * @author Rafael Dias - doisr.com.br (12/11/2025 00:14)
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
                    <div class="page-pretitle">Admin</div>
                    <h2 class="page-title">Gerenciar Assinaturas</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            
            <!-- Filtros -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="get" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="ativa" <?php echo $this->input->get('status') === 'ativa' ? 'selected' : ''; ?>>Ativa</option>
                                <option value="cancelada" <?php echo $this->input->get('status') === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                                <option value="expirada" <?php echo $this->input->get('status') === 'expirada' ? 'selected' : ''; ?>>Expirada</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stats -->
            <div class="row row-cards mb-3">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="subheader">Total de Assinaturas</div>
                            <div class="h1 mb-0"><?php echo $total; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="subheader">Mostrando</div>
                            <div class="h1 mb-0"><?php echo min($per_page, $total - $offset); ?> de <?php echo $total; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela -->
            <?php if (!empty($subscriptions)): ?>
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Corretor</th>
                                    <th>Plano</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Período</th>
                                    <th>Cadastro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subscriptions as $sub): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar me-2"><?php echo strtoupper(substr($sub->user_nome, 0, 2)); ?></span>
                                                <div>
                                                    <div><strong><?php echo $sub->user_nome; ?></strong></div>
                                                    <div class="text-muted small"><?php echo $sub->user_email; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div><strong><?php echo $sub->plan_nome; ?></strong></div>
                                            <div class="text-muted small"><?php echo ucfirst($sub->plan_tipo); ?></div>
                                        </td>
                                        <td><strong>R$ <?php echo number_format($sub->plan_preco, 2, ',', '.'); ?></strong></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                if ($sub->status === 'ativa') echo 'green';
                                                elseif ($sub->status === 'cancelada') echo 'red';
                                                else echo 'gray';
                                            ?>-lt">
                                                <?php echo ucfirst($sub->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div><?php echo date('d/m/Y', strtotime($sub->data_inicio)); ?></div>
                                            <div class="text-muted small">até <?php echo date('d/m/Y', strtotime($sub->data_fim)); ?></div>
                                        </td>
                                        <td class="text-muted"><?php echo date('d/m/Y', strtotime($sub->created_at)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <?php if ($total > $per_page): ?>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">
                                Mostrando <span><?php echo $offset + 1; ?></span> a <span><?php echo min($offset + $per_page, $total); ?></span> de <span><?php echo $total; ?></span> assinaturas
                            </p>
                            <ul class="pagination m-0 ms-auto">
                                <?php if ($offset > 0): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?offset=<?php echo $offset - $per_page; ?><?php echo $this->input->get('status') ? '&status=' . $this->input->get('status') : ''; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                                            Anterior
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($offset + $per_page < $total): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?offset=<?php echo $offset + $per_page; ?><?php echo $this->input->get('status') ? '&status=' . $this->input->get('status') : ''; ?>">
                                            Próxima
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <div class="empty">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                    </div>
                    <p class="empty-title">Nenhuma assinatura encontrada</p>
                    <p class="empty-subtitle text-muted">Tente ajustar os filtros de busca</p>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php $this->load->view('templates/tabler/footer'); ?>
</div>
