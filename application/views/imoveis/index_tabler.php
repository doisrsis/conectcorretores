<?php
/**
 * Listagem de Imóveis - Tabler Layout
 * Autor: Rafael Dias - doisr.com.br
 * Data: 11/11/2025
 */

// Preparar dados para o layout
$data_layout = [
    'title' => 'Meus Imóveis',
    'page' => 'imoveis',
    'page_header' => 'Meus Imóveis',
    'page_pretitle' => 'Gerenciar',
    'page_actions' => '
        <a href="' . base_url('imoveis/novo') . '" class="btn btn-primary d-none d-sm-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
            Novo Imóvel
        </a>
        <a href="' . base_url('imoveis/novo') . '" class="btn btn-primary d-sm-none btn-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
        </a>
    '
];

// Iniciar conteúdo
ob_start();
?>

<!-- Filtros -->
<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tipo de Negócio</label>
                <select name="tipo_negocio" class="form-select">
                    <option value="">Todos</option>
                    <option value="compra" <?php echo $this->input->get('tipo_negocio') === 'compra' ? 'selected' : ''; ?>>Venda</option>
                    <option value="aluguel" <?php echo $this->input->get('tipo_negocio') === 'aluguel' ? 'selected' : ''; ?>>Aluguel</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo de Imóvel</label>
                <select name="tipo_imovel" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach ($tipos_imoveis as $tipo): ?>
                        <option value="<?php echo $tipo; ?>" <?php echo $this->input->get('tipo_imovel') === $tipo ? 'selected' : ''; ?>>
                            <?php echo ucfirst($tipo); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" value="<?php echo $this->input->get('search'); ?>" 
                       class="form-control" placeholder="Cidade, bairro...">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Estatísticas -->
