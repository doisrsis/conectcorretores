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
                <!-- Banner de Trial Ativo -->
                <?php if ($current_subscription->is_trial): ?>
                    <?php 
                        $days_left = ceil((strtotime($current_subscription->trial_ends_at) - time()) / 86400);
                        $is_expiring_soon = $days_left <= 3;
                    ?>
                    <div class="bg-gradient-to-r <?php echo $is_expiring_soon ? 'from-orange-50 to-red-50 border-orange-300' : 'from-blue-50 to-indigo-50 border-blue-300'; ?> border-2 rounded-xl p-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-2xl">🎁</span>
                                    <p class="text-sm <?php echo $is_expiring_soon ? 'text-orange-700' : 'text-blue-700'; ?> font-medium">
                                        Período de Teste Gratuito
                                    </p>
                                </div>
                                <p class="text-2xl font-bold <?php echo $is_expiring_soon ? 'text-orange-900' : 'text-blue-900'; ?>">
                                    <?php echo $current_subscription->plan_nome; ?>
                                </p>
                                <p class="text-sm <?php echo $is_expiring_soon ? 'text-orange-600' : 'text-blue-600'; ?> mt-2">
                                    <?php if ($days_left > 1): ?>
                                        ⏰ Restam <strong><?php echo $days_left; ?> dias</strong> de teste gratuito
                                    <?php elseif ($days_left == 1): ?>
                                        ⏰ <strong>Último dia</strong> de teste gratuito!
                                    <?php else: ?>
                                        ⏰ Seu teste expira <strong>hoje</strong>!
                                    <?php endif; ?>
                                </p>
                                <p class="text-xs <?php echo $is_expiring_soon ? 'text-orange-500' : 'text-blue-500'; ?> mt-1">
                                    Expira em <?php echo date('d/m/Y às H:i', strtotime($current_subscription->trial_ends_at)); ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="mb-3">
                                    <p class="text-sm <?php echo $is_expiring_soon ? 'text-orange-600' : 'text-blue-600'; ?> mb-1">
                                        Após o trial:
                                    </p>
                                    <p class="text-3xl font-bold <?php echo $is_expiring_soon ? 'text-orange-900' : 'text-blue-900'; ?>">
                                        R$ <?php echo number_format($current_subscription->plan_preco, 2, ',', '.'); ?>
                                    </p>
                                    <p class="text-sm <?php echo $is_expiring_soon ? 'text-orange-600' : 'text-blue-600'; ?>">/<?php echo $current_subscription->plan_tipo; ?></p>
                                </div>
                                <a href="<?php echo base_url('planos/escolher/' . $current_subscription->plan_id); ?>" 
                                   class="inline-block px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-bold rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    🚀 Continuar com Plano Pago
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Assinatura Paga Normal -->
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
                                <div class="mt-3 space-y-2">
                                    <a href="<?php echo base_url('planos/portal'); ?>" 
                                       class="inline-block px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Gerenciar Assinatura
                                </a>
                                <br>
                                <a href="<?php echo base_url('planos/cancelar'); ?>" 
                                   class="text-sm text-red-600 hover:text-red-700 inline-block">
                                    Cancelar assinatura
                                </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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
                                <!-- Plano Atual -->
                                <button disabled class="w-full btn-secondary cursor-not-allowed opacity-60">
                                    <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Plano Atual
                                </button>
                            <?php elseif ($current_subscription && $plan->preco > $current_subscription->plan_preco): ?>
                                <!-- Upgrade -->
                                <div class="space-y-2">
                                    <div class="text-center text-sm font-semibold text-green-600">
                                        +R$ <?php echo number_format($plan->preco - $current_subscription->plan_preco, 2, ',', '.'); ?>/mês
                                    </div>
                                    <button onclick="iniciarUpgrade(<?php echo $plan->id; ?>)" 
                                            data-plan-id="<?php echo $plan->id; ?>"
                                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        Fazer Upgrade
                                    </button>
                                </div>
                            <?php elseif ($current_subscription && $plan->preco < $current_subscription->plan_preco): ?>
                                <!-- Downgrade -->
                                <div class="space-y-2">
                                    <div class="text-center text-sm font-semibold text-yellow-600">
                                        Economize R$ <?php echo number_format($current_subscription->plan_preco - $plan->preco, 2, ',', '.'); ?>/mês
                                    </div>
                                    <button onclick="iniciarDowngrade(<?php echo $plan->id; ?>)" 
                                            data-plan-id="<?php echo $plan->id; ?>"
                                            class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-lg hover:shadow-xl">
                                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                        </svg>
                                        Fazer Downgrade
                                    </button>
                                </div>
                            <?php else: ?>
                                <!-- Sem assinatura - Mostrar Trial ou Assinar -->
                                <?php 
                                    $user_id = $this->session->userdata('user_id');
                                    $has_used_trial = $user_id ? $this->Subscription_model->has_used_trial($user_id) : false;
                                ?>
                                
                                <?php if (!$has_used_trial): ?>
                                    <!-- Botão de Trial -->
                                    <div class="space-y-3">
                                        <a href="<?php echo base_url('planos/iniciar_trial/' . $plan->id); ?>" 
                                           class="block w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-4 rounded-lg font-bold text-center hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                            🎁 Testar Grátis por 7 Dias
                                        </a>
                                        <p class="text-xs text-center text-gray-500">
                                            Sem cartão de crédito • Cancele quando quiser
                                        </p>
                                        <div class="relative">
                                            <div class="absolute inset-0 flex items-center">
                                                <div class="w-full border-t border-gray-200"></div>
                                            </div>
                                            <div class="relative flex justify-center text-xs">
                                                <span class="px-2 bg-white text-gray-400">ou</span>
                                            </div>
                                        </div>
                                        <button onclick="iniciarCheckout(<?php echo $plan->id; ?>)" 
                                                data-plan-id="<?php echo $plan->id; ?>"
                                                class="w-full <?php echo $plan->nome === 'Profissional' ? 'btn-primary' : 'btn-outline'; ?> btn-checkout">
                                            Assinar Agora
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <!-- Apenas botão de assinar (já usou trial) -->
                                    <button onclick="iniciarCheckout(<?php echo $plan->id; ?>)" 
                                            data-plan-id="<?php echo $plan->id; ?>"
                                            class="w-full <?php echo $plan->nome === 'Profissional' ? 'btn-primary' : 'btn-checkout'; ?> btn-checkout">
                                        Assinar Agora
                                    </button>
                                    <p class="text-xs text-center text-gray-500 mt-2">
                                        Trial já utilizado
                                    </p>
                                <?php endif; ?>
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
                        <h4 class="font-semibold text-gray-900 mb-2">🎁 Como funciona o período de teste gratuito?</h4>
                        <p class="text-gray-600">Você tem 7 dias para testar todas as funcionalidades do plano escolhido, completamente grátis! Não pedimos cartão de crédito e você pode cancelar a qualquer momento.</p>
                    </div>
                    
                    <div class="border-b pb-4">
                        <h4 class="font-semibold text-gray-900 mb-2">O que acontece após o período de teste?</h4>
                        <p class="text-gray-600">Você receberá lembretes por email 3 dias e 1 dia antes do fim do trial. Se não assinar um plano pago, seu acesso será suspenso, mas seus dados ficam salvos por 30 dias.</p>
                    </div>
                    
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

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<!-- Script de Checkout -->
<script>
// Inicializar Stripe
const stripe = Stripe('<?php echo $this->config->item('stripe_public_key'); ?>');
const baseUrl = '<?php echo base_url(); ?>';

