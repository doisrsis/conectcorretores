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
            
            <h1 class="text-xl font-semibold text-gray-900">✏️ Editar Cupom</h1>
            
            <a href="<?php echo base_url('settings/cupons'); ?>" class="btn-outline btn-sm">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8 bg-gray-50">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <?php $this->load->view('admin/settings/_tabs', ['active_tab' => $active_tab]); ?>
            
            <div class="max-w-3xl mx-auto">
            
            <!-- Mensagens -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert-error mb-6">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <!-- Informações do Cupom (não editáveis) -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 mb-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold font-mono"><?php echo $coupon->codigo; ?></h2>
                    <?php if ($coupon->ativo): ?>
                        <span class="px-3 py-1 bg-green-500 rounded-full text-sm font-semibold">Ativo</span>
                    <?php else: ?>
                        <span class="px-3 py-1 bg-red-500 rounded-full text-sm font-semibold">Inativo</span>
                    <?php endif; ?>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-blue-100">Tipo</p>
                        <p class="font-semibold"><?php echo $coupon->tipo === 'percent' ? 'Percentual' : 'Fixo'; ?></p>
                    </div>
                    <div>
                        <p class="text-blue-100">Desconto</p>
                        <p class="font-semibold text-lg">
                            <?php if ($coupon->tipo === 'percent'): ?>
                                <?php echo number_format($coupon->valor, 0); ?>%
                            <?php else: ?>
                                R$ <?php echo number_format($coupon->valor, 2, ',', '.'); ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-blue-100">Duração</p>
                        <p class="font-semibold">
                            <?php 
                                if ($coupon->duracao === 'once') echo '1x';
                                elseif ($coupon->duracao === 'repeating') echo $coupon->duracao_meses . ' meses';
                                else echo 'Sempre';
                            ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-blue-100">Usos</p>
                        <p class="font-semibold">
                            <?php echo $coupon->total_usos; ?>
                            <?php if ($coupon->max_usos): ?>
                                / <?php echo $coupon->max_usos; ?>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Aviso -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-yellow-900">Campos Limitados para Edição</h3>
                        <p class="text-sm text-yellow-800 mt-1">
                            Por limitações do Stripe, apenas alguns campos podem ser editados. Código, tipo, valor e duração não podem ser alterados.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulário -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h2 class="text-lg font-bold text-gray-900">Campos Editáveis</h2>
                </div>

                <form method="post" action="<?php echo base_url('settings/cupons_edit/' . $coupon->id); ?>" class="p-6 space-y-6">
                    
                    <!-- Limite de Usos -->
                    <div>
                        <label for="max_usos" class="block text-sm font-medium text-gray-700 mb-2">
                            Limite de Usos
                        </label>
                        <input type="number" 
                               id="max_usos" 
                               name="max_usos" 
                               min="<?php echo $coupon->total_usos; ?>"
                               placeholder="Deixe em branco para ilimitado"
                               class="input-field"
                               value="<?php echo $coupon->max_usos; ?>">
                        <p class="text-xs text-gray-500 mt-1">
                            Já foram usados <?php echo $coupon->total_usos; ?> cupons. O limite não pode ser menor que isso.
                        </p>
                    </div>

                    <!-- Validade -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="valido_de" class="block text-sm font-medium text-gray-700 mb-2">
                                Válido De
                            </label>
                            <input type="date" 
                                   id="valido_de" 
                                   name="valido_de" 
                                   class="input-field"
                                   value="<?php echo $coupon->valido_de; ?>">
                        </div>

                        <div>
                            <label for="valido_ate" class="block text-sm font-medium text-gray-700 mb-2">
                                Válido Até
                            </label>
                            <input type="date" 
                                   id="valido_ate" 
                                   name="valido_ate" 
                                   class="input-field"
                                   value="<?php echo $coupon->valido_ate; ?>">
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição Interna
                        </label>
                        <textarea id="descricao" 
                                  name="descricao" 
                                  rows="3"
                                  maxlength="500"
                                  placeholder="Descrição para uso interno (não visível para clientes)"
                                  class="input-field"><?php echo $coupon->descricao; ?></textarea>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="ativo" 
                               name="ativo" 
                               value="1"
                               <?php echo $coupon->ativo ? 'checked' : ''; ?>
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="ativo" class="ml-2 text-sm text-gray-700">
                            Cupom ativo (disponível para uso)
                        </label>
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t">
                        <a href="<?php echo base_url('settings/cupons'); ?>" class="btn-outline">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Alterações
                        </button>
                    </div>

                </form>
            </div>

            </div>
        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>
