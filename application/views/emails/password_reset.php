<h1>Redefinir Sua Senha 🔐</h1>

<p>Olá, <?php echo $nome; ?>!</p>

<p>Recebemos uma solicitação para redefinir a senha da sua conta no <strong><?php echo $site_name; ?></strong>.</p>

<div class="info-box">
    <strong>⏰ Validade do Link:</strong> <?php echo $validade; ?><br>
    <strong>🔒 Segurança:</strong> Este link só pode ser usado uma vez
</div>

<h2>🔧 Redefinir Senha:</h2>

<p>Clique no botão abaixo para criar uma nova senha:</p>

<div style="text-align: center;">
    <a href="<?php echo $reset_link; ?>" class="email-button">
        Redefinir Minha Senha
    </a>
</div>

<p style="font-size: 14px; color: #666;">
    Ou copie e cole este link no seu navegador:<br>
    <a href="<?php echo $reset_link; ?>"><?php echo $reset_link; ?></a>
</p>

<div class="warning-box">
    <strong>⚠️ Não Solicitou Esta Alteração?</strong><br>
    Se você não pediu para redefinir sua senha, ignore este email. 
    Sua senha permanecerá a mesma e sua conta está segura.
</div>

<h2>🔒 Dicas de Segurança:</h2>

<ul>
    <li>✅ Use uma senha forte com letras, números e símbolos</li>
    <li>✅ Não compartilhe sua senha com ninguém</li>
    <li>✅ Use senhas diferentes para cada serviço</li>
    <li>✅ Considere usar um gerenciador de senhas</li>
</ul>

<div class="info-box">
    <strong>💬 Precisa de ajuda?</strong><br>
    Se tiver problemas para redefinir sua senha, entre em contato com nosso suporte.
</div>

<p>Atenciosamente,<br>
<strong>Equipe ConectCorretores</strong></p>
