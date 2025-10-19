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
            
            <h1 class="text-xl font-semibold text-gray-900">Meu Perfil</h1>
            
            <div class="w-6"></div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8">
        <div class="max-w-4xl mx-auto space-y-6">
            
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

            <?php if (validation_errors()): ?>
                <div class="alert-error">
                    <?php echo validation_errors(); ?>
                </div>
            <?php endif; ?>

            <!-- Widget de Plano -->
            <?php if ($subscription): ?>
                <div class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Seu Plano Atual</p>
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
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Valor Mensal</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    R$ <?php echo number_format($subscription->plan_preco, 2, ',', '.'); ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">/<?php echo $subscription->plan_tipo; ?></p>
                            </div>
                            
                            <!-- Limite de Imóveis -->
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Limite de Imóveis</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    <?php 
                                    $limite = $subscription->plan_limite_imoveis;
                                    echo $limite ? number_format($limite, 0, ',', '.') : '∞';
                                    ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?php echo $stats->total_imoveis; ?> cadastrados
                                </p>
                            </div>
                            
                            <!-- Validade -->
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Válido até</p>
                                <p class="text-lg font-bold text-gray-900">
                                    <?php echo date('d/m/Y', strtotime($subscription->data_fim)); ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?php 
                                    $dias_restantes = ceil((strtotime($subscription->data_fim) - time()) / (60 * 60 * 24));
                                    echo $dias_restantes > 0 ? "$dias_restantes dias" : "Expirado";
                                    ?>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Descrição -->
                        <?php if (!empty($subscription->plan_descricao)): ?>
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            <strong>Benefícios do seu plano:</strong><br>
                                            <?php echo $subscription->plan_descricao; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Barra de Progresso (se houver limite) -->
                        <?php if ($limite): ?>
                            <div class="mb-6">
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                    <span>Uso de Imóveis</span>
                                    <span class="font-semibold">
                                        <?php echo $stats->total_imoveis; ?> / <?php echo $limite; ?>
                                        (<?php echo round(($stats->total_imoveis / $limite) * 100); ?>%)
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="<?php echo ($stats->total_imoveis / $limite) >= 0.8 ? 'bg-yellow-500' : 'bg-blue-600'; ?> h-3 rounded-full transition-all duration-300" 
                                         style="width: <?php echo min(($stats->total_imoveis / $limite) * 100, 100); ?>%"></div>
                                </div>
                                <?php if (($stats->total_imoveis / $limite) >= 0.8): ?>
                                    <p class="text-xs text-yellow-600 mt-2">
                                        ⚠️ Você está próximo do limite. Considere fazer upgrade!
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Ações -->
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="<?php echo base_url('planos'); ?>" class="btn-primary flex-1 text-center min-w-[200px]">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Ver Todos os Planos
                            </a>
                            <a href="<?php echo base_url('planos/cancelar'); ?>" class="text-sm text-red-600 hover:text-red-700 px-4 py-2">
                                Cancelar Assinatura
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
                                Assine um plano para aproveitar todos os recursos da plataforma
                            </p>
                        </div>
                        <a href="<?php echo base_url('planos'); ?>" class="btn-primary whitespace-nowrap">
                            Ver Planos
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Informações do Perfil -->
            <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8 border border-gray-100">
                <div class="flex items-center gap-6 mb-6 pb-6 border-b">
                    <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center">
                        <span class="text-primary-600 font-bold text-3xl">
                            <?php echo strtoupper(substr($user->nome, 0, 1)); ?>
                        </span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900"><?php echo $user->nome; ?></h2>
                        <p class="text-gray-600"><?php echo $user->email; ?></p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                            <?php echo ucfirst($user->role); ?>
                        </span>
                    </div>
                </div>

                <!-- Formulário de Edição -->
                <?php echo form_open('dashboard/editar_perfil', ['class' => 'space-y-6']); ?>
                
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nome Completo *
                            </label>
                            <input type="text" name="nome" 
                                   value="<?php echo set_value('nome', $user->nome); ?>" 
                                   class="input" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email *
                            </label>
                            <input type="email" name="email" 
                                   value="<?php echo set_value('email', $user->email); ?>" 
                                   class="input" required>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                CPF
                            </label>
                            <input type="text" name="cpf" 
                                   value="<?php echo set_value('cpf', $user->cpf); ?>" 
                                   class="input" placeholder="000.000.000-00">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Telefone
                            </label>
                            <input type="text" name="telefone" 
                                   value="<?php echo set_value('telefone', $user->telefone); ?>" 
                                   class="input" placeholder="(00) 0000-0000">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            WhatsApp
                        </label>
                        <input type="text" name="whatsapp" 
                               value="<?php echo set_value('whatsapp', $user->whatsapp); ?>" 
                               class="input" placeholder="(00) 9 0000-0000">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Endereço
                        </label>
                        <textarea name="endereco" rows="3" class="input" 
                                  placeholder="Rua, número, bairro, cidade..."><?php echo set_value('endereco', $user->endereco); ?></textarea>
                    </div>

                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Alterar Senha</h3>
                        <p class="text-sm text-gray-600 mb-4">Deixe em branco se não quiser alterar a senha</p>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nova Senha
                                </label>
                                <input type="password" name="nova_senha" class="input" 
                                       placeholder="Mínimo 6 caracteres">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Confirmar Nova Senha
                                </label>
                                <input type="password" name="confirmar_senha" class="input" 
                                       placeholder="Digite novamente">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-6 border-t">
                        <a href="<?php echo base_url('dashboard'); ?>" class="btn-secondary flex-1 text-center">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary flex-1">
                            Salvar Alterações
                        </button>
                    </div>

                <?php echo form_close(); ?>
            </div>

            <!-- Informações da Conta -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações da Conta</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Status da Conta</span>
                        <span class="font-medium <?php echo $user->ativo ? 'text-green-600' : 'text-red-600'; ?>">
                            <?php echo $user->ativo ? 'Ativa' : 'Inativa'; ?>
                        </span>
                    </div>
                    
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Membro desde</span>
                        <span class="font-medium text-gray-900">
                            <?php echo date('d/m/Y', strtotime($user->created_at)); ?>
                        </span>
                    </div>
                    
                    <?php if ($user->updated_at): ?>
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Última atualização</span>
                            <span class="font-medium text-gray-900">
                                <?php echo date('d/m/Y H:i', strtotime($user->updated_at)); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

</body>
</html>
