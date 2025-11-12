<?php
/**
 * Admin - Editar Usuário (Tabler)
 * 
 * @author Rafael Dias - doisr.com.br (11/11/2025 23:01)
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
                        <a href="<?php echo base_url('admin/usuarios'); ?>" class="text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                            Voltar para Usuários
                        </a>
                    </div>
                    <h2 class="page-title">
                        Editar Usuário
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            
            <form method="post" action="<?php echo base_url('admin/editar_usuario/' . $user->id); ?>">
                
                <div class="row g-3">
                    <!-- Informações Básicas -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Informações Básicas</h3>
                            </div>
                            <div class="card-body">
                                
                                <!-- Nome -->
                                <div class="mb-3">
                                    <label class="form-label required">Nome Completo</label>
                                    <input type="text" name="nome" class="form-control <?php echo form_error('nome') ? 'is-invalid' : ''; ?>" 
                                           value="<?php echo set_value('nome', $user->nome); ?>" required>
                                    <?php if (form_error('nome')): ?>
                                        <div class="invalid-feedback"><?php echo form_error('nome'); ?></div>
                                    <?php endif; ?>
                                </div>

                                <!-- Email (não editável) -->
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?php echo $user->email; ?>" disabled>
                                    <small class="form-hint">O email não pode ser alterado</small>
                                </div>

                                <!-- Telefone -->
                                <div class="mb-3">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" name="telefone" class="form-control" 
                                           value="<?php echo set_value('telefone', $user->telefone); ?>" 
                                           placeholder="(00) 00000-0000">
                                </div>

                                <!-- WhatsApp -->
                                <div class="mb-3">
                                    <label class="form-label">WhatsApp</label>
                                    <input type="text" name="whatsapp" class="form-control" 
                                           value="<?php echo set_value('whatsapp', $user->whatsapp); ?>" 
                                           placeholder="(00) 00000-0000">
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Configurações da Conta -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Configurações da Conta</h3>
                            </div>
                            <div class="card-body">
                                
                                <!-- Função (não editável) -->
                                <div class="mb-3">
                                    <label class="form-label">Função</label>
                                    <input type="text" class="form-control" value="<?php echo ucfirst($user->role); ?>" disabled>
                                    <small class="form-hint">A função não pode ser alterada</small>
                                </div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label class="form-label required">Status</label>
                                    <select name="ativo" class="form-select <?php echo form_error('ativo') ? 'is-invalid' : ''; ?>" required>
                                        <option value="1" <?php echo set_value('ativo', $user->ativo) == '1' ? 'selected' : ''; ?>>Ativo</option>
                                        <option value="0" <?php echo set_value('ativo', $user->ativo) == '0' ? 'selected' : ''; ?>>Inativo</option>
                                    </select>
                                    <?php if (form_error('ativo')): ?>
                                        <div class="invalid-feedback"><?php echo form_error('ativo'); ?></div>
                                    <?php endif; ?>
                                    <small class="form-hint">Usuários inativos não podem fazer login</small>
                                </div>

                                <!-- Informações Adicionais -->
                                <div class="mb-3">
                                    <label class="form-label">ID do Usuário</label>
                                    <input type="text" class="form-control" value="#<?php echo $user->id; ?>" disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Cadastrado em</label>
                                    <input type="text" class="form-control" value="<?php echo date('d/m/Y H:i', strtotime($user->created_at)); ?>" disabled>
                                </div>

                            </div>
                        </div>

                        <!-- Alertas -->
                        <?php if ($user->id == $this->session->userdata('user_id')): ?>
                        <div class="alert alert-warning mt-3" role="alert">
                            <div class="d-flex">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                                </div>
                                <div>
                                    <h4 class="alert-title">Atenção!</h4>
                                    <div class="text-muted">Você está editando sua própria conta.</div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="card mt-3">
                    <div class="card-footer">
                        <div class="d-flex">
                            <a href="<?php echo base_url('admin/usuarios'); ?>" class="btn btn-link">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary ms-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                Salvar Alterações
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <?php $this->load->view('templates/tabler/footer'); ?>
</div>
