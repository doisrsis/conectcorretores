<h1>Upgrade Realizado com Sucesso! 🚀</h1>

<p>Olá, <?php echo $nome; ?>!</p>

<p>Parabéns! Você fez upgrade do seu plano e agora tem acesso a ainda mais recursos!</p>

<div class="success-box">
    <strong>📦 Plano Anterior:</strong> <?php echo $plano_antigo; ?> (R$ <?php echo $valor_antigo; ?>/mês)<br>
    <strong>🎉 Novo Plano:</strong> <?php echo $plano_novo; ?> (R$ <?php echo $valor_novo; ?>/mês)<br>
    <strong>🏠 Limite de Imóveis:</strong> <?php echo $limite_imoveis; ?> imóveis
</div>

<h2>✨ Novos Benefícios:</h2>

<p>Com seu novo plano, você pode:</p>

<ul>
    <li>✅ Cadastrar até <strong><?php echo $limite_imoveis; ?> imóveis</strong></li>
    <li>✅ Mais espaço para crescer seu negócio</li>
    <li>✅ Todos os recursos premium</li>
</ul>

<div style="text-align: center;">
    <a href="<?php echo $site_url; ?>imoveis/novo" class="email-button">
        Cadastrar Mais Imóveis
    </a>
</div>

<div class="info-box">
    <strong>💰 Cobrança Proporcional</strong><br>
    O valor foi ajustado proporcionalmente ao tempo restante do seu ciclo de cobrança.
</div>

<p>Aproveite seu novo plano!</p>

<p>Atenciosamente,<br>
<strong>Equipe ConectCorretores</strong></p>
