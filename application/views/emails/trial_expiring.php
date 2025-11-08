<div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 40px 20px; border-radius: 10px; margin-bottom: 30px;">
    <h1 style="color: #ffffff; margin: 0; font-size: 28px; text-align: center;">
        ⏰ Seu Trial Está Terminando
    </h1>
    <p style="color: #ffffff; text-align: center; font-size: 16px; margin: 10px 0 0 0;">
        Não perca acesso aos seus imóveis!
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #333;">
    Olá <strong><?php echo $nome; ?></strong>,
</p>

<p style="font-size: 16px; line-height: 1.6; color: #333;">
    Esperamos que esteja aproveitando o ConectCorretores! Queremos lembrar que seu período de teste gratuito do plano <strong><?php echo $plan_nome; ?></strong> está chegando ao fim.
</p>

<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 30px 0; border-radius: 5px;">
    <h3 style="margin: 0 0 15px 0; color: #856404; font-size: 20px; text-align: center;">
        ⏳ Restam apenas <strong><?php echo $days_left; ?> <?php echo $days_left == 1 ? 'dia' : 'dias'; ?></strong>
    </h3>
    <p style="margin: 0; color: #856404; font-size: 16px; text-align: center;">
        Seu trial expira em <strong><?php echo $trial_ends_at; ?></strong>
    </p>
</div>

<h3 style="color: #333; font-size: 20px; margin: 30px 0 15px 0;">🎯 Continue aproveitando todos os benefícios:</h3>

<div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
    <div style="display: flex; align-items: center;">
        <span style="font-size: 24px; margin-right: 15px;">✅</span>
        <div>
            <h4 style="margin: 0 0 5px 0; color: #333; font-size: 16px;">Anúncios ilimitados</h4>
            <p style="margin: 0; color: #666; font-size: 14px;">Cadastre quantos imóveis quiser</p>
        </div>
    </div>
</div>

<div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
    <div style="display: flex; align-items: center;">
        <span style="font-size: 24px; margin-right: 15px;">✅</span>
        <div>
            <h4 style="margin: 0 0 5px 0; color: #333; font-size: 16px;">Estatísticas completas</h4>
            <p style="margin: 0; color: #666; font-size: 14px;">Acompanhe o desempenho dos seus anúncios</p>
        </div>
    </div>
</div>

<div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 30px;">
    <div style="display: flex; align-items: center;">
        <span style="font-size: 24px; margin-right: 15px;">✅</span>
        <div>
            <h4 style="margin: 0 0 5px 0; color: #333; font-size: 16px;">Suporte prioritário</h4>
            <p style="margin: 0; color: #666; font-size: 14px;">Ajuda sempre que precisar</p>
        </div>
    </div>
</div>

<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; padding: 30px; text-align: center; margin: 40px 0;">
    <h3 style="color: #ffffff; margin: 0 0 15px 0; font-size: 24px;">💎 Plano <?php echo $plan_nome; ?></h3>
    <p style="color: #ffffff; font-size: 32px; font-weight: bold; margin: 10px 0;">
        R$ <?php echo $plan_preco; ?><span style="font-size: 18px; font-weight: normal;">/mês</span>
    </p>
    <p style="color: #ffffff; font-size: 14px; margin: 15px 0 25px 0;">
        Cancele quando quiser, sem multas ou taxas
    </p>
    <a href="<?php echo $upgrade_link; ?>" style="display: inline-block; background: #ffffff; color: #667eea; text-decoration: none; padding: 15px 40px; border-radius: 50px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
        🚀 Continuar com Plano Pago
    </a>
</div>

<div style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 30px 0;">
    <h4 style="margin: 0 0 10px 0; color: #333; font-size: 16px;">❓ O que acontece se eu não assinar?</h4>
    <p style="margin: 0; color: #666; font-size: 14px; line-height: 1.6;">
        Após o término do trial, você perderá acesso aos seus imóveis cadastrados e todas as funcionalidades premium. Mas não se preocupe, seus dados ficarão salvos por 30 dias caso decida voltar!
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #333; margin-top: 30px;">
    Tem alguma dúvida? Estamos aqui para ajudar! Responda este email ou entre em contato conosco.
</p>

<p style="font-size: 16px; line-height: 1.6; color: #333;">
    Até breve! 👋<br>
    <strong>Equipe ConectCorretores</strong>
</p>
