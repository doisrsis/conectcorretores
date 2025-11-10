<?php $this->load->view('templates/dashboard_header'); ?>

<?php $this->load->view('templates/sidebar'); ?>

<!-- Main Content -->
<div class="lg:pl-64 min-h-screen flex flex-col">
    <!-- Top Bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="flex items-center justify-between px-4 py-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <h1 class="text-xl font-semibold text-gray-900">🎟️ Criar Cupom</h1>
            
            <a href="<?php echo base_url('settings/cupons'); ?>" class="btn-outline btn-sm">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8 bg-gray-50">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <?php $this->load->view('admin/settings/_tabs', ['active_tab' => $active_tab]); ?>
            
            <div class="max-w-3xl mx-auto">
            
            <!-- Mensagens -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert-error mb-6">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <!-- Formulário -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600">
                    <h2 class="text-xl font-bold text-white">Novo Cupom de Desconto</h2>
                    <p class="text-blue-100 text-sm mt-1">Preencha os dados para criar um cupom</p>
                </div>

                <form method="post" action="<?php echo base_url('settings/cupons_create'); ?>" class="p-6 space-y-6">
                    
                    <!-- Código do Cupom -->
                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700 mb-2">
                            Código do Cupom *
                        </label>
                        <input type="text" 
                               id="codigo" 
                               name="codigo" 
                               required
                               maxlength="50"
                               placeholder="Ex: BEMVINDO, PROMO20"
                               class="input-field uppercase"
                               value="<?php echo set_value('codigo'); ?>">
                        <p class="text-xs text-gray-500 mt-1">
                            Apenas letras, números, hífens e underscores. Será convertido para MAIÚSCULAS.
                        </p>
                    </div>

                    <!-- Tipo e Valor -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Desconto *
                            </label>
                            <select id="tipo" name="tipo" required class="input-field" onchange="updateValorLabel()">
                                <option value="percent" <?php echo set_select('tipo', 'percent', true); ?>>Percentual (%)</option>
                                <option value="fixed" <?php echo set_select('tipo', 'fixed'); ?>>Valor Fixo (R$)</option>
                            </select>
                        </div>

                        <div>
                            <label for="valor" class="block text-sm font-medium text-gray-700 mb-2">
                                <span id="valor-label">Valor do Desconto (%) *</span>
                            </label>
                            <input type="number" 
                                   id="valor" 
                                   name="valor" 
                                   required
                                   step="0.01"
                                   min="0.01"
                                   placeholder="Ex: 20"
                                   class="input-field"
                                   value="<?php echo set_value('valor'); ?>">
                        </div>
                    </div>

                    <!-- Duração -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="duracao" class="block text-sm font-medium text-gray-700 mb-2">
                                Duração do Desconto *
                            </label>
                            <select id="duracao" name="duracao" required class="input-field" onchange="toggleDuracaoMeses()">
                                <option value="once" <?php echo set_select('duracao', 'once', true); ?>>Uma vez (primeiro pagamento)</option>
                                <option value="repeating" <?php echo set_select('duracao', 'repeating'); ?>>Recorrente (N meses)</option>
                                <option value="forever" <?php echo set_select('duracao', 'forever'); ?>>Para sempre</option>
                            </select>
                        </div>

                        <div id="duracao-meses-wrapper" style="display: none;">
                            <label for="duracao_meses" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantidade de Meses
                            </label>
                            <input type="number" 
                                   id="duracao_meses" 
                                   name="duracao_meses" 
                                   min="1"
                                   placeholder="Ex: 3"
                                   class="input-field"
                                   value="<?php echo set_value('duracao_meses'); ?>">
                        </div>
                    </div>

                    <!-- Limite de Usos -->
                    <div>
                        <label for="max_usos" class="block text-sm font-medium text-gray-700 mb-2">
                            Limite de Usos
                        </label>
                        <input type="number" 
                               id="max_usos" 
                               name="max_usos" 
                               min="1"
                               placeholder="Deixe em branco para ilimitado"
                               class="input-field"
                               value="<?php echo set_value('max_usos'); ?>">
                        <p class="text-xs text-gray-500 mt-1">
                            Quantidade máxima de vezes que o cupom pode ser usado. Deixe vazio para ilimitado.
                        </p>
                    </div>

                    <!-- Validade -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="valido_de" class="block text-sm font-medium text-gray-700 mb-2">
                                Válido De
                            </label>
                            <input type="date" 
                                   id="valido_de" 
                                   name="valido_de" 
                                   class="input-field"
                                   value="<?php echo set_value('valido_de'); ?>">
                        </div>

                        <div>
                            <label for="valido_ate" class="block text-sm font-medium text-gray-700 mb-2">
                                Válido Até
                            </label>
                            <input type="date" 
                                   id="valido_ate" 
                                   name="valido_ate" 
                                   class="input-field"
                                   value="<?php echo set_value('valido_ate'); ?>">
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição Interna
                        </label>
                        <textarea id="descricao" 
                                  name="descricao" 
                                  rows="3"
                                  maxlength="500"
                                  placeholder="Descrição para uso interno (não visível para clientes)"
                                  class="input-field"><?php echo set_value('descricao'); ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            Esta descrição é apenas para controle interno e não será exibida aos clientes.
                        </p>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="ativo" 
                               name="ativo" 
                               value="1"
                               checked
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="ativo" class="ml-2 text-sm text-gray-700">
                            Cupom ativo (disponível para uso)
                        </label>
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                        <a href="<?php echo base_url('settings/cupons'); ?>" class="btn-outline">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Criar Cupom
                        </button>
                    </div>

                </form>
            </div>

            <!-- Informações Adicionais -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">ℹ️ Informações Importantes</h3>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• O cupom será criado automaticamente no Stripe</li>
                    <li>• Códigos devem ser únicos e não podem ser alterados depois</li>
                    <li>• Descontos percentuais não podem exceder 100%</li>
                    <li>• Cupons inativos não podem ser aplicados em novas assinaturas</li>
                </ul>
            </div>

            </div>
        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

<script>
// Atualizar label do valor baseado no tipo
function updateValorLabel() {
    const tipo = document.getElementById('tipo').value;
    const label = document.getElementById('valor-label');
    const input = document.getElementById('valor');
    
    if (tipo === 'percent') {
        label.textContent = 'Valor do Desconto (%) *';
        input.placeholder = 'Ex: 20';
        input.max = '100';
    } else {
        label.textContent = 'Valor do Desconto (R$) *';
        input.placeholder = 'Ex: 10.00';
        input.removeAttribute('max');
    }
}

// Mostrar/ocultar campo de duração em meses
function toggleDuracaoMeses() {
    const duracao = document.getElementById('duracao').value;
    const wrapper = document.getElementById('duracao-meses-wrapper');
    const input = document.getElementById('duracao_meses');
    
    if (duracao === 'repeating') {
        wrapper.style.display = 'block';
        input.required = true;
    } else {
        wrapper.style.display = 'none';
        input.required = false;
        input.value = '';
    }
}

// Inicializar ao carregar
document.addEventListener('DOMContentLoaded', function() {
    updateValorLabel();
    toggleDuracaoMeses();
});
</script>
