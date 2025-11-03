/**
 * JavaScript do Formulário de Imóveis
 * Autor: Rafael Dias - doisr.com.br
 * Data: 18/10/2025
 */

document.addEventListener('DOMContentLoaded', function() {

    // ========================================
    // Máscaras com IMask.js
    // ========================================

    // Máscara de CEP
    const cepMask = IMask(document.getElementById('cep'), {
        mask: '00000-000'
    });

    // Máscara de Preço (R$) - Centavos sempre visíveis
    const precoInput = document.getElementById('preco');
    const precoMask = IMask(precoInput, {
        mask: 'R$ num',
        lazy: false,
        blocks: {
            num: {
                mask: Number,
                scale: 2,
                signed: false,
                thousandsSeparator: '.',
                radix: ',',
                mapToRadix: ['.'],
                padFractionalZeros: true,
                normalizeZeros: true,
                min: 0,
                max: 999999999.99
            }
        }
    });

    // Forçar atualização ao perder foco para garantir centavos
    precoInput.addEventListener('blur', function() {
        if (precoMask.value && precoMask.value !== 'R$ ') {
            precoMask.updateValue();
        }
    });

    // Máscara de Área Privativa (m²) - apenas números inteiros com separador de milhares
    const areaMask = IMask(document.getElementById('area_privativa'), {
        mask: Number,
        scale: 0,  // Sem casas decimais
        signed: false,
        thousandsSeparator: '.',
        min: 0
    });

    // ========================================
    // Carregar Estados
    // ========================================

    const estadoSelect = document.getElementById('estado_id');
    const cidadeSelect = document.getElementById('cidade_id');
    const bairroInput = document.getElementById('bairro');

    // Carregar estados ao iniciar
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
            if (data.success) {
                estadoSelect.innerHTML = '<option value="">Selecione...</option>';
                data.estados.forEach(estado => {
                    const option = document.createElement('option');
                    option.value = estado.id;
                    option.textContent = estado.nome + ' (' + estado.uf + ')';
                    option.dataset.uf = estado.uf;
                    estadoSelect.appendChild(option);
                });

                // Se estiver editando, selecionar estado
                if (isEdit && imovelData && imovelData.estado_id) {
                    estadoSelect.value = imovelData.estado_id;
                    carregarCidades(imovelData.estado_id, imovelData.cidade_id);
                }
            }
        })
        .catch(error => {
            console.error('Erro ao carregar estados:', error);
            mostrarAlerta('Erro ao carregar lista de estados. Recarregue a página.', 'error');
        });
    }

    // ========================================
    // Carregar Cidades ao selecionar Estado
    // ========================================

    estadoSelect.addEventListener('change', function() {
        const estadoId = this.value;

        if (!estadoId) {
            cidadeSelect.innerHTML = '<option value="">Selecione o estado primeiro</option>';
            cidadeSelect.disabled = true;
            return;
        }

        carregarCidades(estadoId);
    });

    function carregarCidades(estadoId, cidadeIdSelecionada = null) {
        cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
        cidadeSelect.disabled = true;

        fetch(baseUrl + 'imoveis/get_cidades', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'estado_id=' + estadoId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cidadeSelect.innerHTML = '<option value="">Selecione...</option>';

                if (data.cidades.length === 0) {
                    cidadeSelect.innerHTML = '<option value="">Nenhuma cidade cadastrada</option>';
                } else {
                    data.cidades.forEach(cidade => {
                        const option = document.createElement('option');
                        option.value = cidade.id;
                        option.textContent = cidade.nome;
                        cidadeSelect.appendChild(option);
                    });
                }

                cidadeSelect.disabled = false;

                // Se tiver cidade pré-selecionada
                if (cidadeIdSelecionada) {
                    cidadeSelect.value = cidadeIdSelecionada;
                }
            }
        })
        .catch(error => {
            console.error('Erro ao carregar cidades:', error);
            cidadeSelect.innerHTML = '<option value="">Erro ao carregar</option>';
        });
    }

    // ========================================
    // Buscar CEP
    // ========================================

    const btnBuscarCep = document.getElementById('btn-buscar-cep');

    btnBuscarCep.addEventListener('click', function() {
        const cep = cepMask.unmaskedValue;

        if (!cep || cep.length !== 8) {
            mostrarAlerta('Digite um CEP válido com 8 dígitos', 'error');
            return;
        }

        // Desabilitar botão
        btnBuscarCep.disabled = true;
        btnBuscarCep.textContent = '🔄 Buscando...';

        fetch(baseUrl + 'imoveis/buscar_cep', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'cep=' + cep
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Preencher campos
                estadoSelect.value = data.data.estado_id;

                // Carregar cidades e selecionar
                carregarCidades(data.data.estado_id, data.data.cidade_id);

                // Preencher bairro
                bairroInput.value = data.data.bairro;

                // Adicionar classe visual de bloqueio (mas não desabilitar para enviar no form)
                estadoSelect.classList.add('bg-gray-100', 'cursor-not-allowed');
                cidadeSelect.classList.add('bg-gray-100', 'cursor-not-allowed');
                estadoSelect.style.pointerEvents = 'none';
                cidadeSelect.style.pointerEvents = 'none';
                bairroInput.readOnly = false;

                // Adicionar botão para limpar
                adicionarBotaoLimpar();

                mostrarAlerta('CEP encontrado! Dados preenchidos automaticamente.', 'success');
            } else {
                mostrarAlerta(data.message + '. Preencha manualmente os campos.', 'error');

                // Habilitar preenchimento manual
                estadoSelect.disabled = false;
                cidadeSelect.disabled = false;
                bairroInput.readOnly = false;
            }
        })
        .catch(error => {
            console.error('Erro ao buscar CEP:', error);
            mostrarAlerta('Erro ao buscar CEP. Verifique sua conexão e tente novamente.', 'error');

            estadoSelect.disabled = false;
            cidadeSelect.disabled = false;
            bairroInput.readOnly = false;
        })
        .finally(() => {
            btnBuscarCep.disabled = false;
            btnBuscarCep.textContent = '🔍 Buscar';
        });
    });

    // ========================================
    // Adicionar botão para limpar CEP
    // ========================================

    function adicionarBotaoLimpar() {
        // Verificar se já existe
        if (document.getElementById('btn-limpar-cep')) return;

        const btnLimpar = document.createElement('button');
        btnLimpar.type = 'button';
        btnLimpar.id = 'btn-limpar-cep';
        btnLimpar.className = 'btn-secondary px-4 text-sm';
        btnLimpar.textContent = '🗑️ Limpar';

        btnLimpar.addEventListener('click', function() {
            cepMask.value = '';
            estadoSelect.value = '';
            estadoSelect.classList.remove('bg-gray-100', 'cursor-not-allowed');
            estadoSelect.style.pointerEvents = '';
            cidadeSelect.innerHTML = '<option value="">Selecione o estado primeiro</option>';
            cidadeSelect.classList.remove('bg-gray-100', 'cursor-not-allowed');
            cidadeSelect.style.pointerEvents = '';
            cidadeSelect.disabled = true;
            bairroInput.value = '';
            bairroInput.readOnly = false;
            this.remove();
        });

        btnBuscarCep.parentElement.appendChild(btnLimpar);
    }

    // ========================================
    // Validação Dinâmica de Quartos/Vagas
    // ========================================
    
    const tipoImovelSelect = document.querySelector('select[name="tipo_imovel"]');
    const quartosSelect = document.querySelector('select[name="quartos"]');
    const vagasSelect = document.querySelector('select[name="vagas"]');

    // Atualizar obrigatoriedade ao mudar tipo de imóvel
    tipoImovelSelect.addEventListener('change', function() {
        const tipo = this.value;
        const requerQuartosVagas = (tipo === 'Casa' || tipo === 'Apartamento');
        
        if (requerQuartosVagas) {
            quartosSelect.required = true;
            vagasSelect.required = true;
            quartosSelect.parentElement.querySelector('label').innerHTML = 'Quantidade de Quartos *';
            vagasSelect.parentElement.querySelector('label').innerHTML = 'Quantidade de Vagas *';
        } else {
            quartosSelect.required = false;
            vagasSelect.required = false;
            quartosSelect.parentElement.querySelector('label').innerHTML = 'Quantidade de Quartos';
            vagasSelect.parentElement.querySelector('label').innerHTML = 'Quantidade de Vagas';
        }
    });

    // Verificar no carregamento (modo edição)
    if (tipoImovelSelect.value) {
        tipoImovelSelect.dispatchEvent(new Event('change'));
    }

    // ========================================
    // Validação antes de enviar
    // ========================================

    const form = document.getElementById('form-imovel');

    form.addEventListener('submit', function(e) {
        // Limpar alertas anteriores
        removerAlertas();

        // Validar se estado e cidade estão selecionados
        if (!estadoSelect.value) {
            e.preventDefault();
            mostrarAlerta('Por favor, selecione o Estado (UF)', 'error');
            estadoSelect.focus();
            return false;
        }

        if (!cidadeSelect.value) {
            e.preventDefault();
            mostrarAlerta('Por favor, selecione a Cidade', 'error');
            cidadeSelect.focus();
            return false;
        }

        // Validar preço
        const preco = parseFloat(precoMask.unmaskedValue.replace(',', '.'));
        if (!preco || preco <= 0) {
            e.preventDefault();
            mostrarAlerta('Por favor, informe um preço válido', 'error');
            precoInput.focus();
            return false;
        }

        // Validar área
        const area = parseFloat(areaMask.unmaskedValue);
        if (!area || area <= 0) {
            e.preventDefault();
            mostrarAlerta('Por favor, informe uma área válida', 'error');
            areaInput.focus();
            return false;
        }

        // Validar quartos e vagas se for Casa ou Apartamento
        const tipoImovel = tipoImovelSelect.value;
        if (tipoImovel === 'Casa' || tipoImovel === 'Apartamento') {
            if (!quartosSelect.value) {
                e.preventDefault();
                mostrarAlerta('Para imóveis do tipo Casa ou Apartamento, é obrigatório informar a quantidade de quartos', 'error');
                quartosSelect.focus();
                return false;
            }
            if (!vagasSelect.value) {
                e.preventDefault();
                mostrarAlerta('Para imóveis do tipo Casa ou Apartamento, é obrigatório informar a quantidade de vagas', 'error');
                vagasSelect.focus();
                return false;
            }
        }

        return true;
    });

    // ========================================
    // Funções de Alerta
    // ========================================
    
    function mostrarAlerta(mensagem, tipo = 'error') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} flex items-start gap-3 animate-fade-in`;
        alertDiv.setAttribute('role', 'alert');
        
        const icon = tipo === 'error' ? '❌' : tipo === 'success' ? '✅' : 'ℹ️';
        
        alertDiv.innerHTML = `
            <span class="text-xl">${icon}</span>
            <div class="flex-1">
                <p class="font-medium">${mensagem}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        
        // Inserir no topo do formulário
        const formContainer = form.parentElement;
        formContainer.insertBefore(alertDiv, form);
        
        // Scroll suave para o alerta
        alertDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        // Auto-remover após 8 segundos
        setTimeout(() => {
            if (alertDiv.parentElement) {
                alertDiv.style.opacity = '0';
                alertDiv.style.transition = 'opacity 0.3s';
                setTimeout(() => alertDiv.remove(), 300);
            }
        }, 8000);
    }
    
    function removerAlertas() {
        const alertas = document.querySelectorAll('.alert');
        alertas.forEach(alerta => alerta.remove());
    }

    // ========================================
    // Console log para debug
    // ========================================

    console.log('✅ Formulário de Imóveis carregado');
    console.log('Modo:', isEdit ? 'Edição' : 'Criação');
    if (isEdit) {
        console.log('Dados do imóvel:', imovelData);
    }
});
