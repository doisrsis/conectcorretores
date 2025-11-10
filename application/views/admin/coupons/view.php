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
            
            <h1 class="text-xl font-semibold text-gray-900">👁️ Detalhes do Cupom</h1>
            
            <div class="flex items-center space-x-2">
                <a href="<?php echo base_url('admin/coupons/edit/' . $coupon->id); ?>" class="btn-outline btn-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="<?php echo base_url('admin/coupons'); ?>" class="btn-outline btn-sm">
                    Voltar
                </a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8">
        <div class="max-w-6xl mx-auto space-y-6">
            
            <!-- Card Principal do Cupom -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-2xl p-8 text-white">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-4xl font-bold font-mono mb-2"><?php echo $coupon->codigo; ?></h2>
                        <?php if ($coupon->descricao): ?>
                            <p class="text-blue-100"><?php echo $coupon->descricao; ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($coupon->ativo): ?>
                        <span class="px-4 py-2 bg-green-500 rounded-full text-sm font-bold shadow-lg">✓ ATIVO</span>
                    <?php else: ?>
                        <span class="px-4 py-2 bg-red-500 rounded-full text-sm font-bold shadow-lg">✗ INATIVO</span>
                    <?php endif; ?>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                        <p class="text-blue-100 text-sm mb-1">Tipo de Desconto</p>
                        <p class="text-2xl font-bold">
                            <?php echo $coupon->tipo === 'percent' ? '📊 Percentual' : '💰 Fixo'; ?>
                        </p>
                    </div>
                    
                    <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                        <p class="text-blue-100 text-sm mb-1">Valor</p>
                        <p class="text-3xl font-bold">
                            <?php if ($coupon->tipo === 'percent'): ?>
                                <?php echo number_format($coupon->valor, 0); ?>%
                            <?php else: ?>
                                R$ <?php echo number_format($coupon->valor, 2, ',', '.'); ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                        <p class="text-blue-100 text-sm mb-1">Duração</p>
                        <p class="text-2xl font-bold">
                            <?php 
                                if ($coupon->duracao === 'once') echo '1️⃣ Uma vez';
                                elseif ($coupon->duracao === 'repeating') echo '🔄 ' . $coupon->duracao_meses . ' meses';
                                else echo '♾️ Sempre';
                            ?>
                        </p>
                    </div>
                    
                    <div class="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
                        <p class="text-blue-100 text-sm mb-1">Usos</p>
                        <p class="text-3xl font-bold">
                            <?php echo $coupon->total_usos; ?>
                            <?php if ($coupon->max_usos): ?>
                                <span class="text-lg">/ <?php echo $coupon->max_usos; ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Validade -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Período de Validade
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Válido De:</p>
                            <p class="text-lg font-semibold text-gray-900">
                                <?php echo $coupon->valido_de ? date('d/m/Y', strtotime($coupon->valido_de)) : 'Sem limite inicial'; ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Válido Até:</p>
                            <p class="text-lg font-semibold text-gray-900">
                                <?php if ($coupon->valido_ate): ?>
                                    <?php 
                                        $expired = strtotime($coupon->valido_ate) < time();
                                        $class = $expired ? 'text-red-600' : 'text-green-600';
                                    ?>
                                    <span class="<?php echo $class; ?>">
                                        <?php echo date('d/m/Y', strtotime($coupon->valido_ate)); ?>
                                        <?php echo $expired ? '(Expirado)' : ''; ?>
                                    </span>
                                <?php else: ?>
                                    Sem limite final
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Informações Técnicas -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Informações Técnicas
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600">ID Stripe:</p>
                            <p class="font-mono text-gray-900 break-all"><?php echo $coupon->stripe_coupon_id ?: 'N/A'; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-600">Criado em:</p>
                            <p class="text-gray-900"><?php echo date('d/m/Y H:i', strtotime($coupon->created_at)); ?></p>
                        </div>
                        <?php if ($coupon->updated_at): ?>
                            <div>
                                <p class="text-gray-600">Última atualização:</p>
                                <p class="text-gray-900"><?php echo date('d/m/Y H:i', strtotime($coupon->updated_at)); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- Histórico de Uso -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Histórico de Uso (<?php echo count($usage_history); ?>)
                    </h3>
                </div>

                <?php if (empty($usage_history)): ?>
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-lg font-medium">Nenhum uso registrado</p>
                        <p class="text-sm mt-1">Este cupom ainda não foi utilizado</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data/Hora</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuário</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Valor Original</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Desconto</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Valor Final</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($usage_history as $usage): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo date('d/m/Y H:i', strtotime($usage->usado_em)); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo $usage->user_nome; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <?php echo $usage->user_email; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            R$ <?php echo number_format($usage->valor_original, 2, ',', '.'); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600 font-semibold">
                                            -R$ <?php echo number_format($usage->desconto_aplicado, 2, ',', '.'); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600 font-bold">
                                            R$ <?php echo number_format($usage->valor_final, 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                                        TOTAL DESCONTADO:
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-red-600 text-right">
                                        <?php 
                                            $total_desconto = array_sum(array_column($usage_history, 'desconto_aplicado'));
                                            echo '-R$ ' . number_format($total_desconto, 2, ',', '.');
                                        ?>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>
