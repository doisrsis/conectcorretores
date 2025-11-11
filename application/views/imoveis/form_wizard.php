<?php
/**
 * Formulário de Imóveis - Wizard Multi-Step
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

<!-- Wizard Card -->
<div class="card">
    <!-- Steps Header -->
    <div class="card-header">
        <ul class="steps steps-counter steps-lime" id="wizard-steps-indicators">
            <li class="step-item" data-step="1">
                <span class="h4">Localização</span>
            </li>
            <li class="step-item" data-step="2">
                <span class="h4">Características</span>
            </li>
            <li class="step-item" data-step="3">
                <span class="h4">Valores</span>
            </li>
            <li class="step-item" data-step="4">
                <span class="h4">Informações Extras</span>
            </li>
        </ul>
    </div>

    <!-- Form -->
    <?php echo form_open(isset($imovel) ? 'imoveis/editar/' . $imovel->id : 'imoveis/novo', ['id' => 'wizard-form']); ?>
    
    <div class="card-body">
        <!-- STEP 1: Localização -->
        <div class="wizard-step" id="step-1">
            <h3 class="mb-4">Localização do Imóvel</h3>
            
            <!-- Busca por CEP -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <label class="form-label">CEP</label>
                    <input type="text"
                           id="cep"
                           name="cep"
                           value="<?php echo set_value('cep', isset($imovel) ? $imovel->cep : ''); ?>"
                           class="form-control"
                           placeholder="00000-000"
                           maxlength="9">
                    <small class="form-hint">Digite o CEP e clique em Buscar</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" id="btn-buscar-cep" class="btn btn-primary w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                        Buscar CEP
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Estado (UF)</label>
                    <select id="estado_id" name="estado_id" class="form-select" required>
                        <option value="">Carregando...</option>
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

        <!-- STEP 2: Características -->
        <div class="wizard-step" id="step-2" style="display: none;">
            <h3 class="mb-4">Características do Imóvel</h3>
            
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

        <!-- STEP 3: Valores -->
        <div class="wizard-step" id="step-3" style="display: none;">
            <h3 class="mb-4">Valores do Imóvel</h3>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Preço (R$)</label>
                    <input type="text"
                           id="preco"
                           name="preco"
                           value="<?php echo set_value('preco', isset($imovel) ? number_format($imovel->preco, 2, ',', '.') : ''); ?>"
                           class="form-control"
                           placeholder="0,00"
                           required>
                    <small class="form-hint">Digite apenas números, a máscara será aplicada automaticamente</small>
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
            
            <div class="alert alert-info">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                <div>O valor por m² será calculado automaticamente</div>
            </div>
        </div>

        <!-- STEP 4: Informações Extras -->
        <div class="wizard-step" id="step-4" style="display: none;">
            <h3 class="mb-4">Informações Extras</h3>
            
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
            
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                <div>
                    <h4 class="alert-title">Pronto para finalizar!</h4>
                    <div class="text-secondary">Revise as informações e clique em "Salvar Imóvel" para concluir.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="card-footer">
        <div class="d-flex justify-content-between">
            <button type="button" id="btn-prev" class="btn btn-link" style="display: none;">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                Anterior
            </button>
            
            <div class="ms-auto">
                <a href="<?php echo base_url('imoveis'); ?>" class="btn btn-link">Cancelar</a>
                <button type="button" id="btn-next" class="btn btn-primary">
                    Próximo
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                </button>
                <button type="submit" id="btn-submit" class="btn btn-success" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                    <?php echo isset($imovel) ? 'Atualizar Imóvel' : 'Salvar Imóvel'; ?>
                </button>
            </div>
        </div>
    </div>

    <?php echo form_close(); ?>
</div>

<!-- IMask CDN -->
<script src="https://unpkg.com/imask"></script>

<!-- Wizard Script -->
<script>
const baseUrl = '<?php echo base_url(); ?>';
const isEdit = <?php echo isset($imovel) ? 'true' : 'false'; ?>;
const imovelData = <?php echo isset($imovel) ? json_encode($imovel) : 'null'; ?>;

document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 4;
    
    // Elementos
    const steps = document.querySelectorAll('.step-item');
    const wizardSteps = document.querySelectorAll('.wizard-step');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const btnSubmit = document.getElementById('btn-submit');
    
    // Navegação
    function showStep(step) {
        // Esconder todos os steps
        wizardSteps.forEach(s => s.style.display = 'none');
        
        // Mostrar step atual
        document.getElementById('step-' + step).style.display = 'block';
        
        // Atualizar indicadores - REMOVER active de todos e adicionar apenas no atual
        steps.forEach((s) => {
            const stepNumber = parseInt(s.getAttribute('data-step'));
            if (stepNumber === step) {
                s.classList.add('active');    // Apenas o step atual fica verde
            } else {
                s.classList.remove('active'); // Todos os outros ficam cinza
            }
        });
        
        // Controlar botões
        btnPrev.style.display = step === 1 ? 'none' : 'inline-block';
        btnNext.style.display = step === totalSteps ? 'none' : 'inline-block';
        btnSubmit.style.display = step === totalSteps ? 'inline-block' : 'none';
        
        currentStep = step;
        
        // Scroll suave para o topo do card
        document.querySelector('.card').scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Log para debug
        console.log('Step atual:', step);
        console.log('Indicador ativo:', Array.from(steps).find(s => s.classList.contains('active'))?.getAttribute('data-step'));
    }
    
    btnNext.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            showStep(currentStep + 1);
        }
    });
    
    btnPrev.addEventListener('click', () => {
        showStep(currentStep - 1);
    });
    
    function validateStep(step) {
        const stepElement = document.getElementById('step-' + step);
        const requiredInputs = stepElement.querySelectorAll('[required]');
        let isValid = true;
        
        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            alert('Por favor, preencha todos os campos obrigatórios.');
        }
        
        return isValid;
    }
    
    // Máscaras
    const cepMask = IMask(document.getElementById('cep'), {
        mask: '00000-000'
    });
    
    const precoMask = IMask(document.getElementById('preco'), {
        mask: Number,
        scale: 2,
        signed: false,
        thousandsSeparator: '.',
        radix: ',',
        mapToRadix: ['.'],
        padFractionalZeros: true,
        normalizeZeros: true,
        min: 0
    });
    
    const areaMask = IMask(document.getElementById('area_privativa'), {
        mask: Number,
        scale: 0,
        signed: false,
        thousandsSeparator: '.',
        min: 0
    });
    
    // Inicializar wizard no step 1
    showStep(1);
    
    // Carregar Estados
    carregarEstados();
    
    function carregarEstados() {
        fetch(baseUrl + 'imoveis/get_estados', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const estadoSelect = document.getElementById('estado_id');
            estadoSelect.innerHTML = '<option value="">Selecione...</option>';
            
            if (data.success && data.estados) {
                data.estados.forEach(estado => {
                    const option = document.createElement('option');
                    option.value = estado.id;
                    option.textContent = estado.nome + ' (' + estado.uf + ')';
                    option.dataset.uf = estado.uf;
                    estadoSelect.appendChild(option);
                });
                
                // Se editando, selecionar estado
                if (isEdit && imovelData && imovelData.estado_id) {
                    estadoSelect.value = imovelData.estado_id;
                    carregarCidades(imovelData.estado_id, imovelData.cidade_id);
                }
            }
        })
        .catch(error => console.error('Erro ao carregar estados:', error));
    }
    
    // Carregar Cidades
    document.getElementById('estado_id').addEventListener('change', function() {
        if (this.value) {
            carregarCidades(this.value);
        }
    });
    
    function carregarCidades(estadoId, cidadeIdSelecionada = null, cidadeNome = null) {
        const formData = new FormData();
        formData.append('estado_id', estadoId);
        
        fetch(baseUrl + 'imoveis/get_cidades', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Cidades recebidas:', data);
            
            const cidadeSelect = document.getElementById('cidade_id');
            cidadeSelect.innerHTML = '<option value="">Selecione...</option>';
            
            if (data.success && data.cidades && data.cidades.length > 0) {
                data.cidades.forEach(cidade => {
                    const option = document.createElement('option');
                    option.value = cidade.id;
                    option.textContent = cidade.nome;
                    cidadeSelect.appendChild(option);
                });
                
                // Selecionar por ID
                if (cidadeIdSelecionada) {
                    cidadeSelect.value = cidadeIdSelecionada;
                    console.log('Cidade selecionada por ID:', cidadeIdSelecionada);
                }
                // Selecionar por nome (busca de CEP)
                else if (cidadeNome) {
                    console.log('Procurando cidade por nome:', cidadeNome);
                    const cidadeOption = Array.from(cidadeSelect.options).find(opt => 
                        opt.textContent.toLowerCase().trim() === cidadeNome.toLowerCase().trim()
                    );
                    if (cidadeOption) {
                        cidadeSelect.value = cidadeOption.value;
                        console.log('Cidade encontrada e selecionada:', cidadeOption.textContent);
                    } else {
                        console.warn('Cidade não encontrada no select:', cidadeNome);
                        console.log('Cidades disponíveis:', Array.from(cidadeSelect.options).map(o => o.textContent));
                    }
                }
            } else {
                console.warn('Nenhuma cidade retornada para o estado');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar cidades:', error);
            alert('Erro ao carregar cidades. Verifique o console.');
        });
    }
    
    // Buscar CEP
    document.getElementById('btn-buscar-cep').addEventListener('click', function() {
        const cep = document.getElementById('cep').value.replace(/\D/g, '');
        
        if (cep.length !== 8) {
            alert('CEP inválido');
            return;
        }
        
        const btnBuscar = this;
        btnBuscar.disabled = true;
        btnBuscar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Buscando...';
        
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição');
                }
                return response.json();
            })
            .then(data => {
                console.log('Dados do CEP:', data);
                
                if (data.erro) {
                    alert('CEP não encontrado');
                    return;
                }
                
                // Preencher bairro
                document.getElementById('bairro').value = data.bairro || '';
                console.log('Bairro preenchido:', data.bairro);
                
                // Selecionar estado
                const estadoSelect = document.getElementById('estado_id');
                const estadoOption = Array.from(estadoSelect.options).find(opt => 
                    opt.dataset.uf === data.uf
                );
                
                console.log('Estado encontrado:', estadoOption ? estadoOption.textContent : 'não encontrado');
                
                if (estadoOption) {
                    estadoSelect.value = estadoOption.value;
                    console.log('Estado selecionado:', estadoOption.value, 'UF:', data.uf);
                    console.log('Cidade a buscar:', data.localidade);
                    
                    // Carregar cidades e depois selecionar
                    carregarCidades(estadoOption.value, null, data.localidade);
                } else {
                    console.error('Estado não encontrado para UF:', data.uf);
                }
            })
            .catch(error => {
                console.error('Erro ao buscar CEP:', error);
                alert('Erro ao buscar CEP. Tente novamente.');
            })
            .finally(() => {
                btnBuscar.disabled = false;
                btnBuscar.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg> Buscar CEP';
            });
    });
});
</script>

<?php
// Capturar conteúdo
$data_layout['content'] = ob_get_clean();

// Carregar layout
$this->load->view('templates/tabler/layout', $data_layout);
?>
