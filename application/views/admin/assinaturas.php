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
            
            <h1 class="text-xl font-semibold text-gray-900">Gerenciar Assinaturas</h1>
            
            <div class="w-6"></div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8">
        <div class="max-w-7xl mx-auto space-y-6">
            
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

            <!-- Filtros -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <form method="get" class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="input">
                            <option value="">Todos</option>
                            <option value="ativa" <?php echo $this->input->get('status') === 'ativa' ? 'selected' : ''; ?>>Ativa</option>
                            <option value="cancelada" <?php echo $this->input->get('status') === 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                            <option value="expirada" <?php echo $this->input->get('status') === 'expirada' ? 'selected' : ''; ?>>Expirada</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Estatísticas -->
            <div class="grid md:grid-cols-2 gap-4">
                <div class="bg-white rounded-lg shadow p-4 border border-gray-100">
                    <p class="text-sm text-gray-600">Total de Assinaturas</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo $total; ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border border-gray-100">
                    <p class="text-sm text-gray-600">Mostrando</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        <?php echo min($per_page, $total - $offset); ?> de <?php echo $total; ?>
                    </p>
                </div>
            </div>

            <!-- Lista de Assinaturas -->
            <?php if (!empty($subscriptions)): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Corretor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Plano
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Valor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Período
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cadastro
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($subscriptions as $sub): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                                    <span class="text-primary-600 font-semibold">
                                                        <?php echo strtoupper(substr($sub->user_nome, 0, 1)); ?>
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo $sub->user_nome; ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo $sub->user_email; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo $sub->plan_nome; ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo ucfirst($sub->plan_tipo); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                R$ <?php echo number_format($sub->plan_preco, 2, ',', '.'); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?php 
                                                    if ($sub->status === 'ativa') echo 'bg-green-100 text-green-800';
                                                    elseif ($sub->status === 'cancelada') echo 'bg-red-100 text-red-800';
                                                    else echo 'bg-gray-100 text-gray-800';
                                                ?>">
                                                <?php echo ucfirst($sub->status); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div><?php echo date('d/m/Y', strtotime($sub->data_inicio)); ?></div>
                                            <div class="text-xs">até <?php echo date('d/m/Y', strtotime($sub->data_fim)); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('d/m/Y', strtotime($sub->created_at)); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginação -->
                <?php if ($total > $per_page): ?>
                    <div class="flex items-center justify-center gap-2">
                        <?php if ($offset > 0): ?>
                            <a href="?offset=<?php echo $offset - $per_page; ?><?php echo $this->input->get('status') ? '&status=' . $this->input->get('status') : ''; ?>" 
                               class="btn-outline px-4 py-2">
                                ← Anterior
                            </a>
                        <?php endif; ?>

                        <?php if ($offset + $per_page < $total): ?>
                            <a href="?offset=<?php echo $offset + $per_page; ?><?php echo $this->input->get('status') ? '&status=' . $this->input->get('status') : ''; ?>" 
                               class="btn-primary px-4 py-2">
                                Próxima →
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="bg-white rounded-xl shadow-lg p-12 text-center border border-gray-100">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Nenhuma assinatura encontrada
                    </h3>
                    <p class="text-gray-600">
                        Tente ajustar os filtros de busca
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

</body>
</html>
