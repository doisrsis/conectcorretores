<?php
/**
 * View: Página de Sucesso - Pagamento Confirmado
 * 
 * Autor: Rafael Dias - doisr.com.br
 * Data: 19/10/2025
 */
$this->load->view('templates/dashboard_header');
$this->load->view('templates/sidebar');
?>

<!-- Main Content -->
<div class="lg:pl-64 min-h-screen flex flex-col bg-gradient-to-br from-green-50 to-emerald-50">
    <!-- Top Bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="flex items-center justify-between px-4 py-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <h1 class="text-xl font-semibold text-gray-900">Pagamento Confirmado</h1>
            
            <div class="w-6"></div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8 flex items-center justify-center">
        <div class="max-w-2xl w-full">
            
            <!-- Card de Sucesso -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 lg:p-12 text-center border-2 border-green-200">
                
                <!-- Ícone de Sucesso -->
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <!-- Título -->
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    🎉 Pagamento Confirmado!
                </h2>

                <!-- Mensagem -->
                <p class="text-lg text-gray-600 mb-8">
                    Sua assinatura foi ativada com sucesso. Agora você tem acesso completo a todos os recursos da plataforma!
                </p>

                <!-- Detalhes da Transação -->
                <?php if (isset($session)): ?>
                <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                    <h3 class="font-semibold text-gray-900 mb-4 text-center">Detalhes da Transação</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-semibold text-green-600">
                                <?php echo $session->payment_status === 'paid' ? '✅ Pago' : '⏳ Processando'; ?>
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">ID da Sessão:</span>
                            <span class="font-mono text-sm text-gray-900">
                                <?php echo substr($session->id, 0, 20); ?>...
                            </span>
                        </div>
                        
                        <?php if ($session->customer_email): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="text-gray-900"><?php echo $session->customer_email; ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Próximos Passos -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-8 text-left">
                    <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Próximos Passos
                    </h3>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li class="flex items-start gap-2">
                            <span>1.</span>
                            <span>Acesse o dashboard para começar a cadastrar seus imóveis</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span>2.</span>
                            <span>Configure seu perfil e preferências</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span>3.</span>
                            <span>Explore todos os recursos disponíveis</span>
                        </li>
                    </ul>
                </div>

                <!-- Botões de Ação -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo base_url('dashboard'); ?>" class="btn-primary px-8 py-3 text-lg">
                        Ir para Dashboard
                    </a>
                    <a href="<?php echo base_url('imoveis/novo'); ?>" class="btn-outline px-8 py-3 text-lg">
                        Cadastrar Imóvel
                    </a>
                </div>

                <!-- Suporte -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        Precisa de ajuda? Entre em contato com nosso 
                        <a href="mailto:suporte@conectcorretores.com.br" class="text-primary-600 hover:text-primary-700 font-medium">
                            suporte
                        </a>
                    </p>
                </div>

            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

</body>
</html>
