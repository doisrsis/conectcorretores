<?php
/**
 * Formulário de Imóveis - Tabler Layout
 * Autor: Rafael Dias - doisr.com.br
 * Data: 11/11/2025
 */

// Preparar dados para o layout
$data_layout = [
    'title' => isset($imovel) ? 'Editar Imóvel' : 'Cadastrar Imóvel',
    'page' => 'imoveis',
    'page_header' => isset($imovel) ? 'Editar Imóvel' : 'Cadastrar Novo Imóvel',
    'page_pretitle' => 'Imóveis',
    'page_actions' => '
        <a href="' . base_url('imoveis') . '" class="btn btn-outline-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
            Voltar
        </a>
    '
];

// Iniciar conteúdo
ob_start();
?>

<!-- Mensagens de Erro -->
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <div class="d-flex">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
            </div>
            <div>
                <h4 class="alert-title">Erro!</h4>
                <div class="text-secondary"><?php echo $this->session->flashdata('error'); ?></div>
            </div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<?php if (validation_errors()): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <div class="d-flex">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
            </div>
            <div>
                <h4 class="alert-title">Erros de Validação</h4>
                <div class="text-secondary"><?php echo validation_errors(); ?></div>
            </div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<!-- Formulário -->
<?php echo form_open(isset($imovel) ? 'imoveis/editar/' . $imovel->id : 'imoveis/novo', ['id' => 'form-imovel']); ?>

<!-- CEP e Busca -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Buscar Endereço por CEP</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label">CEP</label>
                    <input type="text"
                           id="cep"
                           name="cep"
                           value="<?php echo set_value('cep', isset($imovel) ? $imovel->cep : ''); ?>"
                           class="form-control"
                           placeholder="00000-000"
                           maxlength="9">
                    <small class="form-hint">Digite o CEP e clique em Buscar para preencher automaticamente</small>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <button type="button" id="btn-buscar-cep" class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    Buscar CEP
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Localização -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Localização</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Estado (UF)</label>
                <select id="estado_id" name="estado_id" class="form-select" required>
                    <option value="">Selecione...</option>
                </select>
                <input type="hidden" id="estado_uf" value="<?php echo isset($imovel) ? $imovel->estado : ''; ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">Cidade</label>
                <select id="cidade_id" name="cidade_id" class="form-select" required>
                    <option value="">Selecione o estado primeiro...</option>
                </select>
                <input type="hidden" id="cidade_nome" value="<?php echo isset($imovel) ? $imovel->cidade : ''; ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label required">Bairro</label>
            <input type="text"
                   id="bairro"
                   name="bairro"
                   value="<?php echo set_value('bairro', isset($imovel) ? $imovel->bairro : ''); ?>"
                   class="form-control"
                   placeholder="Centro"
                   required>
        </div>
    </div>
</div>

