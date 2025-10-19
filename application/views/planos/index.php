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
            
            <h1 class="text-xl font-semibold text-gray-900">Planos</h1>
            
            <div class="w-6"></div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8">
        <div class="max-w-7xl mx-auto space-y-8">
            
            <!-- Mensagens -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert-success">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert-error">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <!-- Header -->
            <div class="text-center">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Escolha o Plano Ideal
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Gerencie seus imóveis de forma profissional com nossos planos flexíveis
                </p>
            </div>

            <!-- Assinatura Atual -->
            <?php if ($current_subscription): ?>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-700 font-medium mb-1">Seu Plano Atual</p>
                            <p class="text-2xl font-bold text-green-900">
                                <?php echo $current_subscription->plan_nome; ?>
                            </p>
                            <p class="text-sm text-green-600 mt-2">
                                Válido até <?php echo date('d/m/Y', strtotime($current_subscription->data_fim)); ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-green-900">
                                R$ <?php echo number_format($current_subscription->plan_preco, 2, ',', '.'); ?>
                            </p>
                            <p class="text-sm text-green-600">/<?php echo $current_subscription->plan_tipo; ?></p>
                            <a href="<?php echo base_url('planos/cancelar'); ?>" 
                               class="text-sm text-red-600 hover:text-red-700 mt-2 inline-block">
                                Cancelar assinatura
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Planos -->
            <div class="grid md:grid-cols-3 gap-8">
                <?php foreach ($plans as $plan): ?>
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 <?php echo $plan->nome === 'Profissional' ? 'border-primary-500 transform scale-105' : 'border-gray-200'; ?> hover:shadow-2xl transition-all duration-200">
                        
                        <?php if ($plan->nome === 'Profissional'): ?>
                            <div class="bg-primary-600 text-white text-center py-2 text-sm font-semibold">
                                ⭐ MAIS POPULAR
                            </div>
                        <?php endif; ?>

                        <div class="p-8">
                            <!-- Nome do Plano -->
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                <?php echo $plan->nome; ?>
                            </h3>

                            <!-- Preço -->
                            <div class="mb-6">
                                <span class="text-5xl font-bold text-gray-900">
                                    R$ <?php echo number_format($plan->preco, 0, ',', '.'); ?>
                                </span>
                                <span class="text-gray-600">/<?php echo $plan->tipo; ?></span>
                            </div>

                            <!-- Descrição -->
                            <?php if ($plan->descricao): ?>
                                <p class="text-gray-600 mb-6">
                                    <?php echo $plan->descricao; ?>
                                </p>
                            <?php endif; ?>

                            <!-- Recursos -->
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">
                                        <?php echo $plan->limite_imoveis ? $plan->limite_imoveis . ' imóveis' : 'Imóveis ilimitados'; ?>
                                    </span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Painel completo</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-700">Suporte por email</span>
                                </li>
                                <?php if ($plan->nome !== 'Básico'): ?>
                                    <li class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Relatórios avançados</span>
                                    </li>
                                <?php endif; ?>
                                <?php if ($plan->nome === 'Premium'): ?>
                                    <li class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Suporte prioritário</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">API de integração</span>
                                    </li>
                                <?php endif; ?>
                            </ul>

                            <!-- Botão -->
                            <?php if ($current_subscription && $current_subscription->plan_id == $plan->id): ?>
                                <button disabled class="w-full btn-secondary cursor-not-allowed">
                                    Plano Atual
                                </button>
                            <?php elseif ($current_subscription): ?>
                                <button disabled class="w-full btn-secondary cursor-not-allowed">
                                    Cancele sua assinatura primeiro
                                </button>
                            <?php else: ?>
                                <a href="<?php echo base_url('planos/escolher/' . $plan->id); ?>" 
                                   class="block w-full text-center <?php echo $plan->nome === 'Profissional' ? 'btn-primary' : 'btn-outline'; ?>">
                                    Assinar Agora
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- FAQ -->
            <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 mt-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                    Perguntas Frequentes
                </h3>
                
                <div class="space-y-4 max-w-3xl mx-auto">
                    <div class="border-b pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Posso cancelar a qualquer momento?</h4>
                        <p class="text-gray-600">Sim! Você pode cancelar sua assinatura a qualquer momento sem multas ou taxas adicionais.</p>
                    </div>
                    
                    <div class="border-b pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Como funciona o pagamento?</h4>
                        <p class="text-gray-600">Aceitamos cartão de crédito através do Stripe. O pagamento é processado de forma segura e automática.</p>
                    </div>
                    
                    <div class="border-b pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Posso mudar de plano?</h4>
                        <p class="text-gray-600">Sim! Você pode fazer upgrade ou downgrade do seu plano a qualquer momento.</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

</body>
</html>
