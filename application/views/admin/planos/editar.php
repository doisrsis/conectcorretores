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
            
            <h1 class="text-xl font-semibold text-gray-900">Editar Plano</h1>
            
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

            <!-- Informações do Stripe -->
            <?php if ($plan->stripe_product_id): ?>
                <div class="bg-purple-50 border-l-4 border-purple-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-purple-800">Sincronizado com Stripe</h3>
                            <div class="mt-2 text-sm text-purple-700">
                                <p><strong>Product ID:</strong> <?php echo $plan->stripe_product_id; ?></p>
                                <p><strong>Price ID:</strong> <?php echo $plan->stripe_price_id; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulário -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informações do Plano</h2>
                    <p class="text-sm text-gray-600 mt-1">As alterações serão sincronizadas com o Stripe</p>
                </div>

                <form method="post" action="<?php echo base_url('admin/planos_editar/' . $plan->id); ?>" class="p-6 space-y-6">
                    
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
                            value="<?php echo $plan->nome; ?>"
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
                            placeholder="Descrição detalhada do plano..."><?php echo $plan->descricao; ?></textarea>
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
                                    value="<?php echo $plan->preco; ?>"
                                    class="input-field pl-10"
                                    placeholder="0,00">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                <?php if ($plan->stripe_price_id): ?>
                                    ⚠️ Alterar o preço criará um novo Price no Stripe
                                <?php else: ?>
                                    Valor da assinatura
                                <?php endif; ?>
                            </p>
                        </div>

                        <!-- Tipo (somente leitura) -->
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Cobrança
                            </label>
                            <input 
                                type="text" 
                                value="<?php echo ucfirst($plan->tipo); ?>"
                                class="input-field bg-gray-100"
                                readonly>
                            <p class="text-xs text-gray-500 mt-1">Não é possível alterar o tipo após criação</p>
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
                            value="<?php echo $plan->limite_imoveis; ?>"
                            class="input-field"
                            placeholder="Deixe em branco para ilimitado">
                        <p class="text-xs text-gray-500 mt-1">Quantidade máxima de imóveis que o corretor pode cadastrar (vazio = ilimitado)</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status do Plano
                        </label>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="ativo" 
                                    value="1" 
                                    <?php echo $plan->ativo == 1 ? 'checked' : ''; ?>
                                    class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-gray-700">Ativo</span>
                            </label>
                            <label class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="ativo" 
                                    value="0" 
                                    <?php echo $plan->ativo == 0 ? 'checked' : ''; ?>
                                    class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-gray-700">Inativo</span>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Planos inativos não aparecem para novos clientes</p>
                    </div>

                    <!-- Aviso sobre alteração de preço -->
                    <?php if ($plan->stripe_price_id): ?>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Atenção ao alterar o preço</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Se você alterar o preço:</p>
                                        <ul class="list-disc list-inside mt-2 space-y-1">
                                            <li>O preço antigo será desativado no Stripe</li>
                                            <li>Um novo preço será criado</li>
                                            <li>Assinaturas existentes manterão o preço antigo</li>
                                            <li>Novas assinaturas usarão o novo preço</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

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
                            Salvar Alterações
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </main>
</div>

<?php $this->load->view('templates/dashboard_footer'); ?>
