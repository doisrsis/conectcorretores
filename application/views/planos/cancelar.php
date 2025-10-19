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
            
            <h1 class="text-xl font-semibold text-gray-900">Cancelar Assinatura</h1>
            
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

            <!-- Card de Confirmação -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 px-8 py-6">
                    <div class="flex items-center gap-4">
                        <div class="bg-white/20 p-3 rounded-full">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Cancelar Assinatura</h2>
                            <p class="text-red-100 mt-1">Tem certeza que deseja cancelar?</p>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8">
                    
                    <!-- Informações da Assinatura Atual -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Sua Assinatura Atual</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Plano</p>
                                <p class="text-lg font-bold text-gray-900"><?php echo $subscription->plan_nome; ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Valor</p>
                                <p class="text-lg font-bold text-gray-900">
                                    R$ <?php echo number_format($subscription->plan_preco, 2, ',', '.'); ?>/<?php echo $subscription->plan_tipo; ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Data de Início</p>
                                <p class="text-lg font-medium text-gray-900">
                                    <?php echo date('d/m/Y', strtotime($subscription->data_inicio)); ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Válido até</p>
                                <p class="text-lg font-medium text-gray-900">
                                    <?php echo date('d/m/Y', strtotime($subscription->data_fim)); ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Aviso -->
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Atenção!</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Ao cancelar sua assinatura:</p>
                                    <ul class="list-disc list-inside mt-2 space-y-1">
                                        <li>Você perderá acesso aos recursos do plano</li>
                                        <li>Seus imóveis cadastrados serão mantidos</li>
                                        <li>Você poderá reativar sua assinatura a qualquer momento</li>
                                        <li>Não haverá reembolso proporcional</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alternativas -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Que tal mudar de plano?</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Em vez de cancelar, você pode fazer downgrade para um plano mais econômico.</p>
                                    <a href="<?php echo base_url('planos'); ?>" class="inline-flex items-center mt-2 text-blue-600 hover:text-blue-800 font-medium">
                                        Ver outros planos
                                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário de Confirmação -->
                    <form method="post" action="<?php echo base_url('planos/cancelar'); ?>" class="space-y-6">
                        
                        <!-- Motivo do Cancelamento (Opcional) -->
                        <div>
                            <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">
                                Por que você está cancelando? (Opcional)
                            </label>
                            <textarea 
                                id="motivo" 
                                name="motivo" 
                                rows="4" 
                                class="input-field"
                                placeholder="Sua opinião nos ajuda a melhorar nosso serviço..."></textarea>
                        </div>

                        <!-- Checkbox de Confirmação -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input 
                                    id="confirmar_cancelamento" 
                                    name="confirmar_cancelamento" 
                                    type="checkbox" 
                                    required
                                    class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            </div>
                            <div class="ml-3">
                                <label for="confirmar_cancelamento" class="text-sm font-medium text-gray-700">
                                    Confirmo que desejo cancelar minha assinatura e estou ciente das consequências
                                </label>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button 
                                type="submit" 
                                name="confirmar" 
                                value="1"
                                class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                                Confirmar Cancelamento
                            </button>
                            <a 
                                href="<?php echo base_url('planos'); ?>" 
                                class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors text-center">
                                Voltar aos Planos
                            </a>
                        </div>
                    </form>

                </div>
            </div>

            <!-- Suporte -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Precisa de ajuda? 
                    <a href="<?php echo base_url('contato'); ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                        Entre em contato com o suporte
                    </a>
                </p>
            </div>

        </div>
    </main>
</div>

<?php $this->load->view('templates/dashboard_footer'); ?>