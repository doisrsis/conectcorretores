<h1>Assinatura Ativada com Sucesso! 🎉</h1>

<p>Olá, <?php echo $nome; ?>!</p>

<p>Sua assinatura do <strong><?php echo $site_name; ?></strong> foi ativada com sucesso e você já pode começar a usar todos os recursos da plataforma!</p>

<div class="success-box">
    <strong>✅ Plano Ativo:</strong> <?php echo $plano_nome; ?><br>
    <strong>💰 Valor:</strong> R$ <?php echo $plano_preco; ?>/mês<br>
    <strong>📅 Data de Início:</strong> <?php echo $data_inicio; ?><br>
    <strong>📅 Próxima Renovação:</strong> <?php echo $data_fim; ?>
</div>

<h2>📦 O Que Está Incluído:</h2>

<ul>
    <li>✅ Até <strong><?php echo $limite_imoveis; ?> imóveis</strong> cadastrados</li>
    <li>✅ Upload de fotos ilimitadas</li>
    <li>✅ Descrições detalhadas</li>
    <li>✅ Gerenciamento completo</li>
    <li>✅ Suporte prioritário</li>
</ul>

<div style="text-align: center;">
    <a href="<?php echo $site_url; ?>dashboard" class="email-button">
        Acessar Meu Painel
    </a>
</div>

<div class="divider"></div>

<h2>🏠 Comece Agora:</h2>

<p>Cadastre seu primeiro imóvel e comece a divulgar seus anúncios!</p>

<div style="text-align: center;">
    <a href="<?php echo $site_url; ?>imoveis/novo" class="email-button">
        Cadastrar Primeiro Imóvel
    </a>
</div>

<div class="info-box">
    <strong>💳 Renovação Automática</strong><br>
    Sua assinatura será renovada automaticamente em <strong><?php echo $data_fim; ?></strong>. 
    Você receberá um lembrete 7 dias antes da renovação.
</div>

<p>Obrigado por escolher o ConectCorretores!</p>

<p>Atenciosamente,<br>
<strong>Equipe ConectCorretores</strong></p>
