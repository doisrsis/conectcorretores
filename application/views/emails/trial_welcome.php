<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 20px; border-radius: 10px; margin-bottom: 30px;">
    <h1 style="color: #ffffff; margin: 0; font-size: 28px; text-align: center;">
        🎉 Bem-vindo ao ConectCorretores!
    </h1>
    <p style="color: #ffffff; text-align: center; font-size: 16px; margin: 10px 0 0 0;">
        Seu período de teste gratuito começou
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #333;">
    Olá <strong><?php echo $nome; ?></strong>,
</p>

<p style="font-size: 16px; line-height: 1.6; color: #333;">
    Que ótimo ter você conosco! Seu período de teste gratuito do plano <strong><?php echo $plan_nome; ?></strong> foi ativado com sucesso. 🚀
</p>

<div style="background: #f8f9fa; border-left: 4px solid #667eea; padding: 20px; margin: 30px 0; border-radius: 5px;">
    <h3 style="margin: 0 0 15px 0; color: #667eea; font-size: 18px;">📅 Detalhes do seu Trial:</h3>
    <ul style="margin: 0; padding-left: 20px; color: #555;">
        <li style="margin-bottom: 10px;"><strong>Plano:</strong> <?php echo $plan_nome; ?></li>
        <li style="margin-bottom: 10px;"><strong>Duração:</strong> <?php echo $trial_days; ?> dias grátis</li>
        <li style="margin-bottom: 10px;"><strong>Término:</strong> <?php echo $trial_ends_at; ?></li>
        <li><strong>Status:</strong> <span style="color: #28a745;">✓ Ativo</span></li>
    </ul>
</div>

<h3 style="color: #333; font-size: 20px; margin: 30px 0 15px 0;">🎯 O que você pode fazer agora:</h3>

<div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
    <h4 style="margin: 0 0 10px 0; color: #667eea; font-size: 16px;">✨ Explorar todos os recursos</h4>
    <p style="margin: 0; color: #666; font-size: 14px;">
        Cadastre imóveis, gerencie anúncios e teste todas as funcionalidades sem limitações.
    </p>
</div>

<div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
    <h4 style="margin: 0 0 10px 0; color: #667eea; font-size: 16px;">📊 Acompanhar resultados</h4>
    <p style="margin: 0; color: #666; font-size: 14px;">
        Veja estatísticas e métricas dos seus anúncios em tempo real.
    </p>
</div>

<div style="background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 30px;">
    <h4 style="margin: 0 0 10px 0; color: #667eea; font-size: 16px;">🎓 Aprender e crescer</h4>
    <p style="margin: 0; color: #666; font-size: 14px;">
        Acesse nossos tutoriais e dicas para maximizar seus resultados.
    </p>
</div>

<div style="text-align: center; margin: 40px 0;">
    <a href="<?php echo $dashboard_link; ?>" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 50px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
        🚀 Acessar Meu Dashboard
    </a>
</div>

<div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 20px; margin: 30px 0;">
    <h4 style="margin: 0 0 10px 0; color: #856404; font-size: 16px;">💡 Dica Importante:</h4>
    <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
        Aproveite ao máximo seu período de teste! Após <?php echo $trial_days; ?> dias, você pode continuar com uma assinatura paga por apenas <strong>R$ <?php echo number_format($plan_preco ?? 0, 2, ',', '.'); ?>/mês</strong>.
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #333; margin-top: 30px;">
    Estamos aqui para ajudar você a ter sucesso! Se tiver alguma dúvida, não hesite em nos contatar.
</p>

<p style="font-size: 16px; line-height: 1.6; color: #333;">
    Bons negócios! 🏡<br>
    <strong>Equipe ConectCorretores</strong>
</p>
