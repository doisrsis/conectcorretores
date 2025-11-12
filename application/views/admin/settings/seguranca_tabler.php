<?php
/**
 * Configurações de Segurança - Tabler
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
        <h3 class="card-title">Configurações de Segurança</h3>
        <div class="card-actions">
            <a href="<?php echo base_url('settings/limpar_cache'); ?>" class="btn btn-icon" title="Limpar Cache">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="post" action="<?php echo base_url('settings/seguranca'); ?>">
            <?php foreach ($settings as $setting): ?>
                <div class="mb-3">
                    <label class="form-label">
                        <?php echo ucfirst(str_replace('_', ' ', $setting->chave)); ?>
                        <?php if ($setting->descricao): ?>
                            <span class="form-label-description"><?php echo $setting->descricao; ?></span>
                        <?php endif; ?>
                    </label>
                    
                    <?php if ($setting->tipo === 'bool'): ?>
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="<?php echo $setting->chave; ?>" value="1" <?php echo $setting->valor == '1' ? 'checked' : ''; ?>>
                            <span class="form-check-label">Ativado</span>
                        </label>
                    
                    <?php elseif ($setting->tipo === 'text'): ?>
                        <textarea class="form-control" name="<?php echo $setting->chave; ?>" rows="3"><?php echo htmlspecialchars($setting->valor); ?></textarea>
                    
                    <?php elseif ($setting->tipo === 'int' || $setting->tipo === 'float'): ?>
                        <input type="number" class="form-control" name="<?php echo $setting->chave; ?>" value="<?php echo htmlspecialchars($setting->valor); ?>" step="<?php echo $setting->tipo === 'float' ? '0.01' : '1'; ?>">
                    
                    <?php else: ?>
                        <input type="text" class="form-control" name="<?php echo $setting->chave; ?>" value="<?php echo htmlspecialchars($setting->valor); ?>">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                    Salvar Configurações
                </button>
                <a href="<?php echo base_url('settings'); ?>" class="btn btn-link">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();

// Dados para o layout
$data = [
    'content' => $content,
    'page_title' => 'Configurações de Segurança',
    'active_tab' => 'seguranca'
];

// Carregar layout
$this->load->view('admin/settings/layout_tabler', $data);
?>
