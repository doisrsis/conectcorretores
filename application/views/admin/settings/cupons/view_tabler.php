<?php
/**
 * Visualizar Cupom - Tabler
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 11/11/2025
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// Preparar conteúdo
ob_start();
?>

<div class="row row-cards">
    <!-- Informações do Cupom -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informações do Cupom</h3>
                <div class="card-actions">
                    <a href="<?php echo base_url('settings/cupons'); ?>" class="btn btn-outline-secondary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                        Voltar
                    </a>
                    <a href="<?php echo base_url('settings/cupons_edit/' . $coupon->id); ?>" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                        Editar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="datagrid">
                    <div class="datagrid-item">
                        <div class="datagrid-title">Código</div>
                        <div class="datagrid-content">
                            <strong class="text-primary"><?php echo $coupon->codigo; ?></strong>
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Status</div>
                        <div class="datagrid-content">
                            <?php if ($coupon->ativo): ?>
                                <span class="badge bg-green-lt">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-lt">Inativo</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Tipo</div>
                        <div class="datagrid-content">
                            <?php if ($coupon->tipo === 'percent'): ?>
                                <span class="badge bg-blue-lt">Percentual</span>
                            <?php else: ?>
                                <span class="badge bg-green-lt">Valor Fixo</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Valor</div>
                        <div class="datagrid-content">
                            <?php if ($coupon->tipo === 'percent'): ?>
                                <strong><?php echo $coupon->valor; ?>%</strong>
                            <?php else: ?>
                                <strong>R$ <?php echo number_format($coupon->valor, 2, ',', '.'); ?></strong>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Duração</div>
                        <div class="datagrid-content">
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
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Usos</div>
                        <div class="datagrid-content">
                            <strong><?php echo $coupon->usos_atuais; ?></strong>
                            <?php if ($coupon->max_usos): ?>
                                / <?php echo $coupon->max_usos; ?>
                            <?php else: ?>
                                <small class="text-muted">/ ilimitado</small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Validade</div>
                        <div class="datagrid-content">
                            <?php if ($coupon->valido_de && $coupon->valido_ate): ?>
                                <?php echo date('d/m/Y', strtotime($coupon->valido_de)); ?><br>
                                até <?php echo date('d/m/Y', strtotime($coupon->valido_ate)); ?>
                            <?php elseif ($coupon->valido_ate): ?>
                                Até <?php echo date('d/m/Y', strtotime($coupon->valido_ate)); ?>
                            <?php else: ?>
                                <span class="text-muted">Sem limite</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Stripe ID</div>
                        <div class="datagrid-content">
                            <code><?php echo $coupon->stripe_coupon_id; ?></code>
                        </div>
                    </div>
                    <?php if ($coupon->descricao): ?>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Descrição</div>
                        <div class="datagrid-content">
                            <?php echo $coupon->descricao; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Criado em</div>
                        <div class="datagrid-content">
                            <?php echo date('d/m/Y H:i', strtotime($coupon->created_at)); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Uso -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Histórico de Uso</h3>
            </div>
            <div class="card-body">
                <?php if (empty($usage_history)): ?>
                    <div class="empty">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /></svg>
                        </div>
                        <p class="empty-title">Nenhum uso registrado</p>
                        <p class="empty-subtitle text-muted">
                            Este cupom ainda não foi utilizado.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($usage_history as $uso): ?>
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="avatar">
                                            <?php echo strtoupper(substr($uso->user_name, 0, 2)); ?>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="text-truncate">
                                            <strong><?php echo $uso->user_name; ?></strong>
                                        </div>
                                        <div class="text-muted">
                                            <?php echo $uso->user_email; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="text-end">
                                            <div class="text-muted">
                                                <?php echo date('d/m/Y', strtotime($uso->used_at)); ?>
                                            </div>
                                            <div class="text-muted small">
                                                <?php echo date('H:i', strtotime($uso->used_at)); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

// Dados para o layout
$data = [
    'content' => $content,
    'page_title' => 'Detalhes do Cupom',
    'active_tab' => 'cupons'
];

// Carregar layout
$this->load->view('admin/settings/layout_tabler', $data);
?>
