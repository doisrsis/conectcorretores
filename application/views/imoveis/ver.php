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
            
            <h1 class="text-xl font-semibold text-gray-900">Detalhes do Imóvel</h1>
            
            <a href="<?php echo base_url('imoveis'); ?>" class="text-gray-600 hover:text-gray-900">
                ← Voltar
            </a>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8">
        <div class="max-w-5xl mx-auto space-y-6">
            
            <!-- Mensagens -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert-success">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <!-- Imagem Principal -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="h-96 bg-gradient-to-br from-primary-100 to-blue-100 flex items-center justify-center">
                    <svg class="w-32 h-32 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>

            <!-- Informações Principais -->
            <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8 border border-gray-100">
                
                <!-- Header -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo $imovel->tipo_negocio === 'compra' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                <?php echo ucfirst($imovel->tipo_negocio); ?>
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                <?php echo ucfirst($imovel->tipo_imovel); ?>
                            </span>
                            <?php if ($imovel->destaque): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    ⭐ Destaque
                                </span>
                            <?php endif; ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo $imovel->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $imovel->ativo ? 'Ativo' : 'Inativo'; ?>
                            </span>
                        </div>

                        <!-- Localização -->
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            <?php echo $imovel->endereco; ?><?php echo $imovel->numero ? ', ' . $imovel->numero : ''; ?>
                        </h2>
                        <p class="text-gray-600">
                            <?php echo $imovel->bairro; ?> - <?php echo $imovel->cidade; ?>/<?php echo $imovel->estado; ?>
                            <?php if ($imovel->cep): ?>
                                - CEP: <?php echo $imovel->cep; ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Ações -->
                    <div class="flex gap-2 ml-4">
                        <a href="<?php echo base_url('imoveis/editar/' . $imovel->id); ?>" 
                           class="btn-primary px-4 py-2">
                            Editar
                        </a>
                        <a href="<?php echo base_url('imoveis/toggle_status/' . $imovel->id); ?>" 
                           class="btn-secondary px-4 py-2"
                           onclick="return confirm('Deseja <?php echo $imovel->ativo ? 'desativar' : 'ativar'; ?> este imóvel?')">
                            <?php echo $imovel->ativo ? 'Desativar' : 'Ativar'; ?>
                        </a>
                    </div>
                </div>

                <!-- Preço -->
                <div class="bg-primary-50 rounded-lg p-6 mb-6">
                    <p class="text-sm text-primary-700 mb-1">Valor</p>
                    <p class="text-4xl font-bold text-primary-900">
                        R$ <?php echo number_format($imovel->preco, 2, ',', '.'); ?>
                    </p>
                    <p class="text-sm text-primary-600 mt-2">
                        R$ <?php echo number_format($imovel->valor_m2, 2, ',', '.'); ?>/m²
                    </p>
                </div>

                <!-- Características -->
                <div class="grid md:grid-cols-4 gap-6 mb-6">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-2xl">📐</span>
                        </div>
                        <p class="text-sm text-gray-600">Área Privativa</p>
                        <p class="text-xl font-bold text-gray-900"><?php echo number_format($imovel->area_privativa, 2, ',', '.'); ?>m²</p>
                    </div>

                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-2xl">🛏️</span>
                        </div>
                        <p class="text-sm text-gray-600">Quartos</p>
                        <p class="text-xl font-bold text-gray-900"><?php echo $imovel->quartos; ?></p>
                        <?php if ($imovel->suites > 0): ?>
                            <p class="text-xs text-gray-500">(<?php echo $imovel->suites; ?> suíte<?php echo $imovel->suites > 1 ? 's' : ''; ?>)</p>
                        <?php endif; ?>
                    </div>

                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-2xl">🚿</span>
                        </div>
                        <p class="text-sm text-gray-600">Banheiros</p>
                        <p class="text-xl font-bold text-gray-900"><?php echo $imovel->banheiros; ?></p>
                    </div>

                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-2xl">🚗</span>
                        </div>
                        <p class="text-sm text-gray-600">Vagas</p>
                        <p class="text-xl font-bold text-gray-900"><?php echo $imovel->vagas; ?></p>
                    </div>
                </div>

                <!-- Área Total -->
                <?php if ($imovel->area_total): ?>
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <p class="text-sm text-gray-600">Área Total</p>
                        <p class="text-lg font-semibold text-gray-900">
                            <?php echo number_format($imovel->area_total, 2, ',', '.'); ?>m²
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Descrição -->
                <?php if ($imovel->descricao): ?>
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Descrição</h3>
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($imovel->descricao)); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Informações do Corretor -->
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Informações do Corretor</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                            <span class="text-primary-600 font-semibold text-lg">
                                <?php echo strtoupper(substr($imovel->corretor_nome, 0, 1)); ?>
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900"><?php echo $imovel->corretor_nome; ?></p>
                            <p class="text-sm text-gray-600"><?php echo $imovel->corretor_email; ?></p>
                            <?php if ($imovel->corretor_telefone): ?>
                                <p class="text-sm text-gray-600">📞 <?php echo $imovel->corretor_telefone; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Data de Cadastro -->
                <div class="border-t pt-6 mt-6">
                    <p class="text-sm text-gray-600">
                        Cadastrado em <?php echo date('d/m/Y \à\s H:i', strtotime($imovel->created_at)); ?>
                        <?php if ($imovel->updated_at): ?>
                            • Atualizado em <?php echo date('d/m/Y \à\s H:i', strtotime($imovel->updated_at)); ?>
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Ações Adicionais -->
                <div class="border-t pt-6 mt-6 flex gap-4">
                    <a href="<?php echo base_url('imoveis/toggle_destaque/' . $imovel->id); ?>" 
                       class="btn-outline flex-1 text-center"
                       onclick="return confirm('Deseja <?php echo $imovel->destaque ? 'remover destaque' : 'marcar como destaque'; ?>?')">
                        <?php echo $imovel->destaque ? '⭐ Remover Destaque' : '⭐ Marcar como Destaque'; ?>
                    </a>
                    <a href="<?php echo base_url('imoveis/deletar/' . $imovel->id); ?>" 
                       class="btn-secondary flex-1 text-center text-red-600 hover:bg-red-50"
                       onclick="return confirm('Tem certeza que deseja deletar este imóvel? Esta ação não pode ser desfeita!')">
                        🗑️ Deletar Imóvel
                    </a>
                </div>

            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

</body>
</html>
