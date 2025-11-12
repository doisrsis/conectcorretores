<?php
/**
 * Dashboard - Meu Perfil (Tabler)
 * 
 * @author Rafael Dias - doisr.com.br (12/11/2025 00:30)
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
                    <h2 class="page-title">Meu Perfil</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                
                <!-- Coluna Esquerda - Info do Plano -->
                <div class="col-lg-4">
                    <?php if ($subscription): ?>
                    <!-- Card do Plano -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <div class="text-muted">Seu Plano Atual</div>
                                    <div class="h2 mb-0"><?php echo $subscription->plan_nome; ?></div>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-<?php echo $subscription->status === 'ativa' ? 'green' : 'red'; ?>-lt">
                                        <?php echo ucfirst($subscription->status); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Valor</div>
                                    <div class="datagrid-content">
                                        <strong>R$ <?php echo number_format($subscription->plan_preco, 2, ',', '.'); ?></strong>
                                        <span class="text-muted">/<?php echo $subscription->plan_tipo; ?></span>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Limite de Imóveis</div>
                                    <div class="datagrid-content">
                                        <?php 
                                        $limite = $subscription->plan_limite_imoveis;
                                        echo $limite ? number_format($limite, 0, ',', '.') : '∞';
                                        ?>
                                        <span class="text-muted">(<?php echo $stats->total_imoveis; ?> cadastrados)</span>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Válido até</div>
                                    <div class="datagrid-content">
                                        <?php echo date('d/m/Y', strtotime($subscription->data_fim)); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <a href="<?php echo base_url('planos'); ?>" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                    Alterar Plano
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Sem Plano -->
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="empty">
                                <div class="empty-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                                </div>
                                <p class="empty-title">Sem plano ativo</p>
                                <p class="empty-subtitle text-muted">Escolha um plano para começar</p>
                                <div class="empty-action">
                                    <a href="<?php echo base_url('planos'); ?>" class="btn btn-primary">
                                        Ver Planos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Coluna Direita - Formulário -->
                <div class="col-lg-8">
                    <form method="post" action="<?php echo base_url('perfil'); ?>" class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informações Pessoais</h3>
                        </div>
                        <div class="card-body">
                            
                            <!-- Flash Messages -->
                            <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible">
                                <?php echo $this->session->flashdata('success'); ?>
                                <a class="btn-close" data-bs-dismiss="alert"></a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <?php echo $this->session->flashdata('error'); ?>
                                <a class="btn-close" data-bs-dismiss="alert"></a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (validation_errors()): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <?php echo validation_errors(); ?>
                                <a class="btn-close" data-bs-dismiss="alert"></a>
                            </div>
                            <?php endif; ?>

                            <!-- Nome -->
                            <div class="mb-3">
                                <label class="form-label required">Nome Completo</label>
                                <input type="text" name="nome" class="form-control" value="<?php echo $user->nome; ?>" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label required">E-mail</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $user->email; ?>" required>
                            </div>

                            <!-- Telefone e WhatsApp -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Telefone</label>
                                        <input type="text" name="telefone" class="form-control" value="<?php echo $user->telefone; ?>" placeholder="(00) 0000-0000">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">WhatsApp</label>
                                        <input type="text" name="whatsapp" class="form-control" value="<?php echo $user->whatsapp; ?>" placeholder="(00) 00000-0000">
                                    </div>
                                </div>
                            </div>

                            <!-- CPF -->
                            <div class="mb-3">
                                <label class="form-label">CPF</label>
                                <input type="text" name="cpf" class="form-control" value="<?php echo $user->cpf; ?>" placeholder="000.000.000-00">
                            </div>

                            <!-- Endereço -->
                            <div class="mb-3">
                                <label class="form-label">Endereço</label>
                                <textarea name="endereco" class="form-control" rows="2"><?php echo $user->endereco; ?></textarea>
                            </div>

                            <hr class="my-4">

                            <!-- Alterar Senha -->
                            <h3 class="card-title mb-3">Alterar Senha</h3>
                            <p class="text-muted small mb-3">Deixe em branco se não deseja alterar a senha</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nova Senha</label>
                                        <input type="password" name="nova_senha" class="form-control" placeholder="Mínimo 6 caracteres">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Confirmar Senha</label>
                                        <input type="password" name="confirmar_senha" class="form-control" placeholder="Digite novamente">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <?php $this->load->view('templates/tabler/footer'); ?>
</div>
