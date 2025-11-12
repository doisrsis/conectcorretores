<?php
/**
 * Criar Cupom - Tabler
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
        <h3 class="card-title">Criar Novo Cupom</h3>
        <div class="card-actions">
            <a href="<?php echo base_url('settings/cupons'); ?>" class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                Voltar
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="post" action="<?php echo base_url('settings/cupons_create'); ?>">
            
            <div class="row">
                <!-- Código do Cupom -->
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Código do Cupom</label>
                    <input type="text" 
                           name="codigo" 
                           class="form-control text-uppercase" 
                           required
                           maxlength="50"
                           placeholder="Ex: BEMVINDO, PROMO20"
                           value="<?php echo set_value('codigo'); ?>">
                    <small class="form-hint">Apenas letras, números, hífens e underscores.</small>
                </div>

                <!-- Descrição -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Descrição</label>
                    <input type="text" 
                           name="descricao" 
                           class="form-control" 
                           maxlength="500"
                           placeholder="Descrição interna do cupom"
                           value="<?php echo set_value('descricao'); ?>">
                </div>
            </div>

            <div class="row">
                <!-- Tipo de Desconto -->
                <div class="col-md-4 mb-3">
                    <label class="form-label required">Tipo de Desconto</label>
                    <select name="tipo" class="form-select" required id="tipo" onchange="updateValorLabel()">
                        <option value="percent" <?php echo set_select('tipo', 'percent', true); ?>>Percentual (%)</option>
                        <option value="fixed" <?php echo set_select('tipo', 'fixed'); ?>>Valor Fixo (R$)</option>
                    </select>
                </div>

                <!-- Valor -->
                <div class="col-md-4 mb-3">
                    <label class="form-label required" id="valor-label">Valor do Desconto (%)</label>
                    <input type="number" 
                           name="valor" 
                           class="form-control" 
                           required
                           step="0.01"
                           min="0.01"
                           placeholder="Ex: 20"
                           value="<?php echo set_value('valor'); ?>">
                </div>

                <!-- Duração -->
                <div class="col-md-4 mb-3">
                    <label class="form-label required">Duração</label>
                    <select name="duracao" class="form-select" required id="duracao" onchange="toggleDuracaoMeses()">
                        <option value="once" <?php echo set_select('duracao', 'once', true); ?>>Uma Vez</option>
                        <option value="repeating" <?php echo set_select('duracao', 'repeating'); ?>>Recorrente</option>
                        <option value="forever" <?php echo set_select('duracao', 'forever'); ?>>Para Sempre</option>
                    </select>
                    <small class="form-hint">"Para Sempre" apenas para cupons percentuais.</small>
                </div>
            </div>

            <!-- Duração em Meses (condicional) -->
            <div class="row" id="duracao-meses-container" style="display: none;">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Duração em Meses</label>
                    <input type="number" 
                           name="duracao_meses" 
                           class="form-control" 
                           min="1"
                           placeholder="Ex: 3"
                           value="<?php echo set_value('duracao_meses'); ?>">
                    <small class="form-hint">Número de meses que o desconto será aplicado.</small>
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
                           value="<?php echo set_value('max_usos'); ?>">
                    <small class="form-hint">Limite total de vezes que o cupom pode ser usado.</small>
                </div>

                <!-- Válido De -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Válido De</label>
                    <input type="date" 
                           name="valido_de" 
                           class="form-control" 
                           value="<?php echo set_value('valido_de'); ?>">
                </div>

                <!-- Válido Até -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Válido Até</label>
                    <input type="date" 
                           name="valido_ate" 
                           class="form-control" 
                           value="<?php echo set_value('valido_ate'); ?>">
                </div>
            </div>

            <!-- Ativo -->
            <div class="mb-3">
                <label class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="ativo" value="1" <?php echo set_checkbox('ativo', '1', true); ?>>
                    <span class="form-check-label">Cupom ativo (disponível para uso)</span>
                </label>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                    Criar Cupom
                </button>
                <a href="<?php echo base_url('settings/cupons'); ?>" class="btn btn-link">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function updateValorLabel() {
    const tipo = document.getElementById('tipo').value;
    const label = document.getElementById('valor-label');
    
    if (tipo === 'percent') {
        label.textContent = 'Valor do Desconto (%)';
    } else {
        label.textContent = 'Valor do Desconto (R$)';
    }
}

function toggleDuracaoMeses() {
    const duracao = document.getElementById('duracao').value;
    const container = document.getElementById('duracao-meses-container');
    
    if (duracao === 'repeating') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    updateValorLabel();
    toggleDuracaoMeses();
});
</script>

<?php
$content = ob_get_clean();

// Dados para o layout
$data = [
    'content' => $content,
    'page_title' => 'Criar Cupom',
    'active_tab' => 'cupons'
];

// Carregar layout
$this->load->view('admin/settings/layout_tabler', $data);
?>
