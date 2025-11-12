<?php
/**
 * Editar Cupom - Tabler
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 11/11/2025
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// Preparar conteúdo
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Editar Cupom: <?php echo $coupon->codigo; ?></h3>
        <div class="card-actions">
            <a href="<?php echo base_url('settings/cupons'); ?>" class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                Voltar
            </a>
        </div>
    </div>
    <div class="card-body">
        
        <!-- Informações do Cupom (não editáveis) -->
        <div class="alert alert-info mb-4">
            <h4 class="alert-title">Informações do Cupom</h4>
            <div class="text-muted">
                <strong>Código:</strong> <?php echo $coupon->codigo; ?><br>
                <strong>Tipo:</strong> <?php echo $coupon->tipo === 'percent' ? 'Percentual' : 'Valor Fixo'; ?><br>
                <strong>Valor:</strong> <?php echo $coupon->tipo === 'percent' ? $coupon->valor . '%' : 'R$ ' . number_format($coupon->valor, 2, ',', '.'); ?><br>
                <strong>Duração:</strong> 
                <?php
                $duracao_labels = [
                    'once' => 'Uma Vez',
                    'repeating' => 'Recorrente',
                    'forever' => 'Para Sempre'
                ];
                echo $duracao_labels[$coupon->duracao] ?? $coupon->duracao;
                
                if ($coupon->duracao === 'repeating' && $coupon->duracao_meses):
                    echo ' (' . $coupon->duracao_meses . ' meses)';
                endif;
                ?><br>
                <strong>Stripe ID:</strong> <code><?php echo $coupon->stripe_coupon_id; ?></code>
            </div>
        </div>

        <form method="post" action="<?php echo base_url('settings/cupons_edit/' . $coupon->id); ?>">
            
            <div class="row">
                <!-- Descrição -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">Descrição</label>
                    <input type="text" 
                           name="descricao" 
                           class="form-control" 
                           maxlength="500"
                           placeholder="Descrição interna do cupom"
                           value="<?php echo set_value('descricao', $coupon->descricao); ?>">
                </div>
            </div>

            <div class="row">
                <!-- Máximo de Usos -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Máximo de Usos</label>
                    <input type="number" 
                           name="max_usos" 
                           class="form-control" 
                           min="1"
                           placeholder="Deixe vazio para ilimitado"
                           value="<?php echo set_value('max_usos', $coupon->max_usos); ?>">
                    <small class="form-hint">Usos atuais: <strong><?php echo $coupon->usos_atuais; ?></strong></small>
                </div>

                <!-- Válido De -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Válido De</label>
                    <input type="date" 
                           name="valido_de" 
                           class="form-control" 
                           value="<?php echo set_value('valido_de', $coupon->valido_de); ?>">
                </div>

                <!-- Válido Até -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Válido Até</label>
                    <input type="date" 
                           name="valido_ate" 
                           class="form-control" 
                           value="<?php echo set_value('valido_ate', $coupon->valido_ate); ?>">
                </div>
            </div>

            <!-- Ativo -->
            <div class="mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="ativo" value="1" <?php echo set_checkbox('ativo', '1', $coupon->ativo); ?>>
                    <span class="form-check-label">Cupom ativo (disponível para uso)</span>
                </label>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                    Salvar Alterações
                </button>
                <a href="<?php echo base_url('settings/cupons'); ?>" class="btn btn-link">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<div class="alert alert-warning mt-3">
    <h4 class="alert-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
        Atenção
    </h4>
    <div class="text-muted">
        Código, tipo, valor e duração não podem ser alterados após a criação do cupom no Stripe. Se precisar modificar esses campos, crie um novo cupom.
    </div>
</div>

<?php
$content = ob_get_clean();

// Dados para o layout
$data = [
    'content' => $content,
    'page_title' => 'Editar Cupom',
    'active_tab' => 'cupons'
];

// Carregar layout
$this->load->view('admin/settings/layout_tabler', $data);
?>
