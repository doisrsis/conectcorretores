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
            
            <h1 class="text-xl font-semibold text-gray-900">Gerenciar Usuários</h1>
            
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
                <form method="get" class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Função</label>
                        <select name="role" class="input">
                            <option value="">Todos</option>
                            <option value="corretor" <?php echo $this->input->get('role') === 'corretor' ? 'selected' : ''; ?>>Corretor</option>
                            <option value="admin" <?php echo $this->input->get('role') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" name="search" value="<?php echo $this->input->get('search'); ?>" 
                               class="input" placeholder="Nome ou email...">
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
                    <p class="text-sm text-gray-600">Total de Usuários</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo $total; ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border border-gray-100">
                    <p class="text-sm text-gray-600">Mostrando</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        <?php echo min($per_page, $total - $offset); ?> de <?php echo $total; ?>
                    </p>
                </div>
            </div>

            <!-- Lista de Usuários -->
            <?php if (!empty($users)): ?>
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Usuário
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contato
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Função
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cadastro
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($users as $user): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                                    <span class="text-primary-600 font-semibold">
                                                        <?php echo strtoupper(substr($user->nome, 0, 1)); ?>
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <?php echo $user->nome; ?>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <?php echo $user->email; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo $user->telefone ?: '-'; ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo $user->whatsapp ?: '-'; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'; ?>">
                                                <?php echo ucfirst($user->role); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $user->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                <?php echo $user->ativo ? 'Ativo' : 'Inativo'; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('d/m/Y', strtotime($user->created_at)); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="<?php echo base_url('admin/editar_usuario/' . $user->id); ?>" 
                                               class="text-primary-600 hover:text-primary-900 mr-3">
                                                Editar
                                            </a>
                                            <?php if ($user->id != $this->session->userdata('user_id')): ?>
                                                <a href="<?php echo base_url('admin/deletar_usuario/' . $user->id); ?>" 
                                                   class="text-red-600 hover:text-red-900"
                                                   onclick="return confirm('Tem certeza que deseja deletar este usuário?')">
                                                    Deletar
                                                </a>
                                            <?php endif; ?>
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
                            <a href="?offset=<?php echo $offset - $per_page; ?><?php echo $this->input->get('role') ? '&role=' . $this->input->get('role') : ''; ?><?php echo $this->input->get('search') ? '&search=' . $this->input->get('search') : ''; ?>" 
                               class="btn-outline px-4 py-2">
                                ← Anterior
                            </a>
                        <?php endif; ?>

                        <?php if ($offset + $per_page < $total): ?>
                            <a href="?offset=<?php echo $offset + $per_page; ?><?php echo $this->input->get('role') ? '&role=' . $this->input->get('role') : ''; ?><?php echo $this->input->get('search') ? '&search=' . $this->input->get('search') : ''; ?>" 
                               class="btn-primary px-4 py-2">
                                Próxima →
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="bg-white rounded-xl shadow-lg p-12 text-center border border-gray-100">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Nenhum usuário encontrado
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
