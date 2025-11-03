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
            
            <h1 class="text-xl font-semibold text-gray-900">Meus Imóveis</h1>
            
            <a href="<?php echo base_url('imoveis/novo'); ?>" class="btn-primary text-sm">
                + Novo Imóvel
            </a>
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
                <form method="get" class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Negócio</label>
                        <select name="tipo_negocio" class="input">
                            <option value="">Todos</option>
                            <option value="compra" <?php echo $this->input->get('tipo_negocio') === 'compra' ? 'selected' : ''; ?>>Venda</option>
                            <option value="aluguel" <?php echo $this->input->get('tipo_negocio') === 'aluguel' ? 'selected' : ''; ?>>Aluguel</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Imóvel</label>
                        <select name="tipo_imovel" class="input">
                            <option value="">Todos</option>
                            <?php foreach ($tipos_imoveis as $tipo): ?>
                                <option value="<?php echo $tipo; ?>" <?php echo $this->input->get('tipo_imovel') === $tipo ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($tipo); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" name="search" value="<?php echo $this->input->get('search'); ?>" 
                               class="input" placeholder="Cidade, bairro...">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Estatísticas -->
            <div class="grid md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow p-4 border border-gray-100">
                    <p class="text-sm text-gray-600">Total de Imóveis</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1"><?php echo $total; ?></p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border border-gray-100">
                    <p class="text-sm text-gray-600">Página Atual</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        <?php echo floor($offset / $per_page) + 1; ?> de <?php echo ceil($total / $per_page); ?>
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border border-gray-100">
                    <p class="text-sm text-gray-600">Mostrando</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        <?php echo min($per_page, $total - $offset); ?> de <?php echo $total; ?>
                    </p>
                </div>
            </div>

            <!-- Lista de Imóveis -->
            <?php if (!empty($imoveis)): ?>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($imoveis as $imovel): ?>
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-200">
                            <!-- Imagem Placeholder -->
                            <div class="h-48 bg-gradient-to-br from-primary-100 to-blue-100 flex items-center justify-center">
                                <svg class="w-16 h-16 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>

                            <!-- Conteúdo -->
                            <div class="p-4">
                                <!-- Badges -->
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $imovel->tipo_negocio === 'compra' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                        <?php echo ucfirst($imovel->tipo_negocio); ?>
                                    </span>
                                    <?php if ($imovel->destaque): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            ⭐ Destaque
                                        </span>
                                    <?php endif; ?>
                                    
                                    <!-- Badge de Status de Publicação -->
                                    <?php if (isset($imovel->status_publicacao)): ?>
                                        <?php if ($imovel->status_publicacao === 'ativo'): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✅ Publicado
                                            </span>
                                        <?php elseif ($imovel->status_publicacao === 'inativo_plano_vencido'): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ⚠️ Plano Vencido
                                            </span>
                                        <?php elseif ($imovel->status_publicacao === 'inativo_sem_plano'): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                ⚠️ Sem Plano
                                            </span>
                                        <?php elseif ($imovel->status_publicacao === 'inativo_manual'): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                🔒 Desativado
                                            </span>
                                        <?php endif; ?>
                                    <?php elseif (!$imovel->ativo): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Inativo
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Tipo -->
                                <p class="text-sm text-gray-600 mb-1"><?php echo ucfirst($imovel->tipo_imovel); ?></p>

                                <!-- Localização -->
                                <h3 class="font-semibold text-gray-900 mb-2">
                                    <?php echo $imovel->cidade; ?> - <?php echo $imovel->estado; ?>
                                </h3>

                                <!-- Detalhes -->
                                <div class="flex items-center gap-4 text-sm text-gray-600 mb-3">
                                    <span>🛏️ <?php echo $imovel->quartos; ?></span>
                                    <span>🚗 <?php echo $imovel->vagas; ?></span>
                                    <span>📐 <?php echo number_format($imovel->area_privativa, 0, ',', '.'); ?>m²</span>
                                </div>

                                <!-- Preço -->
                                <div class="mb-4">
                                    <p class="text-2xl font-bold text-primary-600">
                                        R$ <?php echo number_format($imovel->preco, 2, ',', '.'); ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        R$ <?php echo number_format($imovel->valor_m2, 2, ',', '.'); ?>/m²
                                    </p>
                                </div>

                                <!-- Ações -->
                                <div class="space-y-2">
                                    <div class="flex gap-2">
                                        <a href="<?php echo base_url('imoveis/ver/' . $imovel->id); ?>" 
                                           class="flex-1 btn-outline text-center text-sm py-2">
                                            Ver
                                        </a>
                                        
                                        <?php 
                                        $this->load->helper('subscription');
                                        $pode_editar = pode_gerenciar_imoveis($this->session->userdata('user_id')) || $this->session->userdata('role') === 'admin';
                                        ?>
                                        
                                        <?php if ($pode_editar): ?>
                                            <a href="<?php echo base_url('imoveis/editar/' . $imovel->id); ?>" 
                                               class="flex-1 btn-primary text-center text-sm py-2">
                                                Editar
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo base_url('planos'); ?>" 
                                               class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center text-sm py-2 rounded-lg transition">
                                                Renovar para Editar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Botão de Ativar/Desativar -->
                                    <?php if ($pode_editar): ?>
                                        <?php 
                                        $status = isset($imovel->status_publicacao) ? $imovel->status_publicacao : 'ativo';
                                        $is_ativo = ($status === 'ativo');
                                        ?>
                                        <a href="<?php echo base_url('imoveis/toggle_status/' . $imovel->id); ?>" 
                                           class="block w-full text-center text-sm py-2 rounded-lg transition <?php echo $is_ativo ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-green-100 hover:bg-green-200 text-green-700'; ?>"
                                           onclick="return confirm('<?php echo $is_ativo ? 'Desativar' : 'Ativar'; ?> este imóvel?')">
                                            <?php echo $is_ativo ? '🔒 Desativar' : '✅ Ativar'; ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginação -->
                <?php if ($total > $per_page): ?>
                    <div class="flex items-center justify-center gap-2 mt-8">
                        <?php if ($offset > 0): ?>
                            <a href="?offset=<?php echo $offset - $per_page; ?>" class="btn-outline px-4 py-2">
                                ← Anterior
                            </a>
                        <?php endif; ?>

                        <?php if ($offset + $per_page < $total): ?>
                            <a href="?offset=<?php echo $offset + $per_page; ?>" class="btn-primary px-4 py-2">
                                Próxima →
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- Vazio -->
                <div class="bg-white rounded-xl shadow-lg p-12 text-center border border-gray-100">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        Nenhum imóvel encontrado
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Comece cadastrando seu primeiro imóvel
                    </p>
                    <a href="<?php echo base_url('imoveis/novo'); ?>" class="btn-primary inline-block">
                        + Cadastrar Primeiro Imóvel
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

</body>
</html>
