<?php $this->load->view('templates/header'); ?>

<div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="<?php echo base_url(); ?>" class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="text-xl font-bold text-gray-900">ConectCorretores</span>
                </a>

                <div class="flex items-center space-x-4">
                    <a href="<?php echo base_url('login'); ?>" class="btn-outline btn-sm px-4 py-2">
                        Entrar
                    </a>
                    <a href="<?php echo base_url('register'); ?>" class="btn-primary btn-sm px-4 py-2">
                        Cadastrar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="flex-1 py-12 px-4">
        <div class="max-w-7xl mx-auto space-y-12">
            
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">
                    Planos e Preços
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Escolha o plano ideal para gerenciar seus imóveis de forma profissional
                </p>
            </div>

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
                            <a href="<?php echo base_url('register'); ?>" 
                               class="block w-full text-center <?php echo $plan->nome === 'Profissional' ? 'btn-primary' : 'btn-outline'; ?>">
                                Começar Agora
                            </a>
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
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Preciso de cartão de crédito para testar?</h4>
                        <p class="text-gray-600">Não! Você pode criar sua conta gratuitamente e escolher um plano quando estiver pronto.</p>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl p-12 text-center">
                <h3 class="text-3xl font-bold text-white mb-4">
                    Pronto para começar?
                </h3>
                <p class="text-primary-100 mb-8 text-lg max-w-2xl mx-auto">
                    Crie sua conta gratuitamente e comece a gerenciar seus imóveis hoje mesmo
                </p>
                <a href="<?php echo base_url('register'); ?>" 
                   class="inline-block bg-white text-primary-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-medium text-lg transition-all duration-200">
                    Criar Conta Grátis
                </a>
            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

</body>
</html>
