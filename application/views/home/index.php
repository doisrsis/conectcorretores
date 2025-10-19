<?php $this->load->view('templates/header'); ?>

<div class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-900">ConectCorretores</span>
                </div>

                <!-- Menu -->
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

    <!-- Hero Section -->
    <section class="flex-1 bg-gradient-to-br from-primary-50 via-white to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                    Gerencie seus imóveis de forma 
                    <span class="text-primary-600">profissional</span>
                </h1>
                
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Plataforma completa para corretores organizarem, divulgarem e gerenciarem 
                    seu portfólio de imóveis com eficiência.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="<?php echo base_url('register'); ?>" class="btn-primary text-lg px-8 py-3 w-full sm:w-auto">
                        Começar Agora
                    </a>
                    <a href="<?php echo base_url('planos'); ?>" class="btn-outline text-lg px-8 py-3 w-full sm:w-auto">
                        Ver Planos
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Gestão Completa</h3>
                    <p class="text-gray-600 text-sm">
                        Cadastre e gerencie todos os seus imóveis em um só lugar
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Análises</h3>
                    <p class="text-gray-600 text-sm">
                        Acompanhe estatísticas e métricas do seu portfólio
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Fácil de Usar</h3>
                    <p class="text-gray-600 text-sm">
                        Interface intuitiva e moderna para máxima produtividade
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Seguro</h3>
                    <p class="text-gray-600 text-sm">
                        Seus dados protegidos com criptografia de ponta
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-gradient-to-r from-primary-600 to-primary-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Pronto para começar?
            </h2>
            <p class="text-primary-100 mb-8 max-w-2xl mx-auto text-lg">
                Junte-se a centenas de corretores que já estão gerenciando seus imóveis 
                de forma profissional com o ConectCorretores.
            </p>
            <a href="<?php echo base_url('register'); ?>" class="inline-block bg-white text-primary-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-medium text-lg transition-all duration-200">
                Criar Conta Grátis
            </a>
        </div>
    </section>
</div>

<?php $this->load->view('templates/footer'); ?>
