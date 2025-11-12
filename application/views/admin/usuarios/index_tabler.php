<?php
/**
 * Admin - Lista de Usuários (Tabler)
 * 
 * @author Rafael Dias - doisr.com.br (11/11/2025 22:57)
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
                        Gerenciar Usuários
                    </h2>
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
                    <div>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
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
                    <div>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                </div>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            <?php endif; ?>
            
            <!-- Estatísticas -->
            <div class="row row-cards mb-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total de Usuários</div>
                            </div>
                            <div class="h1 mb-0"><?php echo $total; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Mostrando</div>
                            </div>
                            <div class="h1 mb-0"><?php echo min($per_page, $total - $offset); ?> de <?php echo $total; ?></div>
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
                        <div class="col-md-4">
                            <label class="form-label">Função</label>
                            <select name="role" class="form-select">
                                <option value="">Todos</option>
                                <option value="corretor" <?php echo $this->input->get('role') === 'corretor' ? 'selected' : ''; ?>>Corretor</option>
                                <option value="admin" <?php echo $this->input->get('role') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Buscar</label>
                            <input type="text" name="search" value="<?php echo $this->input->get('search'); ?>" 
                                   class="form-control" placeholder="Nome ou email...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Usuários -->
            <?php if (!empty($users)): ?>
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Usuário</th>
                                    <th>Contato</th>
                                    <th>Função</th>
                                    <th>Status</th>
                                    <th>Cadastro</th>
                                    <th class="w-1">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex py-1 align-items-center">
                                                <span class="avatar me-2"><?php echo strtoupper(substr($user->nome, 0, 2)); ?></span>
                                                <div class="flex-fill">
                                                    <div class="font-weight-medium"><?php echo $user->nome; ?></div>
                                                    <div class="text-muted"><?php echo $user->email; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                <?php if ($user->telefone): ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                                    <?php echo $user->telefone; ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($user->whatsapp): ?>
                                                <div class="text-muted small">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                                                    <?php echo $user->whatsapp; ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $user->role === 'admin' ? 'blue' : 'azure'; ?>-lt">
                                                <?php echo ucfirst($user->role); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $user->ativo ? 'green' : 'red'; ?>-lt">
                                                <?php echo $user->ativo ? 'Ativo' : 'Inativo'; ?>
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            <?php echo date('d/m/Y', strtotime($user->created_at)); ?>
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <a href="<?php echo base_url('admin/ver_usuario/' . $user->id); ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                    Ver
                                                </a>
                                                <a href="<?php echo base_url('admin/editar_usuario/' . $user->id); ?>" 
                                                   class="btn btn-sm btn-outline-secondary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                    Editar
                                                </a>
                                                <?php if ($user->id != $this->session->userdata('user_id')): ?>
                                                    <a href="<?php echo base_url('admin/deletar_usuario/' . $user->id); ?>" 
                                                       class="btn btn-sm btn-outline-danger"
                                                       onclick="return confirm('Tem certeza que deseja deletar este usuário?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                        Deletar
                                                    </a>
                                                <?php endif; ?>
                                            </div>
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
                                Mostrando <span><?php echo $offset + 1; ?></span> a <span><?php echo min($offset + $per_page, $total); ?></span> de <span><?php echo $total; ?></span> usuários
                            </p>
                            <ul class="pagination m-0 ms-auto">
                                <?php if ($offset > 0): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?offset=<?php echo $offset - $per_page; ?><?php echo $this->input->get('role') ? '&role=' . $this->input->get('role') : ''; ?><?php echo $this->input->get('search') ? '&search=' . $this->input->get('search') : ''; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                                            Anterior
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if ($offset + $per_page < $total): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?offset=<?php echo $offset + $per_page; ?><?php echo $this->input->get('role') ? '&role=' . $this->input->get('role') : ''; ?><?php echo $this->input->get('search') ? '&search=' . $this->input->get('search') : ''; ?>">
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                    </div>
                    <p class="empty-title">Nenhum usuário encontrado</p>
                    <p class="empty-subtitle text-muted">
                        Tente ajustar os filtros de busca
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php $this->load->view('templates/tabler/footer'); ?>
</div>
