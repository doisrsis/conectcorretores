<h1>Plano Alterado com Sucesso</h1>

<p>Olá, <?php echo $nome; ?>!</p>

<p>Seu plano foi alterado conforme solicitado.</p>

<div class="info-box">
    <strong>📦 Plano Anterior:</strong> <?php echo $plano_antigo; ?> (R$ <?php echo $valor_antigo; ?>/mês)<br>
    <strong>📦 Novo Plano:</strong> <?php echo $plano_novo; ?> (R$ <?php echo $valor_novo; ?>/mês)<br>
    <strong>🏠 Limite de Imóveis:</strong> <?php echo $limite_imoveis; ?> imóveis
</div>

<div class="warning-box">
    <strong>⚠️ Atenção ao Limite de Imóveis</strong><br>
    Seu novo plano permite até <strong><?php echo $limite_imoveis; ?> imóveis</strong>. 
    Se você tiver mais imóveis cadastrados, alguns foram desativados temporariamente.
</div>

<h2>🔧 Próximos Passos:</h2>

<p>Acesse seu painel e escolha quais imóveis deseja manter ativos:</p>

<div style="text-align: center;">
    <a href="<?php echo $site_url; ?>imoveis" class="email-button">
        Gerenciar Meus Imóveis
    </a>
</div>

<div class="info-box">
    <strong>💰 Crédito Proporcional</strong><br>
    O valor pago a mais será creditado na sua próxima fatura.
</div>

<p>Obrigado por continuar conosco!</p>

<p>Atenciosamente,<br>
<strong>Equipe ConectCorretores</strong></p>
