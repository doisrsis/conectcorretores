<h1>Seu plano renova em <?php echo $dias_restantes; ?> dias 📅</h1>

<p>Olá, <?php echo $nome; ?>!</p>

<p>Este é um lembrete amigável de que sua assinatura do <strong><?php echo $plano_nome; ?></strong> será renovada automaticamente em breve.</p>

<div class="info-box">
    <strong>📦 Plano:</strong> <?php echo $plano_nome; ?><br>
    <strong>📅 Data de Renovação:</strong> <?php echo $data_renovacao; ?><br>
    <strong>💰 Valor:</strong> R$ <?php echo $valor; ?><br>
    <strong>⏰ Dias Restantes:</strong> <?php echo $dias_restantes; ?> dias
</div>

<h2>💳 Renovação Automática:</h2>

<p>Sua assinatura será renovada automaticamente usando o método de pagamento cadastrado. Você não precisa fazer nada!</p>

<div class="success-box">
    <strong>✅ Tudo pronto!</strong><br>
    Seu método de pagamento está ativo e a renovação ocorrerá automaticamente.
</div>

<h2>🔧 Precisa Fazer Alterações?</h2>

<p>Se você deseja:</p>

<ul>
    <li>Atualizar seu método de pagamento</li>
    <li>Fazer upgrade ou downgrade do plano</li>
    <li>Cancelar a renovação</li>
</ul>

<p>Acesse seu painel e faça as alterações necessárias:</p>

<div style="text-align: center;">
    <a href="<?php echo $site_url; ?>dashboard/assinatura" class="email-button">
        Gerenciar Minha Assinatura
    </a>
</div>

<div class="info-box">
    <strong>💡 Dica:</strong> Você pode cancelar sua assinatura a qualquer momento, sem multas ou taxas adicionais.
</div>

<p>Obrigado por continuar conosco!</p>

<p>Atenciosamente,<br>
<strong>Equipe ConectCorretores</strong></p>
