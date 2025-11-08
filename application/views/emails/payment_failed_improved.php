<h1>⚠️ Problema com seu Pagamento</h1>

<p>Olá, <?php echo $nome; ?>!</p>

<p>Infelizmente, não conseguimos processar o pagamento da sua assinatura do <strong><?php echo $plano_nome; ?></strong>.</p>

<?php if ($tentativa == 1): ?>
    <div class="warning-box">
        <strong>⚠️ Primeira Tentativa</strong><br>
        Não se preocupe! Tentaremos processar o pagamento automaticamente nos próximos dias.
    </div>
<?php elseif ($tentativa == 2): ?>
    <div class="warning-box">
        <strong>⚠️ Segunda Tentativa</strong><br>
        Esta é a segunda tentativa de cobrança. Por favor, verifique seu método de pagamento.
    </div>
<?php elseif ($tentativa == 3): ?>
    <div class="warning-box" style="background-color: #FEF3C7; border-color: #F59E0B;">
        <strong>🚨 Terceira Tentativa</strong><br>
        Atenção! Esta é a penúltima tentativa. Atualize seu método de pagamento urgentemente.
    </div>
<?php else: ?>
    <div class="warning-box" style="background-color: #FEE2E2; border-color: #DC2626;">
        <strong>🚨 ÚLTIMA TENTATIVA</strong><br>
        Esta é a última tentativa! Sua assinatura será cancelada se não resolvermos o problema.
    </div>
<?php endif; ?>

<h2>📋 Detalhes:</h2>

<div class="info-box">
    <strong>📦 Plano:</strong> <?php echo $plano_nome; ?><br>
    <strong>💰 Valor:</strong> R$ <?php echo $valor; ?><br>
    <strong>🔄 Tentativa:</strong> <?php echo $tentativa; ?>ª de 4<br>
    <?php if ($dias_restantes > 0): ?>
        <strong>⏰ Tempo restante:</strong> <?php echo $dias_restantes; ?> dias
    <?php else: ?>
        <strong>⏰ Status:</strong> <span style="color: #DC2626;">Cancelamento iminente</span>
    <?php endif; ?>
</div>

<h2>🔧 Como Resolver:</h2>

<p>Para manter sua assinatura ativa, você precisa atualizar seu método de pagamento:</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="<?php echo $portal_url; ?>" class="email-button" style="background-color: #DC2626;">
        🔧 Atualizar Método de Pagamento AGORA
    </a>
</div>

<h3>💳 Possíveis Causas:</h3>

<ul>
    <li>Cartão de crédito expirado</li>
    <li>Saldo insuficiente</li>
    <li>Cartão bloqueado ou cancelado</li>
    <li>Limite de crédito atingido</li>
    <li>Dados do cartão incorretos</li>
</ul>

<h3>✅ O Que Fazer:</h3>

<ol>
    <li><strong>Clique no botão acima</strong> para acessar o portal de gerenciamento</li>
    <li><strong>Atualize seu cartão</strong> ou adicione um novo método de pagamento</li>
    <li><strong>Aguarde a confirmação</strong> - tentaremos processar novamente automaticamente</li>
</ol>

<?php if ($dias_restantes > 0): ?>
    <div class="info-box">
        <strong>⏰ Você tem <?php echo $dias_restantes; ?> dias</strong> para resolver o problema antes que sua assinatura seja cancelada e seus imóveis sejam desativados.
    </div>
<?php else: ?>
    <div class="warning-box" style="background-color: #FEE2E2; border-color: #DC2626;">
        <strong>🚨 URGENTE!</strong><br>
        Sua assinatura será cancelada em breve se o pagamento não for processado. Atualize seu método de pagamento IMEDIATAMENTE!
    </div>
<?php endif; ?>

<h2>❓ Precisa de Ajuda?</h2>

<p>Nossa equipe de suporte está pronta para ajudá-lo!</p>

<div class="info-box">
    <strong>📧 Email:</strong> suporte@conectcorretores.com.br<br>
    <strong>💬 WhatsApp:</strong> (11) 99999-9999<br>
    <strong>⏰ Horário:</strong> Segunda a Sexta, 9h às 18h
</div>

<p>Atenciosamente,<br>
<strong>Equipe ConectCorretores</strong></p>

<p style="font-size: 12px; color: #666; margin-top: 30px;">
    <em>Este é um email automático sobre o status do seu pagamento. Por favor, não responda diretamente a este email.</em>
</p>
