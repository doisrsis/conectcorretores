<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/dashboard_header');
$this->load->view('templates/sidebar');
?>

<!-- Main Content -->
<div class="lg:pl-64 min-h-screen flex flex-col">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="flex items-center justify-between px-4 py-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="text-xl font-semibold text-gray-900">⚙️ Configurações de Assinaturas</h1>
            <a href="<?php echo base_url('settings/limpar_cache'); ?>" class="text-gray-600 hover:text-gray-900" title="Limpar Cache">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </a>
        </div>
    </header>

    <main class="flex-1 p-4 lg:p-8 bg-gray-50">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <?php $this->load->view('admin/settings/_tabs', ['active_tab' => $active_tab]); ?>

            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <p class="text-sm text-green-700">✓ <?php echo $this->session->flashdata('success'); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <p class="text-sm text-red-700">✗ <?php echo $this->session->flashdata('error'); ?></p>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow p-6">
                <form method="post" action="<?php echo base_url('settings/assinaturas'); ?>">
                    <div class="grid md:grid-cols-2 gap-6">
                        <?php foreach ($settings as $setting): ?>
                            <div class="border rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php echo ucfirst(str_replace('_', ' ', $setting->chave)); ?>
                                </label>
                                
                                <?php if ($setting->descricao): ?>
                                    <p class="text-xs text-gray-500 mb-3"><?php echo $setting->descricao; ?></p>
                                <?php endif; ?>

                                <?php if ($setting->tipo === 'bool'): ?>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="<?php echo $setting->chave; ?>" value="1" 
                                               class="sr-only peer" <?php echo $setting->valor == '1' ? 'checked' : ''; ?>>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                                        <span class="ml-3 text-sm font-medium text-gray-900"><?php echo $setting->valor == '1' ? 'Ativado' : 'Desativado'; ?></span>
                                    </label>
                                <?php elseif ($setting->tipo === 'int'): ?>
                                    <input type="number" name="<?php echo $setting->chave; ?>" value="<?php echo $setting->valor; ?>" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" min="0" required>
                                <?php else: ?>
                                    <input type="text" name="<?php echo $setting->chave; ?>" value="<?php echo htmlspecialchars($setting->valor); ?>" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" required>
                                <?php endif; ?>

                                <p class="mt-2 text-xs text-gray-500">Atual: <code class="bg-gray-100 px-1 rounded"><?php echo $setting->valor; ?></code></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="<?php echo base_url('admin'); ?>" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                            Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <h3 class="text-sm font-medium text-blue-800 mb-2">💡 Informações</h3>
                <ul class="text-xs text-blue-700 space-y-1">
                    <li><strong>Período de Teste:</strong> Dias gratuitos para novos usuários</li>
                    <li><strong>Período de Graça:</strong> Dias de tolerância após falha de pagamento</li>
                    <li><strong>Tentativas:</strong> Número de vezes que o sistema tenta cobrar</li>
                    <li><strong>Intervalo:</strong> Dias entre cada tentativa de cobrança</li>
                </ul>
            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>