<!-- Características do Imóvel -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Características do Imóvel</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Tipo de Negócio</label>
                <select name="tipo_negocio" class="form-select" required>
                    <option value="">Selecione...</option>
                    <option value="compra" <?php echo set_select('tipo_negocio', 'compra', isset($imovel) && $imovel->tipo_negocio == 'compra'); ?>>Venda</option>
                    <option value="aluguel" <?php echo set_select('tipo_negocio', 'aluguel', isset($imovel) && $imovel->tipo_negocio == 'aluguel'); ?>>Aluguel</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">Tipo de Imóvel</label>
                <select name="tipo_imovel" class="form-select" required>
                    <option value="">Selecione...</option>
                    <option value="casa" <?php echo set_select('tipo_imovel', 'casa', isset($imovel) && $imovel->tipo_imovel == 'casa'); ?>>Casa</option>
                    <option value="apartamento" <?php echo set_select('tipo_imovel', 'apartamento', isset($imovel) && $imovel->tipo_imovel == 'apartamento'); ?>>Apartamento</option>
                    <option value="terreno" <?php echo set_select('tipo_imovel', 'terreno', isset($imovel) && $imovel->tipo_imovel == 'terreno'); ?>>Terreno</option>
                    <option value="comercial" <?php echo set_select('tipo_imovel', 'comercial', isset($imovel) && $imovel->tipo_imovel == 'comercial'); ?>>Comercial</option>
                    <option value="rural" <?php echo set_select('tipo_imovel', 'rural', isset($imovel) && $imovel->tipo_imovel == 'rural'); ?>>Rural</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Quantidade de Quartos</label>
                <select name="quartos" class="form-select">
                    <option value="">Selecione...</option>
                    <option value="1" <?php echo set_select('quartos', '1', isset($imovel) && $imovel->quartos == 1); ?>>1</option>
                    <option value="2" <?php echo set_select('quartos', '2', isset($imovel) && $imovel->quartos == 2); ?>>2</option>
                    <option value="3" <?php echo set_select('quartos', '3', isset($imovel) && $imovel->quartos == 3); ?>>3</option>
                    <option value="4" <?php echo set_select('quartos', '4', isset($imovel) && $imovel->quartos == 4); ?>>4</option>
                    <option value="5" <?php echo set_select('quartos', '5', isset($imovel) && $imovel->quartos >= 5); ?>>5 ou mais</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Quantidade de Vagas</label>
                <select name="vagas" class="form-select">
                    <option value="">Selecione...</option>
                    <option value="1" <?php echo set_select('vagas', '1', isset($imovel) && $imovel->vagas == 1); ?>>1</option>
                    <option value="2" <?php echo set_select('vagas', '2', isset($imovel) && $imovel->vagas == 2); ?>>2</option>
                    <option value="3" <?php echo set_select('vagas', '3', isset($imovel) && $imovel->vagas == 3); ?>>3</option>
                    <option value="4" <?php echo set_select('vagas', '4', isset($imovel) && $imovel->vagas == 4); ?>>4</option>
                    <option value="5" <?php echo set_select('vagas', '5', isset($imovel) && $imovel->vagas >= 5); ?>>5 ou mais</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Valores -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Valores</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required">Preço (R$)</label>
                <input type="text"
                       id="preco"
                       name="preco"
                       value="<?php echo set_value('preco', isset($imovel) ? number_format($imovel->preco, 2, ',', '.') : ''); ?>"
                       class="form-control"
                       placeholder="R$ 0,00"
                       required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required">Área Privativa (m²)</label>
                <input type="text"
                       id="area_privativa"
                       name="area_privativa"
                       value="<?php echo set_value('area_privativa', isset($imovel) ? $imovel->area_privativa : ''); ?>"
                       class="form-control"
                       placeholder="90"
                       required>
            </div>
        </div>
    </div>
</div>

<!-- Link do Imóvel -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Link do Imóvel</h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">URL do Imóvel <span class="form-label-description">(Opcional)</span></label>
            <input type="url"
                   name="link_imovel"
                   value="<?php echo set_value('link_imovel', isset($imovel) ? $imovel->link_imovel : ''); ?>"
                   class="form-control"
                   placeholder="https://seusite.com.br/imovel/123">
            <small class="form-hint">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9h.01" /><path d="M11 12h1v4h1" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
                Insira o link da página do imóvel no seu site com todos os detalhes
            </small>
        </div>
    </div>
</div>

<!-- Botões de Ação -->
<div class="card">
    <div class="card-footer">
        <div class="d-flex">
            <a href="<?php echo base_url('imoveis'); ?>" class="btn btn-link">Cancelar</a>
            <button type="submit" class="btn btn-primary ms-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                <?php echo isset($imovel) ? 'Atualizar Imóvel' : 'Cadastrar Imóvel'; ?>
            </button>
        </div>
    </div>
</div>

<?php echo form_close(); ?>

<!-- Scripts -->
<script>
const baseUrl = '<?php echo base_url(); ?>';
const isEdit = <?php echo isset($imovel) ? 'true' : 'false'; ?>;
const imovelData = <?php echo isset($imovel) ? json_encode($imovel) : 'null'; ?>;
</script>

<?php
// Capturar conteúdo
$data_layout['content'] = ob_get_clean();

// Carregar layout
$this->load->view('templates/tabler/layout', $data_layout);
?>
