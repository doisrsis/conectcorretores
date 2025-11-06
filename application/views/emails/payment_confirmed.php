<h1>Pagamento Recebido! ✅</h1>

<p>Olá, <?php echo $nome; ?>!</p>

<p>Confirmamos o recebimento do seu pagamento. Obrigado por manter sua assinatura ativa!</p>

<div class="success-box">
    <strong>💰 Valor Pago:</strong> R$ <?php echo $valor; ?><br>
    <strong>📦 Plano:</strong> <?php echo $plano_nome; ?><br>
    <strong>📅 Data do Pagamento:</strong> <?php echo $data_pagamento; ?>
</div>

<h2>📄 Detalhes do Pagamento:</h2>

<p>Seu pagamento foi processado com sucesso e sua assinatura continua ativa. Você pode acessar o histórico completo de pagamentos no seu painel.</p>

<div style="text-align: center;">
    <a href="<?php echo $site_url; ?>dashboard/assinatura" class="email-button">
        Ver Histórico de Pagamentos
    </a>
</div>

<div class="info-box">
    <strong>📧 Nota Fiscal</strong><br>
    Se você precisa de nota fiscal, entre em contato com nosso suporte informando os dados da sua empresa.
</div>

<p>Continue aproveitando todos os recursos da plataforma!</p>

<p>Atenciosamente,<br>
<strong>Equipe ConectCorretores</strong></p>
