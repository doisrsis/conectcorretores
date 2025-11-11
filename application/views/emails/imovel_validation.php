<div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 40px 20px; border-radius: 10px; margin-bottom: 30px;">
    <h1 style="color: #ffffff; margin: 0; font-size: 28px; text-align: center;">
        ⚠️ Validação Necessária
    </h1>
    <p style="color: #ffffff; text-align: center; font-size: 16px; margin: 10px 0 0 0;">
        Confirme a disponibilidade do seu imóvel
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #333;">
    Olá <strong><?php echo $corretor_nome; ?></strong>,
</p>

<p style="font-size: 16px; line-height: 1.6; color: #333;">
    Notamos que o imóvel abaixo está cadastrado há <strong>60 dias</strong> em nossa plataforma. Para manter a qualidade dos anúncios, precisamos confirmar se ele ainda está disponível.
</p>

<!-- Dados do Imóvel -->
<div style="background: #f8f9fa; border-left: 4px solid #667eea; padding: 20px; margin: 30px 0; border-radius: 5px;">
    <h3 style="margin: 0 0 15px 0; color: #667eea; font-size: 20px;">
        🏠 Imóvel #<?php echo $imovel_id; ?>
    </h3>
    
    <div style="margin-bottom: 10px;">
        <strong style="color: #333;">Tipo:</strong>
        <span style="color: #666;"><?php echo $tipo_imovel; ?> para <?php echo $tipo_negocio; ?></span>
    </div>
    
    <div style="margin-bottom: 10px;">
        <strong style="color: #333;">Localização:</strong>
        <span style="color: #666;"><?php echo $endereco_completo; ?></span>
    </div>
    
    <div style="margin-bottom: 10px;">
        <strong style="color: #333;">Valor:</strong>
        <span style="color: #666; font-size: 18px; font-weight: bold;">R$ <?php echo $preco; ?></span>
    </div>
    
    <div style="margin-bottom: 10px;">
        <strong style="color: #333;">Cadastrado em:</strong>
        <span style="color: #666;"><?php echo $created_at; ?></span>
    </div>
</div>

<!-- Alerta de Prazo -->
<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 30px 0; border-radius: 5px;">
    <h3 style="margin: 0 0 15px 0; color: #856404; font-size: 20px; text-align: center;">
        ⏰ Você tem <strong>72 horas</strong> para responder
    </h3>
    <p style="margin: 0; color: #856404; font-size: 16px; text-align: center;">
        Prazo expira em: <strong><?php echo $expira_em; ?></strong>
    </p>
</div>

<h3 style="color: #333; font-size: 20px; margin: 30px 0 20px 0; text-align: center;">
    📋 Qual é o status deste imóvel?
</h3>

<!-- Botão: Ainda Disponível -->
<div style="margin: 20px 0;">
    <a href="<?php echo $link_confirmar; ?>" style="display: block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 20px; border-radius: 10px; text-align: center; font-weight: bold; font-size: 18px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
        ✅ Ainda Está Disponível
    </a>
    <p style="text-align: center; color: #666; font-size: 14px; margin: 10px 0 0 0;">
        O imóvel continua ativo e disponível para negociação
    </p>
</div>

<!-- Botão: Foi Vendido -->
<div style="margin: 20px 0;">
    <a href="<?php echo $link_vendido; ?>" style="display: block; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: #ffffff; text-decoration: none; padding: 20px; border-radius: 10px; text-align: center; font-weight: bold; font-size: 18px; box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);">
        🎉 Foi Vendido
    </a>
    <p style="text-align: center; color: #666; font-size: 14px; margin: 10px 0 0 0;">
        Parabéns! O imóvel foi vendido e será desativado
    </p>
</div>

<!-- Botão: Foi Alugado -->
<div style="margin: 20px 0;">
    <a href="<?php echo $link_alugado; ?>" style="display: block; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #ffffff; text-decoration: none; padding: 20px; border-radius: 10px; text-align: center; font-weight: bold; font-size: 18px; box-shadow: 0 4px 15px rgba(250, 112, 154, 0.3);">
        🏢 Foi Alugado
    </a>
    <p style="text-align: center; color: #666; font-size: 14px; margin: 10px 0 0 0;">
        O imóvel foi alugado e será desativado
    </p>
</div>

<!-- Aviso Importante -->
<div style="background: #fee; border-left: 4px solid #dc3545; padding: 20px; margin: 30px 0; border-radius: 5px;">
    <h4 style="margin: 0 0 10px 0; color: #dc3545; font-size: 16px;">
        ⚠️ Atenção: Desativação Automática
    </h4>
    <p style="margin: 0; color: #721c24; font-size: 14px; line-height: 1.6;">
        Se não recebermos sua resposta em <strong>72 horas</strong>, o imóvel será <strong>automaticamente desativado</strong> do sistema. Você poderá reativá-lo manualmente a qualquer momento pelo painel administrativo.
    </p>
</div>

<!-- Informações Adicionais -->
<div style="background: #e7f3ff; border-radius: 8px; padding: 20px; margin: 30px 0;">
    <h4 style="margin: 0 0 15px 0; color: #0056b3; font-size: 16px;">
        💡 Por que fazemos isso?
    </h4>
    <p style="margin: 0 0 10px 0; color: #004085; font-size: 14px; line-height: 1.6;">
        Mantemos nossa plataforma atualizada para oferecer a melhor experiência aos clientes. Imóveis desatualizados prejudicam a credibilidade e geram frustração.
    </p>
    <p style="margin: 0; color: #004085; font-size: 14px; line-height: 1.6;">
        Esta validação automática garante que apenas imóveis realmente disponíveis sejam exibidos, aumentando suas chances de negociação!
    </p>
</div>

<!-- Ajuda -->
<div style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 30px 0;">
    <h4 style="margin: 0 0 10px 0; color: #333; font-size: 16px;">
        ❓ Precisa de ajuda?
    </h4>
    <p style="margin: 0; color: #666; font-size: 14px; line-height: 1.6;">
        Se tiver alguma dúvida ou problema, entre em contato conosco respondendo este email ou através do suporte no painel administrativo.
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #333; margin-top: 30px;">
    Atenciosamente,<br>
    <strong>Equipe ConectCorretores</strong>
</p>

<hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">

<p style="font-size: 12px; color: #999; text-align: center;">
    Este é um email automático do sistema de validação de imóveis.<br>
    Imóvel cadastrado há 60 dias • Prazo de resposta: 72 horas
</p>
