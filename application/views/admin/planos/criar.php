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
            
            <h1 class="text-xl font-semibold text-gray-900">Criar Novo Plano</h1>
            
            <div class="w-6"></div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8">
        <div class="max-w-3xl mx-auto">
            
            <!-- Mensagens -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert-success mb-6">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert-error mb-6">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <!-- Formulário -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informações do Plano</h2>
                    <p class="text-sm text-gray-600 mt-1">O plano será criado automaticamente no Stripe</p>
                </div>

                <form method="post" action="<?php echo base_url('admin/planos_criar'); ?>" class="p-6 space-y-6">
                    
                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome do Plano <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            required
                            class="input-field"
                            placeholder="Ex: Plano Básico">
                        <p class="text-xs text-gray-500 mt-1">Nome que aparecerá para os clientes</p>
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição
                        </label>
                        <textarea 
                            id="descricao" 
                            name="descricao" 
                            rows="3"
                            class="input-field"
                            placeholder="Descrição detalhada do plano..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Opcional - Descreva os benefícios do plano</p>
                    </div>

                    <!-- Preço e Tipo -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Preço -->
                        <div>
                            <label for="preco" class="block text-sm font-medium text-gray-700 mb-2">
                                Preço (R$) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R$</span>
                                <input 
                                    type="number" 
                                    id="preco" 
                                    name="preco" 
                                    step="0.01"
                                    min="0"
                                    required
                                    class="input-field pl-10"
                                    placeholder="0,00">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Valor da assinatura</p>
                        </div>

                        <!-- Tipo -->
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Cobrança <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="tipo" 
                                name="tipo" 
                                required
                                class="input-field">
                                <option value="mensal">Mensal</option>
                                <option value="trimestral">Trimestral</option>
                                <option value="semestral">Semestral</option>
                                <option value="anual">Anual</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Frequência de cobrança</p>
                        </div>
                    </div>

                    <!-- Limite de Imóveis -->
                    <div>
                        <label for="limite_imoveis" class="block text-sm font-medium text-gray-700 mb-2">
                            Limite de Imóveis
                        </label>
                        <input 
                            type="number" 
                            id="limite_imoveis" 
                            name="limite_imoveis" 
                            min="0"
                            class="input-field"
                            placeholder="Deixe em branco para ilimitado">
                        <p class="text-xs text-gray-500 mt-1">Quantidade máxima de imóveis que o corretor pode cadastrar (vazio = ilimitado)</p>
                    </div>

                    <!-- Informação sobre Stripe -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Integração com Stripe</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Ao criar este plano, o sistema irá:</p>
                                    <ul class="list-disc list-inside mt-2 space-y-1">
                                        <li>Criar um produto no Stripe</li>
                                        <li>Criar um preço recorrente no Stripe</li>
                                        <li>Salvar as informações no banco de dados</li>
                                        <li>Disponibilizar o plano para os clientes automaticamente</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="<?php echo base_url('admin/planos'); ?>" class="btn-outline">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Voltar
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Criar Plano
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </main>
</div>

<?php $this->load->view('templates/dashboard_footer'); ?>
