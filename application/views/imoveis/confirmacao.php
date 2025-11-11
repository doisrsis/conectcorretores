<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - ConectCorretores</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="max-w-2xl w-full">
        
        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-indigo-600">ConectCorretores</h1>
            <p class="text-gray-600 mt-2">Sistema de Validação de Imóveis</p>
        </div>

        <!-- Card Principal -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            
            <?php if ($tipo === 'sucesso_confirmado'): ?>
                <!-- Sucesso: Confirmado -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-8 text-center">
                    <div class="text-6xl mb-4">✅</div>
                    <h2 class="text-3xl font-bold text-white mb-2">Imóvel Confirmado!</h2>
                    <p class="text-green-100 text-lg">Obrigado por confirmar a disponibilidade</p>
                </div>
                
                <div class="p-8">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                        <p class="text-green-800 font-medium">
                            ✓ O imóvel continua ativo e disponível em nossa plataforma
                        </p>
                    </div>
                    
                    <?php if (isset($imovel)): ?>
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="font-bold text-gray-800 mb-4">Dados do Imóvel:</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><strong>ID:</strong> #<?php echo $imovel->id; ?></p>
                            <p><strong>Tipo:</strong> <?php echo $imovel->tipo_imovel; ?></p>
                            <p><strong>Localização:</strong> <?php echo $imovel->cidade; ?>/<?php echo $imovel->estado; ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <p class="text-gray-600 mb-6">
                        Seu imóvel permanecerá ativo e visível para potenciais clientes. Você não precisará validá-lo novamente.
                    </p>
                    
                    <div class="flex gap-4">
                        <a href="<?php echo base_url('dashboard'); ?>" class="flex-1 bg-indigo-600 text-white text-center py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Ir para Dashboard
                        </a>
                        <a href="<?php echo base_url('imoveis'); ?>" class="flex-1 bg-gray-200 text-gray-700 text-center py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition">
                            Ver Meus Imóveis
                        </a>
                    </div>
                </div>

            <?php elseif ($tipo === 'sucesso_vendido'): ?>
                <!-- Sucesso: Vendido -->
                <div class="bg-gradient-to-r from-blue-500 to-cyan-600 p-8 text-center">
                    <div class="text-6xl mb-4">🎉</div>
                    <h2 class="text-3xl font-bold text-white mb-2">Parabéns pela Venda!</h2>
                    <p class="text-blue-100 text-lg">Imóvel marcado como vendido</p>
                </div>
                
                <div class="p-8">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                        <p class="text-blue-800 font-medium">
                            ✓ O imóvel foi desativado e marcado como vendido
                        </p>
                    </div>
                    
                    <?php if (isset($imovel)): ?>
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="font-bold text-gray-800 mb-4">Dados do Imóvel Vendido:</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><strong>ID:</strong> #<?php echo $imovel->id; ?></p>
                            <p><strong>Tipo:</strong> <?php echo $imovel->tipo_imovel; ?></p>
                            <p><strong>Localização:</strong> <?php echo $imovel->cidade; ?>/<?php echo $imovel->estado; ?></p>
                            <p><strong>Valor:</strong> R$ <?php echo number_format($imovel->preco, 2, ',', '.'); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <p class="text-gray-600 mb-6">
                        Parabéns pela negociação! O imóvel foi removido dos anúncios ativos. Você pode reativá-lo a qualquer momento pelo painel administrativo se necessário.
                    </p>
                    
                    <div class="flex gap-4">
                        <a href="<?php echo base_url('dashboard'); ?>" class="flex-1 bg-indigo-600 text-white text-center py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Ir para Dashboard
                        </a>
                        <a href="<?php echo base_url('imoveis'); ?>" class="flex-1 bg-gray-200 text-gray-700 text-center py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition">
                            Ver Meus Imóveis
                        </a>
                    </div>
                </div>

            <?php elseif ($tipo === 'sucesso_alugado'): ?>
                <!-- Sucesso: Alugado -->
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-8 text-center">
                    <div class="text-6xl mb-4">🏢</div>
                    <h2 class="text-3xl font-bold text-white mb-2">Imóvel Alugado!</h2>
                    <p class="text-purple-100 text-lg">Imóvel marcado como alugado</p>
                </div>
                
                <div class="p-8">
                    <div class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-6">
                        <p class="text-purple-800 font-medium">
                            ✓ O imóvel foi desativado e marcado como alugado
                        </p>
                    </div>
                    
                    <?php if (isset($imovel)): ?>
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="font-bold text-gray-800 mb-4">Dados do Imóvel Alugado:</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><strong>ID:</strong> #<?php echo $imovel->id; ?></p>
                            <p><strong>Tipo:</strong> <?php echo $imovel->tipo_imovel; ?></p>
                            <p><strong>Localização:</strong> <?php echo $imovel->cidade; ?>/<?php echo $imovel->estado; ?></p>
                            <p><strong>Valor:</strong> R$ <?php echo number_format($imovel->preco, 2, ',', '.'); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <p class="text-gray-600 mb-6">
                        Ótimo! O imóvel foi removido dos anúncios ativos. Você pode reativá-lo a qualquer momento pelo painel administrativo se o contrato de aluguel for encerrado.
                    </p>
                    
                    <div class="flex gap-4">
                        <a href="<?php echo base_url('dashboard'); ?>" class="flex-1 bg-indigo-600 text-white text-center py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Ir para Dashboard
                        </a>
                        <a href="<?php echo base_url('imoveis'); ?>" class="flex-1 bg-gray-200 text-gray-700 text-center py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition">
                            Ver Meus Imóveis
                        </a>
                    </div>
                </div>

            <?php elseif ($tipo === 'token_invalido'): ?>
                <!-- Erro: Token Inválido -->
                <div class="bg-gradient-to-r from-red-500 to-rose-600 p-8 text-center">
                    <div class="text-6xl mb-4">❌</div>
                    <h2 class="text-3xl font-bold text-white mb-2">Token Inválido</h2>
                    <p class="text-red-100 text-lg">Link de validação não encontrado</p>
                </div>
                
                <div class="p-8">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <p class="text-red-800 font-medium">
                            <?php echo $mensagem; ?>
                        </p>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        O link pode ter expirado ou já foi utilizado. Se você recebeu este email recentemente, entre em contato com o suporte.
                    </p>
                    
                    <div class="flex gap-4">
                        <a href="<?php echo base_url('dashboard'); ?>" class="flex-1 bg-indigo-600 text-white text-center py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Ir para Dashboard
                        </a>
                        <a href="<?php echo base_url('contato'); ?>" class="flex-1 bg-gray-200 text-gray-700 text-center py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition">
                            Falar com Suporte
                        </a>
                    </div>
                </div>

            <?php elseif ($tipo === 'expirado'): ?>
                <!-- Erro: Expirado -->
                <div class="bg-gradient-to-r from-orange-500 to-amber-600 p-8 text-center">
                    <div class="text-6xl mb-4">⏰</div>
                    <h2 class="text-3xl font-bold text-white mb-2">Prazo Expirado</h2>
                    <p class="text-orange-100 text-lg">As 72 horas de validação expiraram</p>
                </div>
                
                <div class="p-8">
                    <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-6">
                        <p class="text-orange-800 font-medium">
                            <?php echo $mensagem; ?>
                        </p>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        O prazo de 72 horas para validação já passou. O imóvel foi desativado automaticamente, mas você pode reativá-lo manualmente pelo painel administrativo.
                    </p>
                    
                    <div class="flex gap-4">
                        <a href="<?php echo base_url('imoveis'); ?>" class="flex-1 bg-indigo-600 text-white text-center py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Ver Meus Imóveis
                        </a>
                        <a href="<?php echo base_url('dashboard'); ?>" class="flex-1 bg-gray-200 text-gray-700 text-center py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition">
                            Ir para Dashboard
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <!-- Erro Genérico -->
                <div class="bg-gradient-to-r from-gray-500 to-slate-600 p-8 text-center">
                    <div class="text-6xl mb-4">⚠️</div>
                    <h2 class="text-3xl font-bold text-white mb-2">Erro</h2>
                    <p class="text-gray-100 text-lg">Ocorreu um erro ao processar sua solicitação</p>
                </div>
                
                <div class="p-8">
                    <div class="bg-gray-50 border-l-4 border-gray-500 p-4 mb-6">
                        <p class="text-gray-800 font-medium">
                            <?php echo $mensagem; ?>
                        </p>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        Por favor, tente novamente ou entre em contato com o suporte se o problema persistir.
                    </p>
                    
                    <div class="flex gap-4">
                        <a href="<?php echo base_url('dashboard'); ?>" class="flex-1 bg-indigo-600 text-white text-center py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Ir para Dashboard
                        </a>
                        <a href="<?php echo base_url('contato'); ?>" class="flex-1 bg-gray-200 text-gray-700 text-center py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition">
                            Falar com Suporte
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600">
            <p class="text-sm">
                © <?php echo date('Y'); ?> ConectCorretores - Sistema de Validação de Imóveis
            </p>
            <p class="text-xs mt-2">
                Desenvolvido por <a href="https://doisr.com.br" target="_blank" class="text-indigo-600 hover:underline">Rafael Dias - doisr.com.br</a>
            </p>
        </div>

    </div>

</body>
</html>
