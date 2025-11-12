<?php
/**
 * Admin - Ver Detalhes do Usuário (Tabler)
 * 
 * @author Rafael Dias - doisr.com.br (11/11/2025 22:59)
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
                        Detalhes do Usuário
                    </h2>
                </div>
                <div class="col-auto ms-auto">
                    <div class="btn-list">
                        <a href="<?php echo base_url('admin/editar_usuario/' . $user->id); ?>" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            
            <!-- Card Principal -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="avatar avatar-xl"><?php echo strtoupper(substr($user->nome, 0, 2)); ?></span>
                        </div>
                        <div class="col">
                            <h2 class="mb-1"><?php echo $user->nome; ?></h2>
                            <div class="text-muted"><?php echo $user->email; ?></div>
                            <div class="mt-2">
                                <span class="badge bg-<?php echo $user->role === 'admin' ? 'blue' : 'azure'; ?>-lt me-1">
                                    <?php echo ucfirst($user->role); ?>
                                </span>
                                <span class="badge bg-<?php echo $user->ativo ? 'green' : 'red'; ?>-lt">
                                    <?php echo $user->ativo ? 'Ativo' : 'Inativo'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="text-muted small">Cadastrado em</div>
                            <div class="fw-bold"><?php echo date('d/m/Y', strtotime($user->created_at)); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informações Pessoais</h3>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Nome Completo</div>
                                    <div class="datagrid-content"><?php echo $user->nome; ?></div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Email</div>
                                    <div class="datagrid-content">
                                        <a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Telefone</div>
                                    <div class="datagrid-content">
                                        <?php if ($user->telefone): ?>
                                            <a href="tel:<?php echo $user->telefone; ?>"><?php echo $user->telefone; ?></a>
                                        <?php else: ?>
                                            <span class="text-muted">Não informado</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">WhatsApp</div>
                                    <div class="datagrid-content">
                                        <?php if ($user->whatsapp): ?>
                                            <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $user->whatsapp); ?>" target="_blank">
                                                <?php echo $user->whatsapp; ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Não informado</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informações da Conta</h3>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">ID do Usuário</div>
                                    <div class="datagrid-content"><code>#<?php echo $user->id; ?></code></div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Função</div>
                                    <div class="datagrid-content">
                                        <span class="badge bg-<?php echo $user->role === 'admin' ? 'blue' : 'azure'; ?>-lt">
                                            <?php echo ucfirst($user->role); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Status</div>
                                    <div class="datagrid-content">
                                        <span class="badge bg-<?php echo $user->ativo ? 'green' : 'red'; ?>-lt">
                                            <?php echo $user->ativo ? 'Ativo' : 'Inativo'; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Data de Cadastro</div>
                                    <div class="datagrid-content"><?php echo date('d/m/Y H:i', strtotime($user->created_at)); ?></div>
                                </div>
                                <?php if (isset($user->updated_at) && $user->updated_at): ?>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Última Atualização</div>
                                    <div class="datagrid-content"><?php echo date('d/m/Y H:i', strtotime($user->updated_at)); ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($user->role === 'corretor'): ?>
            <!-- Estatísticas do Corretor -->
            <div class="row g-3 mt-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Estatísticas</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <div class="subheader">Total de Imóveis</div>
                                        <div class="h1 mb-0"><?php echo isset($stats->total_imoveis) ? $stats->total_imoveis : 0; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <div class="subheader">Imóveis Ativos</div>
                                        <div class="h1 mb-0"><?php echo isset($stats->imoveis_ativos) ? $stats->imoveis_ativos : 0; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <div class="subheader">Status da Assinatura</div>
                                        <div class="h3 mb-0">
                                            <?php if (isset($subscription) && $subscription): ?>
                                                <span class="badge bg-<?php echo $subscription->status === 'ativa' ? 'green' : 'red'; ?>-lt">
                                                    <?php echo ucfirst($subscription->status); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-gray-lt">Sem Plano</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <div class="subheader">Plano Atual</div>
                                        <div class="h3 mb-0">
                                            <?php if (isset($subscription) && $subscription && isset($subscription->plan_nome)): ?>
                                                <?php echo $subscription->plan_nome; ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (isset($subscription) && $subscription): ?>
            <!-- Detalhes da Assinatura -->
            <div class="row g-3 mt-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Assinatura</h3>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <?php if (isset($subscription->plan_nome)): ?>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Plano</div>
                                    <div class="datagrid-content"><?php echo $subscription->plan_nome; ?></div>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($subscription->plan_preco)): ?>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Valor</div>
                                    <div class="datagrid-content">R$ <?php echo number_format($subscription->plan_preco, 2, ',', '.'); ?></div>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($subscription->status)): ?>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Status</div>
                                    <div class="datagrid-content">
                                        <span class="badge bg-<?php echo $subscription->status === 'ativa' ? 'green' : 'red'; ?>-lt">
                                            <?php echo ucfirst($subscription->status); ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($subscription->data_inicio) && $subscription->data_inicio): ?>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Início</div>
                                    <div class="datagrid-content"><?php echo date('d/m/Y', strtotime($subscription->data_inicio)); ?></div>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($subscription->data_fim) && $subscription->data_fim): ?>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Vencimento</div>
                                    <div class="datagrid-content"><?php echo date('d/m/Y', strtotime($subscription->data_fim)); ?></div>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($subscription->stripe_subscription_id)): ?>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Stripe ID</div>
                                    <div class="datagrid-content"><code><?php echo $subscription->stripe_subscription_id; ?></code></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            <!-- Ações -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Ações</h3>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-auto">
                            <a href="<?php echo base_url('admin/editar_usuario/' . $user->id); ?>" 
                               class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                Editar Usuário
                            </a>
                        </div>
                        <?php if ($user->id != $this->session->userdata('user_id')): ?>
                        <div class="col-auto">
                            <a href="<?php echo base_url('admin/deletar_usuario/' . $user->id); ?>" 
                               class="btn btn-outline-danger"
                               onclick="return confirm('Tem certeza que deseja deletar este usuário? Esta ação não pode ser desfeita!')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                Deletar Usuário
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php $this->load->view('templates/tabler/footer'); ?>
</div>
