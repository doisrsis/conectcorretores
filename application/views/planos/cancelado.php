<?php
/**
 * View: Página de Cancelamento - Pagamento Cancelado
 * 
 * Autor: Rafael Dias - doisr.com.br
 * Data: 19/10/2025
 */
$this->load->view('templates/dashboard_header');
$this->load->view('templates/sidebar');
?>

<!-- Main Content -->
<div class="lg:pl-64 min-h-screen flex flex-col bg-gradient-to-br from-orange-50 to-yellow-50">
    <!-- Top Bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="flex items-center justify-between px-4 py-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <h1 class="text-xl font-semibold text-gray-900">Pagamento Cancelado</h1>
            
            <div class="w-6"></div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8 flex items-center justify-center">
        <div class="max-w-2xl w-full">
            
            <!-- Card de Cancelamento -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 lg:p-12 text-center border-2 border-orange-200">
                
                <!-- Ícone de Cancelamento -->
                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>

                <!-- Título -->
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Pagamento Cancelado
                </h2>

                <!-- Mensagem -->
                <p class="text-lg text-gray-600 mb-8">
                    Você cancelou o processo de pagamento. Nenhuma cobrança foi realizada.
                </p>

                <!-- Informações -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-8 text-left">
                    <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        O que aconteceu?
                    </h3>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li class="flex items-start gap-2">
                            <span>•</span>
                            <span>O processo de pagamento foi interrompido</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span>•</span>
                            <span>Nenhuma cobrança foi realizada no seu cartão</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span>•</span>
                            <span>Você pode tentar novamente a qualquer momento</span>
                        </li>
                    </ul>
                </div>

                <!-- Motivos Comuns -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                    <h3 class="font-semibold text-gray-900 mb-3">Motivos Comuns para Cancelamento</h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start gap-2">
                            <span>🤔</span>
                            <span>Ainda está avaliando os planos</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span>💳</span>
                            <span>Precisa verificar informações do cartão</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span>❓</span>
                            <span>Tem dúvidas sobre o serviço</span>
                        </li>
                    </ul>
                </div>

                <!-- Botões de Ação -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo base_url('planos'); ?>" class="btn-primary px-8 py-3 text-lg">
                        Tentar Novamente
                    </a>
                    <a href="<?php echo base_url('dashboard'); ?>" class="btn-outline px-8 py-3 text-lg">
                        Voltar ao Dashboard
                    </a>
                </div>

                <!-- Suporte -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-4">
                        Tem dúvidas sobre nossos planos ou precisa de ajuda?
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center text-sm">
                        <a href="mailto:suporte@conectcorretores.com.br" class="text-primary-600 hover:text-primary-700 font-medium flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Enviar Email
                        </a>
                        <a href="https://wa.me/5511999999999" target="_blank" class="text-green-600 hover:text-green-700 font-medium flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            WhatsApp
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

</body>
</html>
