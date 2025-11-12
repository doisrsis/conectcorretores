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
                
                <!-- Card Principal -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-xl" style="background-image: url(<?php echo base_url('assets/img/property-placeholder.jpg'); ?>)">
                                    <?php if (!isset($imovel->imagem_principal)): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M9 8l1 0" /><path d="M9 12l1 0" /><path d="M9 16l1 0" /><path d="M14 8l1 0" /><path d="M14 12l1 0" /><path d="M14 16l1 0" /><path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="col">
                                <h2 class="mb-1"><?php echo ucfirst($imovel->tipo_imovel); ?> em <?php echo $imovel->bairro; ?></h2>
                                <div class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" /></svg>
                                    <?php echo $imovel->bairro; ?> - <?php echo $imovel->cidade; ?>/<?php echo $imovel->estado; ?>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-<?php echo $imovel->tipo_negocio === 'compra' ? 'green' : 'blue'; ?>-lt me-1">
                                        <?php echo ucfirst($imovel->tipo_negocio); ?>
                                    </span>
                                    <span class="badge bg-azure-lt me-1">
                                        <?php echo ucfirst($imovel->tipo_imovel); ?>
                                    </span>
                                    <?php if ($imovel->destaque): ?>
                                        <span class="badge bg-yellow-lt me-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                                            Destaque
                                        </span>
                                    <?php endif; ?>
                                    <span class="badge bg-<?php echo $imovel->ativo ? 'green' : 'red'; ?>-lt">
                                        <?php echo $imovel->ativo ? 'Ativo' : 'Inativo'; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="text-muted small">Cadastrado em</div>
                                <div class="fw-bold"><?php echo date('d/m/Y', strtotime($imovel->created_at)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações e Características -->
                <div class="row g-3">

                    <!-- Preço -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="subheader">Valor</div>
                                <div class="h1 mb-0">R$ <?php echo number_format($imovel->preco, 2, ',', '.'); ?></div>
                                <div class="text-muted mt-1">
                                    R$ <?php echo number_format($imovel->valor_m2, 2, ',', '.'); ?>/m²
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Área -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="subheader">Área Privativa</div>
                                <div class="d-flex align-items-baseline">
                                    <div class="h1 mb-0 me-2"><?php echo number_format($imovel->area_privativa, 0, ',', '.'); ?></div>
                                    <div class="me-2">m²</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($imovel->quartos): ?>
                    <!-- Quartos -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="subheader">Quartos</div>
                                <div class="h1 mb-0"><?php echo $imovel->quartos; ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($imovel->vagas): ?>
                    <!-- Vagas -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="subheader">Vagas</div>
                                <div class="h1 mb-0"><?php echo $imovel->vagas; ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                
                <!-- Corretor e Informações Adicionais -->
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Corretor Responsável</h3>
                            </div>
                            <div class="card-body">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Nome</div>
                                        <div class="datagrid-content"><?php echo $imovel->corretor_nome; ?></div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Email</div>
                                        <div class="datagrid-content"><?php echo $imovel->corretor_email; ?></div>
                                    </div>
                                    <?php if ($imovel->corretor_telefone): ?>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Telefone</div>
                                        <div class="datagrid-content"><?php echo $imovel->corretor_telefone; ?></div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Localização</h3>
                            </div>
                            <div class="card-body">
                                <div class="datagrid">
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Endereço</div>
                                        <div class="datagrid-content"><?php echo $imovel->bairro; ?></div>
                                    </div>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Cidade/Estado</div>
                                        <div class="datagrid-content"><?php echo $imovel->cidade; ?>/<?php echo $imovel->estado; ?></div>
                                    </div>
                                    <?php if ($imovel->cep): ?>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">CEP</div>
                                        <div class="datagrid-content"><?php echo $imovel->cep; ?></div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (isset($imovel->link_imovel) && $imovel->link_imovel): ?>
                                    <div class="datagrid-item">
                                        <div class="datagrid-title">Link</div>
                                        <div class="datagrid-content">
                                            <a href="<?php echo $imovel->link_imovel; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                Ver Anúncio
                                            </a>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Ações Rápidas</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
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

        <?php $this->load->view('templates/tabler/footer'); ?>
    </div>
</div>
