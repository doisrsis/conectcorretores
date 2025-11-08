<?php
/**
 * Partial: Formulário Genérico de Configurações
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 08/11/2025
 * 
 * Variáveis esperadas:
 * - $settings: array de configurações
 * - $grupo: nome do grupo
 * - $icon: ícone do grupo
 * - $description: descrição do grupo
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?php echo base_url('settings/' . $grupo); ?>">
            
            <?php if (empty($settings)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nenhuma configuração editável encontrada neste grupo.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($settings as $setting): ?>
                        <div class="col-md-6 mb-4">
                            <div class="border rounded p-3 h-100">
                                <label class="form-label fw-bold">
                                    <?php echo ucfirst(str_replace('_', ' ', str_replace($grupo . '_', '', $setting->chave))); ?>
                                </label>
                                
                                <?php if ($setting->descricao): ?>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-info-circle"></i> <?php echo $setting->descricao; ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($setting->tipo === 'bool'): ?>
                                    <!-- Checkbox para boolean -->
                                    <div class="form-check form-switch">
                                        <input 
                                            type="checkbox" 
                                            class="form-check-input" 
                                            name="<?php echo $setting->chave; ?>"
                                            id="<?php echo $setting->chave; ?>"
                                            value="1"
                                            <?php echo $setting->valor == '1' ? 'checked' : ''; ?>
                                        >
                                        <label class="form-check-label" for="<?php echo $setting->chave; ?>">
                                            <?php echo $setting->valor == '1' ? 'Ativado' : 'Desativado'; ?>
                                        </label>
                                    </div>

                                <?php elseif ($setting->tipo === 'int' || $setting->tipo === 'float'): ?>
                                    <!-- Input numérico -->
                                    <input 
                                        type="number" 
                                        class="form-control" 
                                        name="<?php echo $setting->chave; ?>"
                                        id="<?php echo $setting->chave; ?>"
                                        value="<?php echo $setting->valor; ?>"
                                        min="0"
                                        <?php echo $setting->tipo === 'float' ? 'step="0.01"' : ''; ?>
                                        required
                                    >

                                <?php elseif ($setting->tipo === 'json'): ?>
                                    <!-- Textarea para JSON -->
                                    <textarea 
                                        class="form-control font-monospace" 
                                        name="<?php echo $setting->chave; ?>"
                                        id="<?php echo $setting->chave; ?>"
                                        rows="4"
                                    ><?php echo $setting->valor; ?></textarea>

                                <?php else: ?>
                                    <!-- Input de texto padrão -->
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        name="<?php echo $setting->chave; ?>"
                                        id="<?php echo $setting->chave; ?>"
                                        value="<?php echo htmlspecialchars($setting->valor); ?>"
                                        required
                                    >
                                <?php endif; ?>

                                <!-- Valor atual -->
                                <div class="mt-2">
                                    <small class="text-muted">
                                        Valor atual: <code><?php echo htmlspecialchars($setting->valor); ?></code>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Botões de Ação -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            As alterações serão aplicadas imediatamente após salvar.
                        </small>
                    </div>
                    <div>
                        <a href="<?php echo base_url('admin'); ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Configurações
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
// Atualizar label do switch quando mudar
document.querySelectorAll('.form-check-input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const label = this.nextElementSibling;
        label.textContent = this.checked ? 'Ativado' : 'Desativado';
    });
});
</script>
