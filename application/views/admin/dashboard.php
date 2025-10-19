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
            
            <h1 class="text-xl font-semibold text-gray-900">Admin Dashboard</h1>
            
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
                    Painel Administrativo 🔐
                </h2>
                <p class="text-gray-600 mt-1">
                    Visão geral do sistema
                </p>
            </div>

            <!-- Stats Cards -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total de Corretores -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total de Corretores</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                <?php echo $stats->total_corretores; ?>
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

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

                <!-- Assinaturas Ativas -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Assinaturas Ativas</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                <?php echo $stats->assinaturas_ativas; ?>
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Receita Mensal -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Receita Mensal</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">
                                R$ <?php echo number_format($stats->receita_mensal, 2, ',', '.'); ?>
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Últimos Corretores -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Últimos Corretores
                        </h3>
                        <a href="<?php echo base_url('admin/usuarios'); ?>" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            Ver todos →
                        </a>
                    </div>

                    <?php if (!empty($recent_users)): ?>
                        <div class="space-y-3">
                            <?php foreach ($recent_users as $user): ?>
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-primary-500 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                            <span class="text-primary-600 font-semibold">
                                                <?php echo strtoupper(substr($user->nome, 0, 1)); ?>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900"><?php echo $user->nome; ?></p>
                                            <p class="text-sm text-gray-600"><?php echo $user->email; ?></p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $user->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $user->ativo ? 'Ativo' : 'Inativo'; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-gray-600 py-8">Nenhum corretor cadastrado</p>
                    <?php endif; ?>
                </div>

                <!-- Últimas Assinaturas -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Últimas Assinaturas
                        </h3>
                        <a href="<?php echo base_url('admin/assinaturas'); ?>" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                            Ver todas →
                        </a>
                    </div>

                    <?php if (!empty($recent_subscriptions)): ?>
                        <div class="space-y-3">
                            <?php foreach ($recent_subscriptions as $sub): ?>
                                <div class="p-3 border border-gray-200 rounded-lg hover:border-primary-500 transition-all">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="font-medium text-gray-900"><?php echo $sub->user_nome; ?></p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $sub->status === 'ativa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                            <?php echo ucfirst($sub->status); ?>
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        <?php echo $sub->plan_nome; ?> - R$ <?php echo number_format($sub->plan_preco, 2, ',', '.'); ?>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Válido até <?php echo date('d/m/Y', strtotime($sub->data_fim)); ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-gray-600 py-8">Nenhuma assinatura</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

</body>
</html>
