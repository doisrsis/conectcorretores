<?php $this->load->view('templates/dashboard_header'); ?>

<?php $this->load->view('templates/sidebar'); ?>

<!-- Main Content -->
<div class="lg:pl-64 min-h-screen flex flex-col">
    <!-- Top Bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="flex items-center justify-between px-4 py-4">
            <!-- Menu Button (Mobile) -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>

            <div class="w-6"></div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Mensagens de Feedback -->
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

            <!-- Welcome -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900">
                    Olá, <?php echo $user->nome; ?>! 👋
                </h2>
                <p class="text-gray-600 mt-1">
                    Bem-vindo ao seu painel de controle
                </p>
            </div>

            <!-- Avisos de Plano -->
            <?php $this->load->helper('subscription'); ?>
            <?php $status_plano = get_status_assinatura($user->id); ?>
            
            <?php if (!$status_plano->tem_plano): ?>
                <!-- Sem Plano -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Você não possui um plano ativo
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Seus imóveis estão inativos e não aparecem nas buscas. Escolha um plano para ativá-los e começar a anunciar.</p>
                            </div>
                            <div class="mt-4">
                                <a href="<?php echo base_url('planos'); ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Escolher Plano
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($status_plano->plano_vencido): ?>
                <!-- Plano Vencido -->
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-red-800">
                                Seu plano expirou em <?php echo date('d/m/Y', strtotime($status_plano->data_fim)); ?>
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Seus imóveis foram desativados automaticamente</li>
                                    <li>Não é possível cadastrar novos imóveis</li>
                                    <li>Não é possível editar imóveis existentes</li>
                                    <li>Seus anúncios não aparecem mais nas buscas</li>
                                </ul>
                            </div>
                            <div class="mt-4">
                                <a href="<?php echo base_url('planos'); ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Renovar Plano Agora
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Status da Assinatura -->
            <?php if ($subscription): ?>
                <div class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                    <!-- Header com gradiente -->
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Seu Plano Atual</p>
                                <p class="text-2xl font-bold text-white mt-1">
                                    <?php echo $subscription->plan_nome; ?>
                                </p>
                            </div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-white/20 text-white backdrop-blur-sm">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <?php echo ucfirst($subscription->status); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Conteúdo -->
                    <div class="p-6">
                        <div class="grid md:grid-cols-3 gap-6 mb-6">
                            <!-- Preço -->
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Valor</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    R$ <?php echo number_format($subscription->plan_preco, 2, ',', '.'); ?>
                                    <span class="text-sm font-normal text-gray-500">/<?php echo $subscription->plan_tipo; ?></span>
                                </p>
                            </div>

                            <!-- Limite de Imóveis -->
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Limite de Imóveis</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    <?php
                                    $limite = $subscription->plan_limite_imoveis;
                                    echo $limite ? number_format($limite, 0, ',', '.') : '∞';
                                    ?>
                                    <span class="text-sm font-normal text-gray-500">imóveis</span>
                                </p>
                                <?php if ($limite): ?>
                                    <div class="mt-2">
                                        <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                            <span>Você tem <?php echo $stats->total_imoveis; ?> cadastrados</span>
                                            <span><?php echo round(($stats->total_imoveis / $limite) * 100); ?>%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: <?php echo min(($stats->total_imoveis / $limite) * 100, 100); ?>%"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Validade -->
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Válido até</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    <?php echo date('d/m/Y', strtotime($subscription->data_fim)); ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?php
                                    // Calcular dias restantes
                                    $data_fim_timestamp = strtotime($subscription->data_fim);
                                    $hoje_timestamp = time();
                                    $diferenca_segundos = $data_fim_timestamp - $hoje_timestamp;
                                    $dias_restantes = ceil($diferenca_segundos / (60 * 60 * 24));

                                    echo $dias_restantes > 0 ? "$dias_restantes dias restantes" : "Expirado";

                                    // DEBUG (remover depois)
                                    //echo "<br><small style='color: red;'>Data Fim: {$subscription->data_fim} | Hoje: " . date('Y-m-d') . " | Dias: {$dias_restantes}</small>";
                                    ?>
                                </p>
                            </div>
                        </div>

                        <!-- Descrição do Plano -->
                        <?php if (!empty($subscription->plan_descricao)): ?>
                            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                <p class="text-sm text-gray-700">
                                    <svg class="w-4 h-4 inline mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <?php echo $subscription->plan_descricao; ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <!-- Ações -->
                        <div class="flex items-center gap-3">
                            <a href="<?php echo base_url('planos'); ?>" class="btn-primary flex-1 text-center">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Fazer Upgrade
                            </a>
                            <a href="<?php echo base_url('planos'); ?>" class="btn-outline flex-1 text-center">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                Trocar Plano
                            </a>
                            <a href="<?php echo base_url('planos/cancelar'); ?>" class="text-sm text-red-600 hover:text-red-700 px-4 py-2">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-yellow-700 font-medium">Sem Plano Ativo</p>
                            <p class="text-lg text-yellow-900 mt-1">
                                Assine um plano para aproveitar todos os recursos
                            </p>
                        </div>
                        <a href="<?php echo base_url('planos'); ?>" class="btn-primary">
                            Ver Planos
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Total de Imóveis -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total de Imóveis</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                <?php echo $stats->total_imoveis; ?>
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Para Venda -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Para Venda</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                <?php echo $stats->imoveis_venda; ?>
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Para Aluguel -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Para Aluguel</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                <?php echo $stats->imoveis_aluguel; ?>
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    Ações Rápidas
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <a href="<?php echo base_url('imoveis/novo'); ?>"
                       class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition-all">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Cadastrar Imóvel</p>
                            <p class="text-sm text-gray-600">Adicione um novo imóvel</p>
                        </div>
                    </a>

                    <a href="<?php echo base_url('imoveis'); ?>"
                       class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition-all">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Ver Todos os Imóveis</p>
                            <p class="text-sm text-gray-600">Gerencie seu portfólio</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Imóveis Recentes -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Imóveis Recentes
                    </h3>
                    <a href="<?php echo base_url('imoveis'); ?>" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        Ver todos →
                    </a>
                </div>

                <?php if (!empty($recent_imoveis)): ?>
                    <div class="space-y-3">
                        <?php foreach ($recent_imoveis as $imovel): ?>
                            <div class="p-4 border border-gray-200 rounded-lg hover:border-primary-500 hover:shadow-md transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $imovel->tipo_negocio === 'compra' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                                <?php echo ucfirst($imovel->tipo_negocio); ?>
                                            </span>
                                            <span class="text-sm text-gray-600"><?php echo $imovel->tipo_imovel; ?></span>
                                        </div>
                                        <p class="font-medium text-gray-900">
                                            <?php echo $imovel->cidade; ?> - <?php echo $imovel->estado; ?>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <?php echo $imovel->quartos; ?> quartos • <?php echo $imovel->vagas; ?> vagas • <?php echo number_format($imovel->area_privativa, 0, ',', '.'); ?>m²
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-primary-600">
                                            R$ <?php echo number_format($imovel->preco, 2, ',', '.'); ?>
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            R$ <?php echo number_format($imovel->valor_m2, 2, ',', '.'); ?>/m²
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <p class="text-gray-600 mb-4">Nenhum imóvel cadastrado ainda</p>
                        <a href="<?php echo base_url('imoveis/novo'); ?>" class="btn-primary">
                            Cadastrar Primeiro Imóvel
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

</body>
</html>
