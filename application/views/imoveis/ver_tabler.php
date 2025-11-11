<?php 
/**
 * Visualização de Imóvel - Layout Tabler
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
                            Imóveis
                        </div>
                        <h2 class="page-title">
                            Detalhes do Imóvel
                        </h2>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="<?php echo base_url('imoveis'); ?>" class="btn btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                                Voltar
                            </a>
                            <a href="<?php echo base_url('imoveis/editar/' . $imovel->id); ?>" class="btn btn-primary">
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

                <!-- Card Principal com Informações do Corretor no Topo -->
                <div class="card mb-3 bg-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-lg bg-white text-primary">
                                    <?php echo strtoupper(substr($imovel->corretor_nome, 0, 2)); ?>
                                </span>
                            </div>
                            <div class="col">
                                <div class="text-white-50 small">Corretor Responsável</div>
                                <div class="fw-bold fs-3"><?php echo $imovel->corretor_nome; ?></div>
                                <div class="text-white-75">
                                    <?php echo $imovel->corretor_email; ?>
                                    <?php if ($imovel->corretor_telefone): ?>
                                        • <?php echo $imovel->corretor_telefone; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="text-white-50 small">Cadastrado em</div>
                                <div class="fw-bold"><?php echo date('d/m/Y', strtotime($imovel->created_at)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Coluna Principal - Informações -->
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Informações do Imóvel</h3>
                                </div>
                                <div class="card-body">
                                
                                <!-- Badges e Título -->
                                <div class="mb-4">
                                    <div class="mb-3">
                                        <span class="badge bg-<?php echo $imovel->tipo_negocio === 'compra' ? 'green' : 'blue'; ?> me-1">
                                            <?php echo ucfirst($imovel->tipo_negocio); ?>
                                        </span>
                                        <span class="badge bg-secondary me-1">
                                            <?php echo ucfirst($imovel->tipo_imovel); ?>
                                        </span>
                                        <?php if ($imovel->destaque): ?>
                                            <span class="badge bg-yellow me-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                                                Destaque
                                            </span>
                                        <?php endif; ?>
                                        <span class="badge bg-<?php echo $imovel->ativo ? 'green' : 'red'; ?>">
                                            <?php echo $imovel->ativo ? 'Ativo' : 'Inativo'; ?>
                                        </span>
                                    </div>

                                    <h2 class="mb-2"><?php echo ucfirst($imovel->tipo_imovel); ?> em <?php echo $imovel->bairro; ?></h2>
                                    
                                    <div class="text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                                        <?php echo $imovel->bairro; ?> - <?php echo $imovel->cidade; ?>/<?php echo $imovel->estado; ?>
                                        <?php if ($imovel->cep): ?>
                                            • CEP: <?php echo $imovel->cep; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <!-- Preço -->
                                    <div class="col-md-4">
                                        <div class="card bg-primary-lt mb-0">
                                            <div class="card-body">
                                                <div class="text-muted small mb-1">Valor</div>
                                                <h2 class="mb-1">R$ <?php echo number_format($imovel->preco, 2, ',', '.'); ?></h2>
                                                <div class="text-muted small">
                                                    R$ <?php echo number_format($imovel->valor_m2, 2, ',', '.'); ?>/m²
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Características -->
                                    <div class="col-md-8">
                                        <div class="row g-3">
                                            <div class="col-4">
                                                <div class="card mb-0">
                                                    <div class="card-body text-center py-3">
                                                        <div class="text-muted small mb-1">Área</div>
                                                        <h3 class="mb-0"><?php echo number_format($imovel->area_privativa, 0, ',', '.'); ?>m²</h3>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if ($imovel->quartos): ?>
                                            <div class="col-4">
                                                <div class="card mb-0">
                                                    <div class="card-body text-center py-3">
                                                        <div class="text-muted small mb-1">Quartos</div>
                                                        <h3 class="mb-0"><?php echo $imovel->quartos; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <?php if ($imovel->vagas): ?>
                                            <div class="col-4">
                                                <div class="card mb-0">
                                                    <div class="card-body text-center py-3">
                                                        <div class="text-muted small mb-1">Vagas</div>
                                                        <h3 class="mb-0"><?php echo $imovel->vagas; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($imovel->link_imovel) && $imovel->link_imovel): ?>
                                <div class="mt-4">
                                    <a href="<?php echo $imovel->link_imovel; ?>" target="_blank" class="btn btn-outline-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" /><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" /></svg>
                                        Ver Anúncio Completo
                                    </a>
                                </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                    <!-- Card de Ações -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Ações Rápidas</h3>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-lg-2 col-md-4 col-6">
                                        <a href="<?php echo base_url('imoveis/toggle_status/' . $imovel->id); ?>" 
                                           class="btn btn-outline-<?php echo $imovel->ativo ? 'warning' : 'success'; ?> w-100"
                                           onclick="return confirm('Deseja <?php echo $imovel->ativo ? 'desativar' : 'ativar'; ?> este imóvel?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                                            <?php echo $imovel->ativo ? 'Desativar' : 'Ativar'; ?>
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6">
                                        <a href="<?php echo base_url('imoveis/toggle_destaque/' . $imovel->id); ?>" 
                                           class="btn btn-outline-yellow w-100"
                                           onclick="return confirm('Deseja <?php echo $imovel->destaque ? 'remover destaque' : 'marcar como destaque'; ?>?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                                            <?php echo $imovel->destaque ? 'Remover Destaque' : 'Destacar'; ?>
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6">
                                        <a href="<?php echo base_url('imoveis/marcar_vendido/' . $imovel->id); ?>" 
                                           class="btn btn-outline-success w-100"
                                           onclick="return confirm('Marcar este imóvel como VENDIDO? Isso irá desativar o imóvel.')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                            Vendido
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6">
                                        <a href="<?php echo base_url('imoveis/marcar_alugado/' . $imovel->id); ?>" 
                                           class="btn btn-outline-info w-100"
                                           onclick="return confirm('Marcar este imóvel como ALUGADO? Isso irá desativar o imóvel.')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12l.01 0" /><path d="M13 12l2 0" /><path d="M9 16l.01 0" /><path d="M13 16l2 0" /></svg>
                                            Alugado
                                        </a>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6">
                                        <a href="<?php echo base_url('imoveis/deletar/' . $imovel->id); ?>" 
                                           class="btn btn-outline-danger w-100"
                                           onclick="return confirm('Tem certeza que deseja deletar este imóvel? Esta ação não pode ser desfeita!')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            Deletar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php $this->load->view('templates/tabler/footer'); ?>
    </div>
</div>

<?php $this->load->view('templates/tabler/footer_scripts'); ?>