<div class="row row-cards mb-3">
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total de Imóveis</div>
                </div>
                <div class="h1 mb-0"><?php echo $total; ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Página Atual</div>
                </div>
                <div class="h1 mb-0">
                    <?php echo floor($offset / $per_page) + 1; ?> de <?php echo max(1, ceil($total / $per_page)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Mostrando</div>
                </div>
                <div class="h1 mb-0">
                    <?php echo min($per_page, max(0, $total - $offset)); ?> de <?php echo $total; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Imóveis -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lista de Imóveis</h3>
    </div>
    <?php if (!empty($imoveis)): ?>
        <div class="table-responsive">
            <table class="table table-vcenter card-table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Localização</th>
                        <th>Detalhes</th>
                        <th>Preço</th>
                        <th>Status</th>
                        <th class="w-1">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($imoveis as $imovel): ?>
                        <tr>
                            <td class="text-secondary">#<?php echo $imovel->id; ?></td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="font-weight-medium"><?php echo ucfirst($imovel->tipo_imovel); ?></span>
                                    <span class="badge <?php echo $imovel->tipo_negocio === 'compra' ? 'bg-green-lt' : 'bg-blue-lt'; ?> mt-1" style="width: fit-content;">
                                        <?php echo ucfirst($imovel->tipo_negocio); ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div><?php echo $imovel->cidade; ?> - <?php echo $imovel->estado; ?></div>
                                <?php if (!empty($imovel->bairro)): ?>
                                    <div class="text-secondary small"><?php echo $imovel->bairro; ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-secondary">
                                <div class="d-flex gap-2">
                                    <span title="Quartos">🛏️ <?php echo $imovel->quartos; ?></span>
                                    <span title="Vagas">🚗 <?php echo $imovel->vagas; ?></span>
                                    <span title="Área">📐 <?php echo number_format($imovel->area_privativa, 0, ',', '.'); ?>m²</span>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-medium">R$ <?php echo number_format($imovel->preco, 2, ',', '.'); ?></div>
                                <div class="text-secondary small">R$ <?php echo number_format($imovel->valor_m2, 2, ',', '.'); ?>/m²</div>
                            </td>
                            <td>
                                <?php if (isset($imovel->status_publicacao)): ?>
                                    <?php if ($imovel->status_publicacao === 'ativo'): ?>
                                        <span class="badge bg-success text-white">✓ Publicado</span>
                                    <?php elseif ($imovel->status_publicacao === 'inativo_plano_vencido'): ?>
                                        <span class="badge bg-danger text-white">⚠ Plano Vencido</span>
                                    <?php elseif ($imovel->status_publicacao === 'inativo_sem_plano'): ?>
                                        <span class="badge bg-warning text-white">⚠ Sem Plano</span>
                                    <?php elseif ($imovel->status_publicacao === 'inativo_manual'): ?>
                                        <span class="badge bg-secondary text-white">● Desativado</span>
                                    <?php elseif ($imovel->status_publicacao === 'inativo_por_tempo'): ?>
                                        <span class="badge bg-orange text-white">⏰ Expirado</span>
                                    <?php elseif ($imovel->status_publicacao === 'inativo_vendido'): ?>
                                        <span class="badge bg-info text-white">✓ Vendido</span>
                                    <?php elseif ($imovel->status_publicacao === 'inativo_alugado'): ?>
                                        <span class="badge bg-purple text-white">✓ Alugado</span>
                                    <?php endif; ?>
                                <?php elseif (!$imovel->ativo): ?>
                                    <span class="badge bg-danger text-white">● Inativo</span>
                                <?php endif; ?>
                                
                                <?php if ($imovel->destaque): ?>
                                    <span class="badge bg-yellow text-dark mt-1">⭐ Destaque</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="<?php echo base_url('imoveis/ver/' . $imovel->id); ?>" class="btn btn-sm btn-outline-primary" title="Ver">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                    </a>
                                    <a href="<?php echo base_url('imoveis/editar/' . $imovel->id); ?>" class="btn btn-sm btn-primary" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" title="Mais opções">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="<?php echo base_url('imoveis/toggle-status/' . $imovel->id); ?>">
                                                <?php echo ($imovel->status_publicacao === 'ativo') ? 'Desativar' : 'Ativar'; ?>
                                            </a>
                                            <a class="dropdown-item" href="<?php echo base_url('imoveis/toggle-destaque/' . $imovel->id); ?>">
                                                <?php echo $imovel->destaque ? 'Remover Destaque' : 'Destacar'; ?>
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="<?php echo base_url('imoveis/deletar/' . $imovel->id); ?>" 
                                               onclick="return confirm('Tem certeza que deseja excluir este imóvel?')">
                                                Excluir
                                            </a>
                                        </div>
                                    </div>
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
                <p class="m-0 text-secondary">Mostrando <?php echo min($per_page, max(0, $total - $offset)); ?> de <?php echo $total; ?> imóveis</p>
                <ul class="pagination m-0 ms-auto">
                    <?php echo $pagination; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <!-- Empty State -->
        <div class="card-body">
    <div class="empty">
        <div class="empty-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M5 21v-14l8 -4v18" /><path d="M19 21v-10l-6 -4" /><path d="M9 9l0 .01" /><path d="M9 12l0 .01" /><path d="M9 15l0 .01" /><path d="M9 18l0 .01" /></svg>
        </div>
        <p class="empty-title">Nenhum imóvel encontrado</p>
        <p class="empty-subtitle text-secondary">
            <?php if ($this->input->get()): ?>
                Tente ajustar os filtros de busca
            <?php else: ?>
                Comece cadastrando seu primeiro imóvel
            <?php endif; ?>
        </p>
        <div class="empty-action">
            <?php if ($this->input->get()): ?>
                <a href="<?php echo base_url('imoveis'); ?>" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                    Limpar Filtros
                </a>
            <?php else: ?>
                <a href="<?php echo base_url('imoveis/novo'); ?>" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Cadastrar Primeiro Imóvel
                </a>
            <?php endif; ?>
        </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
// Capturar conteúdo
$data_layout['content'] = ob_get_clean();

// Carregar layout
$this->load->view('templates/tabler/layout', $data_layout);
?>