// Função para iniciar checkout (nova assinatura)
async function iniciarCheckout(planId) {
    const button = document.querySelector(`[data-plan-id="${planId}"]`);
    
    // Desabilitar botão e mostrar loading
    button.disabled = true;
    button.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Processando...';
    
    try {
        // Criar sessão de checkout
        const response = await fetch(baseUrl + 'planos/criar_checkout_session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `plan_id=${planId}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Redirecionar para checkout do Stripe
            const result = await stripe.redirectToCheckout({
                sessionId: data.session_id
            });
            
            if (result.error) {
                alert('Erro: ' + result.error.message);
                button.disabled = false;
                button.innerHTML = 'Assinar Agora';
            }
        } else {
            alert('Erro: ' + data.error);
            button.disabled = false;
            button.innerHTML = 'Assinar Agora';
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao processar pagamento. Tente novamente.');
        button.disabled = false;
        button.innerHTML = 'Assinar Agora';
    }
}

// Função para fazer upgrade
async function iniciarUpgrade(planId) {
    const button = document.querySelector(`[data-plan-id="${planId}"]`);
    const originalHTML = button.innerHTML;
    
    // Desabilitar botão e mostrar loading
    button.disabled = true;
    button.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Processando upgrade...';
    
    try {
        const response = await fetch(baseUrl + 'planos/upgrade', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `plan_id=${planId}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Mostrar mensagem de sucesso
            alert('✅ Upgrade realizado com sucesso! Redirecionando...');
            window.location.href = baseUrl + 'dashboard';
        } else {
            alert('❌ Erro: ' + data.error);
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('❌ Erro ao processar upgrade. Tente novamente.');
        button.disabled = false;
        button.innerHTML = originalHTML;
    }
}

// Função para fazer downgrade
async function iniciarDowngrade(planId) {
    const button = document.querySelector(`[data-plan-id="${planId}"]`);
    const originalHTML = button.innerHTML;
    
    // Desabilitar botão e mostrar loading
    button.disabled = true;
    button.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Processando downgrade...';
    
    try {
        const response = await fetch(baseUrl + 'planos/downgrade', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `plan_id=${planId}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Mostrar mensagem de sucesso
            alert('✅ Downgrade realizado com sucesso! ' + (data.message || 'Redirecionando...'));
            window.location.href = baseUrl + 'dashboard';
        } else {
            alert('❌ Erro: ' + data.error);
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('❌ Erro ao processar downgrade. Tente novamente.');
        button.disabled = false;
        button.innerHTML = originalHTML;
    }
}
</script>

</body>
</html>
