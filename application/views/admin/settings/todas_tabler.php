<?php
/**
 * Todas as Configurações - Tabler
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
        <h3 class="card-title">Todas as Configurações</h3>
        <div class="card-actions">
            <a href="<?php echo base_url('settings/limpar_cache'); ?>" class="btn btn-icon" title="Limpar Cache">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="accordion" id="accordionSettings">
            <?php foreach ($grouped_settings as $grupo => $settings): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?php echo $grupo; ?>">
                        <button class="accordion-button <?php echo $grupo !== array_key_first($grouped_settings) ? 'collapsed' : ''; ?>" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#collapse<?php echo $grupo; ?>" 
                                aria-expanded="<?php echo $grupo === array_key_first($grouped_settings) ? 'true' : 'false'; ?>" 
                                aria-controls="collapse<?php echo $grupo; ?>">
                            <strong><?php echo ucfirst($grupo); ?></strong>
                            <span class="badge bg-secondary ms-2"><?php echo count($settings); ?></span>
                        </button>
                    </h2>
                    <div id="collapse<?php echo $grupo; ?>" 
                         class="accordion-collapse collapse <?php echo $grupo === array_key_first($grouped_settings) ? 'show' : ''; ?>" 
                         aria-labelledby="heading<?php echo $grupo; ?>" 
                         data-bs-parent="#accordionSettings">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Chave</th>
                                            <th>Valor</th>
                                            <th>Tipo</th>
                                            <th>Descrição</th>
                                            <th class="w-1">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($settings as $setting): ?>
                                            <tr>
                                                <td>
                                                    <code><?php echo $setting->chave; ?></code>
                                                </td>
                                                <td>
                                                    <?php if ($setting->tipo === 'bool'): ?>
                                                        <?php if ($setting->valor == '1'): ?>
                                                            <span class="badge bg-success">Ativado</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Desativado</span>
                                                        <?php endif; ?>
                                                    <?php elseif (strlen($setting->valor) > 50): ?>
                                                        <span class="text-muted"><?php echo substr(htmlspecialchars($setting->valor), 0, 50); ?>...</span>
                                                    <?php else: ?>
                                                        <?php echo htmlspecialchars($setting->valor); ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge"><?php echo $setting->tipo; ?></span>
                                                </td>
                                                <td class="text-muted">
                                                    <?php echo $setting->descricao ?: '-'; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo base_url('settings/' . $grupo); ?>" class="btn btn-sm btn-icon" title="Editar">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

// Dados para o layout
$data = [
    'content' => $content,
    'page_title' => 'Todas as Configurações',
    'active_tab' => 'todas'
];

// Carregar layout
$this->load->view('admin/settings/layout_tabler', $data);
?>
