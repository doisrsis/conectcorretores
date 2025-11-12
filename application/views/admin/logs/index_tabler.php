<?php
/**
 * Admin - Logs de Atividade (Tabler)
 * 
 * @author Rafael Dias - doisr.com.br (11/11/2025 23:31)
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
                        Logs de Atividade
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            
            <!-- Estatísticas -->
            <div class="row row-cards mb-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="subheader">Total de Ações (Hoje)</div>
                            <div class="h1 mb-0"><?php echo isset($stats->total_actions) ? $stats->total_actions : 0; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="subheader">Total de Logs</div>
                            <div class="h1 mb-0"><?php echo $total; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Filtros</h3>
                </div>
                <div class="card-body">
                    <form method="get" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Módulo</label>
                            <select name="module" class="form-select">
                                <option value="">Todos</option>
                                <option value="auth" <?php echo $this->input->get('module') === 'auth' ? 'selected' : ''; ?>>Autenticação</option>
                                <option value="users" <?php echo $this->input->get('module') === 'users' ? 'selected' : ''; ?>>Usuários</option>
                                <option value="imoveis" <?php echo $this->input->get('module') === 'imoveis' ? 'selected' : ''; ?>>Imóveis</option>
                                <option value="planos" <?php echo $this->input->get('module') === 'planos' ? 'selected' : ''; ?>>Planos</option>
                                <option value="subscriptions" <?php echo $this->input->get('module') === 'subscriptions' ? 'selected' : ''; ?>>Assinaturas</option>
                                <option value="settings" <?php echo $this->input->get('module') === 'settings' ? 'selected' : ''; ?>>Configurações</option>
                                <option value="cupons" <?php echo $this->input->get('module') === 'cupons' ? 'selected' : ''; ?>>Cupons</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Ação</label>
                            <select name="action" class="form-select">
                                <option value="">Todas</option>
                                <option value="login" <?php echo $this->input->get('action') === 'login' ? 'selected' : ''; ?>>Login</option>
                                <option value="logout" <?php echo $this->input->get('action') === 'logout' ? 'selected' : ''; ?>>Logout</option>
                                <option value="create" <?php echo $this->input->get('action') === 'create' ? 'selected' : ''; ?>>Criar</option>
                                <option value="update" <?php echo $this->input->get('action') === 'update' ? 'selected' : ''; ?>>Atualizar</option>
                                <option value="delete" <?php echo $this->input->get('action') === 'delete' ? 'selected' : ''; ?>>Deletar</option>
                                <option value="view" <?php echo $this->input->get('action') === 'view' ? 'selected' : ''; ?>>Visualizar</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Data Inicial</label>
                            <input type="date" name="date_from" value="<?php echo $this->input->get('date_from'); ?>" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Data Final</label>
                            <input type="date" name="date_to" value="<?php echo $this->input->get('date_to'); ?>" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Buscar</label>
                            <input type="text" name="search" value="<?php echo $this->input->get('search'); ?>" 
                                   class="form-control" placeholder="Descrição...">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Logs -->
            <?php if (!empty($logs)): ?>
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table table-striped">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Usuário</th>
                                    <th>Ação</th>
                                    <th>Módulo</th>
                                    <th>Descrição</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td class="text-muted">
                                            <?php echo date('d/m/Y H:i:s', strtotime($log->created_at)); ?>
                                        </td>
                                        <td>
                                            <?php if ($log->user_id): ?>
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-sm me-2"><?php echo strtoupper(substr($log->user_name, 0, 2)); ?></span>
                                                    <div>
                                                        <div><?php echo $log->user_name; ?></div>
                                                        <?php if (isset($log->user_email)): ?>
                                                            <div class="text-muted small"><?php echo $log->user_email; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">Sistema</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $badge_colors = [
                                                'login' => 'green',
                                                'logout' => 'gray',
                                                'create' => 'blue',
                                                'update' => 'yellow',
                                                'delete' => 'red',
                                                'view' => 'cyan',
                                                'export' => 'purple',
                                                'import' => 'indigo',
                                                'status_change' => 'orange'
                                            ];
                                            $color = isset($badge_colors[$log->action]) ? $badge_colors[$log->action] : 'secondary';
                                            ?>
                                            <span class="badge bg-<?php echo $color; ?>-lt">
                                                <?php echo ucfirst($log->action); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-azure-lt">
                                                <?php echo ucfirst($log->module); ?>
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            <?php echo $log->description; ?>
                                            <?php if ($log->record_id): ?>
                                                <span class="text-muted small">(ID: <?php echo $log->record_id; ?>)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small">
                                            <?php echo $log->ip_address; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <?php if ($total > $per_page): ?>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">
                                Mostrando <span><?php echo $offset + 1; ?></span> a <span><?php echo min($offset + $per_page, $total); ?></span> de <span><?php echo $total; ?></span> logs
                            </p>
                            <ul class="pagination m-0 ms-auto">
                                <?php if ($offset > 0): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?offset=<?php echo $offset - $per_page; ?><?php echo $this->input->get('module') ? '&module=' . $this->input->get('module') : ''; ?><?php echo $this->input->get('action') ? '&action=' . $this->input->get('action') : ''; ?><?php echo $this->input->get('date_from') ? '&date_from=' . $this->input->get('date_from') : ''; ?><?php echo $this->input->get('date_to') ? '&date_to=' . $this->input->get('date_to') : ''; ?><?php echo $this->input->get('search') ? '&search=' . $this->input->get('search') : ''; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                                            Anterior
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($offset + $per_page < $total): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?offset=<?php echo $offset + $per_page; ?><?php echo $this->input->get('module') ? '&module=' . $this->input->get('module') : ''; ?><?php echo $this->input->get('action') ? '&action=' . $this->input->get('action') : ''; ?><?php echo $this->input->get('date_from') ? '&date_from=' . $this->input->get('date_from') : ''; ?><?php echo $this->input->get('date_to') ? '&date_to=' . $this->input->get('date_to') : ''; ?><?php echo $this->input->get('search') ? '&search=' . $this->input->get('search') : ''; ?>">
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" /><path d="M3 6l0 13" /><path d="M12 6l0 13" /><path d="M21 6l0 13" /></svg>
                    </div>
                    <p class="empty-title">Nenhum log encontrado</p>
                    <p class="empty-subtitle text-muted">
                        Tente ajustar os filtros de busca
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php $this->load->view('templates/tabler/footer'); ?>
</div>
