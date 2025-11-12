<?php
/**
 * Gerenciamento de Cupons - Tabler
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 11/11/2025
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// Preparar conteúdo
ob_start();
?>

<!-- Estatísticas -->
<div class="row row-cards mb-3">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total de Cupons</div>
                </div>
                <div class="h1 mb-3"><?php echo $statistics->total_cupons; ?></div>
                <div class="d-flex mb-2">
                    <div>Todos os cupons cadastrados</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Cupons Ativos</div>
                </div>
                <div class="h1 mb-3 text-success"><?php echo $statistics->cupons_ativos; ?></div>
                <div class="d-flex mb-2">
                    <div>Disponíveis para uso</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total de Usos</div>
                </div>
                <div class="h1 mb-3 text-purple"><?php echo $statistics->total_usos; ?></div>
                <div class="d-flex mb-2">
                    <div>Cupons utilizados</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Cupons Inativos</div>
                </div>
                <div class="h1 mb-3 text-secondary"><?php echo $statistics->total_cupons - $statistics->cupons_ativos; ?></div>
                <div class="d-flex mb-2">
                    <div>Não disponíveis</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Cupons -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cupons de Desconto</h3>
        <div class="card-actions">
            <a href="<?php echo base_url('settings/cupons_maintenance'); ?>" class="btn btn-icon" title="Manutenção Automática">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 7l0 2.5" /><path d="M12 14.5l0 2.5" /></svg>
            </a>
            <a href="<?php echo base_url('settings/cupons_create'); ?>" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                Novo Cupom
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Valor</th>
                    <th>Duração</th>
                    <th>Usos</th>
                    <th>Validade</th>
                    <th>Status</th>
                    <th class="w-1">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($coupons)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 5l0 2" /><path d="M15 11l0 2" /><path d="M15 17l0 2" /><path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" /></svg>
                            <p>Nenhum cupom cadastrado ainda.</p>
                            <a href="<?php echo base_url('settings/cupons_create'); ?>" class="btn btn-primary mt-2">
                                Criar Primeiro Cupom
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($coupons as $coupon): ?>
                        <tr>
                            <td>
                                <strong><?php echo $coupon->codigo; ?></strong>
                                <?php if ($coupon->descricao): ?>
                                    <br><small class="text-muted"><?php echo $coupon->descricao; ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($coupon->tipo === 'percent'): ?>
                                    <span class="badge badge-outline text-blue"><?php echo $coupon->valor; ?>%</span>
                                <?php else: ?>
                                    <span class="badge badge-outline text-green">R$ <?php echo number_format($coupon->valor, 2, ',', '.'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $duracao_labels = [
                                    'once' => 'Uma Vez',
                                    'repeating' => 'Recorrente',
                                    'forever' => 'Para Sempre'
                                ];
                                echo $duracao_labels[$coupon->duracao] ?? $coupon->duracao;
                                
                                if ($coupon->duracao === 'repeating' && $coupon->duracao_meses):
                                    echo '<br><small class="text-muted">' . $coupon->duracao_meses . ' meses</small>';
                                endif;
                                ?>
                            </td>
                            <td>
                                <?php echo $coupon->usos_atuais; ?>
                                <?php if ($coupon->max_usos): ?>
                                    / <?php echo $coupon->max_usos; ?>
                                <?php else: ?>
                                    <small class="text-muted">/ ilimitado</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($coupon->valido_de && $coupon->valido_ate): ?>
                                    <small>
                                        <?php echo date('d/m/Y', strtotime($coupon->valido_de)); ?><br>
                                        até <?php echo date('d/m/Y', strtotime($coupon->valido_ate)); ?>
                                    </small>
                                <?php elseif ($coupon->valido_ate): ?>
                                    <small>Até <?php echo date('d/m/Y', strtotime($coupon->valido_ate)); ?></small>
                                <?php else: ?>
                                    <small class="text-muted">Sem limite</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($coupon->ativo): ?>
                                    <span class="badge bg-green-lt">Ativo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-lt">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-list">
                                    <a href="<?php echo base_url('settings/cupons_view/' . $coupon->id); ?>" class="btn btn-sm btn-icon" title="Ver Detalhes">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                    </a>
                                    <a href="<?php echo base_url('settings/cupons_edit/' . $coupon->id); ?>" class="btn btn-sm btn-icon" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                    </a>
                                    <a href="<?php echo base_url('settings/cupons_toggle/' . $coupon->id); ?>" class="btn btn-sm btn-icon" title="<?php echo $coupon->ativo ? 'Desativar' : 'Ativar'; ?>">
                                        <?php if ($coupon->ativo): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-warning" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /><path d="M3 3l18 18" /></svg>
                                        <?php else: ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon text-success" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                        <?php endif; ?>
                                    </a>
                                    <a href="<?php echo base_url('settings/cupons_delete/' . $coupon->id); ?>" class="btn btn-sm btn-icon" title="Deletar" onclick="return confirm('Tem certeza que deseja deletar este cupom?');">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon text-danger" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
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

<?php
$content = ob_get_clean();

// Dados para o layout
$data = [
    'content' => $content,
    'page_title' => 'Gerenciar Cupons',
    'active_tab' => 'cupons'
];

// Carregar layout
$this->load->view('admin/settings/layout_tabler', $data);
?>
