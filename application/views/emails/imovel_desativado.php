<div style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); padding: 40px 20px; border-radius: 10px; margin-bottom: 30px;">
    <h1 style="color: #ffffff; margin: 0; font-size: 28px; text-align: center;">
        ⚠️ Imóvel Desativado
    </h1>
    <p style="color: #ffffff; text-align: center; font-size: 16px; margin: 10px 0 0 0;">
        Seu imóvel foi desativado por falta de validação
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #333;">
    Olá <strong><?php echo $corretor_nome; ?></strong>,
</p>

<p style="font-size: 16px; line-height: 1.6; color: #333;">
    Informamos que o imóvel abaixo foi <strong>desativado automaticamente</strong> porque não recebemos sua confirmação dentro do prazo de 72 horas.
</p>

<!-- Dados do Imóvel -->
<div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 30px 0; border-radius: 5px;">
    <h3 style="margin: 0 0 15px 0; color: #856404; font-size: 20px;">
        🏠 Imóvel Desativado - #<?php echo $imovel_id; ?>
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
    
    <div style="margin-bottom: 10px;">
        <strong style="color: #333;">Email enviado em:</strong>
        <span style="color: #666;"><?php echo $validacao_enviada_em; ?></span>
    </div>
    
    <div style="margin-bottom: 10px;">
        <strong style="color: #333;">Prazo expirou em:</strong>
        <span style="color: #666;"><?php echo $validacao_expira_em; ?></span>
    </div>
</div>

<!-- Motivo da Desativação -->
<div style="background: #fee; border-left: 4px solid #dc3545; padding: 20px; margin: 30px 0; border-radius: 5px;">
    <h4 style="margin: 0 0 10px 0; color: #dc3545; font-size: 16px;">
        📋 Por que foi desativado?
    </h4>
    <p style="margin: 0; color: #721c24; font-size: 14px; line-height: 1.6;">
        Enviamos um email de validação há <strong>72 horas</strong> solicitando que você confirmasse se o imóvel ainda estava disponível. Como não recebemos sua resposta dentro do prazo, o sistema desativou automaticamente o anúncio para manter nossa plataforma atualizada.
    </p>
</div>

<!-- Como Reativar -->
<div style="background: #e7f3ff; border-radius: 8px; padding: 20px; margin: 30px 0;">
    <h4 style="margin: 0 0 15px 0; color: #0056b3; font-size: 18px;">
        🔄 Como Reativar o Imóvel?
    </h4>
    <p style="margin: 0 0 15px 0; color: #004085; font-size: 14px; line-height: 1.6;">
        Você pode reativar este imóvel a qualquer momento através do painel administrativo:
    </p>
    
    <ol style="color: #004085; font-size: 14px; line-height: 1.8; margin: 0 0 15px 0; padding-left: 20px;">
        <li>Acesse o painel de imóveis</li>
        <li>Localize o imóvel desativado</li>
        <li>Clique em "Reativar"</li>
        <li>Confirme que o imóvel ainda está disponível</li>
    </ol>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="<?php echo $link_imoveis; ?>" style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 50px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
            🔄 Acessar Meus Imóveis
        </a>
    </div>
</div>

<!-- Dicas para Evitar Desativação -->
<div style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 30px 0;">
    <h4 style="margin: 0 0 15px 0; color: #333; font-size: 16px;">
        💡 Dicas para Evitar Desativação Automática
    </h4>
    
    <div style="margin-bottom: 15px;">
        <strong style="color: #333; display: block; margin-bottom: 5px;">✓ Fique atento aos emails</strong>
        <p style="margin: 0; color: #666; font-size: 14px;">
            Sempre que um imóvel completar 60 dias, você receberá um email de validação. Responda dentro de 72 horas.
        </p>
    </div>
    
    <div style="margin-bottom: 15px;">
        <strong style="color: #333; display: block; margin-bottom: 5px;">✓ Atualize o status</strong>
        <p style="margin: 0; color: #666; font-size: 14px;">
            Se o imóvel foi vendido ou alugado, marque no sistema para manter tudo organizado.
        </p>
    </div>
    
    <div style="margin-bottom: 0;">
        <strong style="color: #333; display: block; margin-bottom: 5px;">✓ Mantenha contato ativo</strong>
        <p style="margin: 0; color: #666; font-size: 14px;">
            Verifique regularmente seus imóveis cadastrados e mantenha as informações atualizadas.
        </p>
    </div>
</div>

<!-- Informações Adicionais -->
<div style="background: #e7f3ff; border-radius: 8px; padding: 20px; margin: 30px 0;">
    <h4 style="margin: 0 0 10px 0; color: #0056b3; font-size: 16px;">
        ℹ️ Informações Importantes
    </h4>
    <p style="margin: 0 0 10px 0; color: #004085; font-size: 14px; line-height: 1.6;">
        <strong>Seus dados estão seguros:</strong> Todas as informações do imóvel foram preservadas. Ao reativar, tudo voltará ao normal.
    </p>
    <p style="margin: 0; color: #004085; font-size: 14px; line-height: 1.6;">
        <strong>Não haverá nova validação:</strong> Após reativar, o imóvel não será validado novamente automaticamente.
    </p>
</div>

<!-- Ajuda -->
<div style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 30px 0;">
    <h4 style="margin: 0 0 10px 0; color: #333; font-size: 16px;">
        ❓ Precisa de ajuda?
    </h4>
    <p style="margin: 0; color: #666; font-size: 14px; line-height: 1.6;">
        Se tiver alguma dúvida sobre a desativação ou precisar de ajuda para reativar o imóvel, entre em contato conosco respondendo este email ou através do suporte no painel administrativo.
    </p>
</div>

<p style="font-size: 16px; line-height: 1.6; color: #333; margin-top: 30px;">
    Atenciosamente,<br>
    <strong>Equipe ConectCorretores</strong>
</p>

<hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">

<p style="font-size: 12px; color: #999; text-align: center;">
    Este é um email automático do sistema de validação de imóveis.<br>
    Imóvel desativado por falta de validação (72 horas sem resposta)
</p>
