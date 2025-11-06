<h1>Assinatura Cancelada 😔</h1>

<p>Olá, <?php echo $nome; ?>!</p>

<p>Confirmamos o cancelamento da sua assinatura do <strong><?php echo $plano_nome; ?></strong>.</p>

<div class="info-box">
    <strong>📦 Plano Cancelado:</strong> <?php echo $plano_nome; ?><br>
    <strong>📅 Data de Término:</strong> <?php echo $data_termino; ?>
</div>

<h2>📋 O Que Acontece Agora:</h2>

<ul>
    <li>✅ Você ainda tem acesso até <strong><?php echo $data_termino; ?></strong></li>
    <li>⚠️ Após essa data, seus imóveis serão desativados</li>
    <li>✅ Seus dados permanecerão salvos por 90 dias</li>
    <li>✅ Você pode reativar a qualquer momento</li>
</ul>

<div class="warning-box">
    <strong>⚠️ Importante</strong><br>
    Após <?php echo $data_termino; ?>, você não poderá mais acessar ou gerenciar seus imóveis até reativar sua assinatura.
</div>

<h2>💬 Conte-nos o Motivo:</h2>

<p>Sentiremos sua falta! Se puder, nos conte o que podemos melhorar:</p>

<div style="text-align: center;">
    <a href="<?php echo $site_url; ?>feedback/cancelamento" class="email-button">
        Enviar Feedback
    </a>
</div>

<h2>🔄 Mudou de Ideia?</h2>

<p>Você pode reativar sua assinatura a qualquer momento:</p>

<div style="text-align: center;">
    <a href="<?php echo $site_url; ?>planos" class="email-button">
        Reativar Assinatura
    </a>
</div>

<p>Obrigado por ter usado o ConectCorretores!</p>

<p>Atenciosamente,<br>
<strong>Equipe ConectCorretores</strong></p>
