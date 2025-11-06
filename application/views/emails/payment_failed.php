<h1>Problema com seu Pagamento ⚠️</h1>

<p>Olá, <?php echo $nome; ?>!</p>

<p>Infelizmente, não conseguimos processar o pagamento da sua assinatura do <strong><?php echo $plano_nome; ?></strong>.</p>

<div class="warning-box">
    <strong>⚠️ Ação Necessária</strong><br>
    Seu pagamento falhou e sua assinatura pode ser cancelada se não for resolvido em breve.
</div>

<h2>📋 Detalhes:</h2>

<div class="info-box">
    <strong>📦 Plano:</strong> <?php echo $plano_nome; ?><br>
    <strong>💰 Valor:</strong> R$ <?php echo $valor; ?>
</div>

<h2>🔧 Como Resolver:</h2>

<p>Para manter sua assinatura ativa, siga um destes passos:</p>

<ol>
    <li><strong>Atualizar método de pagamento</strong> - Verifique se seu cartão está válido e com saldo</li>
    <li><strong>Tentar novamente</strong> - Tentaremos processar o pagamento automaticamente nos próximos dias</li>
    <li><strong>Usar outro método</strong> - Cadastre um novo cartão de crédito</li>
</ol>

<div style="text-align: center;">
    <a href="<?php echo $site_url; ?>dashboard/assinatura" class="email-button">
        Atualizar Método de Pagamento
    </a>
</div>

<h2>⏰ Prazo:</h2>

<p>Você tem <strong>7 dias</strong> para resolver o problema antes que sua assinatura seja cancelada e seus imóveis sejam desativados.</p>

<div class="info-box">
    <strong>💬 Precisa de ajuda?</strong><br>
    Nossa equipe de suporte está pronta para ajudá-lo! Entre em contato conosco.
</div>

<p>Atenciosamente,<br>
<strong>Equipe ConectCorretores</strong></p>
