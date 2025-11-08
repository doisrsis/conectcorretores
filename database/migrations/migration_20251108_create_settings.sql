-- ========================================
-- Migration: Criar Tabela de Configurações
-- Autor: Rafael Dias - doisr.com.br
-- Data: 08/11/2025
-- Descrição: Sistema centralizado de configurações
-- ========================================

-- Criar tabela settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `chave` varchar(100) NOT NULL COMMENT 'Chave única da configuração',
  `valor` text NOT NULL COMMENT 'Valor da configuração',
  `tipo` enum('string','int','bool','json','float') NOT NULL DEFAULT 'string' COMMENT 'Tipo do valor',
  `grupo` varchar(50) NOT NULL DEFAULT 'geral' COMMENT 'Grupo da configuração',
  `descricao` text DEFAULT NULL COMMENT 'Descrição da configuração',
  `editavel` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Se pode ser editado via interface',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chave` (`chave`),
  KEY `grupo` (`grupo`),
  KEY `editavel` (`editavel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configurações do sistema';

-- Inserir configurações padrão
INSERT INTO `settings` (`chave`, `valor`, `tipo`, `grupo`, `descricao`, `editavel`) VALUES
-- Configurações de Assinaturas
('trial_days_default', '7', 'int', 'assinaturas', 'Dias de período de teste gratuito padrão', 1),
('grace_period_days', '14', 'int', 'assinaturas', 'Dias de período de graça após falha de pagamento', 1),
('max_retry_attempts', '4', 'int', 'assinaturas', 'Número máximo de tentativas de cobrança', 1),
('retry_interval_days', '3', 'int', 'assinaturas', 'Intervalo em dias entre tentativas de cobrança', 1),
('auto_cancel_after_grace', '1', 'bool', 'assinaturas', 'Cancelar automaticamente após período de graça', 1),

-- Configurações do Site
('site_name', 'ConectCorretores', 'string', 'site', 'Nome do site', 1),
('site_email', 'contato@conectcorretores.com.br', 'string', 'site', 'Email principal do site', 1),
('site_phone', '', 'string', 'site', 'Telefone de contato', 1),
('site_description', 'Plataforma para corretores de imóveis', 'string', 'site', 'Descrição do site', 1),
('maintenance_mode', '0', 'bool', 'site', 'Modo de manutenção ativado', 1),

-- Configurações de Email
('email_from_name', 'ConectCorretores', 'string', 'email', 'Nome do remetente dos emails', 1),
('email_from_address', 'noreply@conectcorretores.com.br', 'string', 'email', 'Email remetente', 1),
('email_payment_failed', '1', 'bool', 'email', 'Enviar email em caso de falha de pagamento', 1),
('email_subscription_created', '1', 'bool', 'email', 'Enviar email ao criar assinatura', 1),
('email_subscription_canceled', '1', 'bool', 'email', 'Enviar email ao cancelar assinatura', 1),
('email_trial_ending', '1', 'bool', 'email', 'Enviar email antes do fim do período de teste', 1),
('email_trial_ending_days', '2', 'int', 'email', 'Dias antes do fim do trial para enviar lembrete', 1),

-- Configurações de Segurança
('max_login_attempts', '5', 'int', 'seguranca', 'Tentativas máximas de login', 1),
('login_lockout_minutes', '15', 'int', 'seguranca', 'Minutos de bloqueio após tentativas excedidas', 1),
('password_min_length', '6', 'int', 'seguranca', 'Tamanho mínimo da senha', 1),
('session_timeout_minutes', '120', 'int', 'seguranca', 'Tempo de expiração da sessão em minutos', 1),

-- Configurações de Imóveis
('max_images_per_property', '20', 'int', 'imoveis', 'Máximo de imagens por imóvel', 1),
('image_max_size_mb', '5', 'int', 'imoveis', 'Tamanho máximo de imagem em MB', 1),
('auto_approve_properties', '0', 'bool', 'imoveis', 'Aprovar imóveis automaticamente', 1),

-- Configurações de Sistema
('system_version', '1.5.0', 'string', 'sistema', 'Versão atual do sistema', 0),
('system_timezone', 'America/Sao_Paulo', 'string', 'sistema', 'Fuso horário do sistema', 1),
('system_locale', 'pt_BR', 'string', 'sistema', 'Locale do sistema', 1),
('debug_mode', '0', 'bool', 'sistema', 'Modo de debug ativado', 1),
('cache_enabled', '1', 'bool', 'sistema', 'Cache de configurações ativado', 1),
('cache_duration_minutes', '60', 'int', 'sistema', 'Duração do cache em minutos', 1);

-- ========================================
-- Verificação
-- ========================================
SELECT 
    COUNT(*) as total_configuracoes,
    COUNT(DISTINCT grupo) as total_grupos
FROM settings;

-- Listar configurações por grupo
SELECT 
    grupo,
    COUNT(*) as total
FROM settings
GROUP BY grupo
ORDER BY grupo;
