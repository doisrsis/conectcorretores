<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/dashboard_header');
$this->load->view('templates/sidebar');
?>

<div class="lg:pl-64 min-h-screen flex flex-col">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="flex items-center justify-between px-4 py-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="text-xl font-semibold text-gray-900">📋 Todas as Configurações</h1>
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

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <p class="text-sm text-blue-700">ℹ️ Esta é uma visualização somente leitura. Para editar, acesse a aba específica de cada grupo.</p>
            </div>

            <?php foreach ($grouped_settings as $grupo => $settings): ?>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <?php
                            $icons = [
                                'assinaturas' => '💳',
                                'site' => '🌐',
                                'email' => '📧',
                                'seguranca' => '🛡️',
                                'imoveis' => '🏠',
                                'sistema' => '⚙️'
                            ];
                            echo isset($icons[$grupo]) ? $icons[$grupo] : '📁';
                            ?>
                            <?php echo ucfirst($grupo); ?>
                            <span class="ml-2 text-sm text-gray-500">(<?php echo count($settings); ?> configurações)</span>
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chave</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($settings as $setting): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                            <?php echo $setting->chave; ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <?php if ($setting->tipo === 'bool'): ?>
                                                <?php if ($setting->valor == '1'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        ✓ Ativado
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        ✗ Desativado
                                                    </span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <code class="bg-gray-100 px-2 py-1 rounded text-xs">
                                                    <?php echo htmlspecialchars(substr($setting->valor, 0, 50)); ?>
                                                    <?php if (strlen($setting->valor) > 50): ?>...<?php endif; ?>
                                                </code>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?php echo $setting->tipo; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?php echo $setting->descricao ?: '-'; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Estatísticas -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Estatísticas</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-600">
                            <?php 
                            $total = 0;
                            foreach ($grouped_settings as $settings) {
                                $total += count($settings);
                            }
                            echo $total;
                            ?>
                        </div>
                        <div class="text-sm text-gray-500">Total</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600"><?php echo count($grouped_settings); ?></div>
                        <div class="text-sm text-gray-500">Grupos</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">
                            <?php 
                            $editaveis = 0;
                            foreach ($grouped_settings as $settings) {
                                foreach ($settings as $setting) {
                                    if ($setting->editavel) $editaveis++;
                                }
                            }
                            echo $editaveis;
                            ?>
                        </div>
                        <div class="text-sm text-gray-500">Editáveis</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-600"><?php echo $total - $editaveis; ?></div>
                        <div class="text-sm text-gray-500">Bloqueadas</div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>
