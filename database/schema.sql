-- ========================================
-- Schema do Banco de Dados - ConectCorretores
-- Autor: Rafael Dias - doisr.com.br
-- Data: 18/10/2025
-- ========================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS `corretor_saas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `corretor_saas`;

-- ========================================
-- Tabela: users (Corretores e Admin)
-- ========================================
CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `endereco` text DEFAULT NULL,
  `role` enum('admin','corretor') NOT NULL DEFAULT 'corretor',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`),
  KEY `ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Tabela: plans (Planos de Assinatura)
-- ========================================
CREATE TABLE `plans` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `tipo` enum('mensal','trimestral','semestral','anual') NOT NULL DEFAULT 'mensal',
  `stripe_price_id` varchar(255) DEFAULT NULL COMMENT 'ID do preço no Stripe',
  `stripe_product_id` varchar(255) DEFAULT NULL COMMENT 'ID do produto no Stripe',
  `limite_imoveis` int(11) DEFAULT NULL COMMENT 'Limite de imóveis (NULL = ilimitado)',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tipo` (`tipo`),
  KEY `ativo` (`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Tabela: subscriptions (Assinaturas)
-- ========================================
CREATE TABLE `subscriptions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `plan_id` int(11) UNSIGNED NOT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL COMMENT 'ID da assinatura no Stripe',
  `stripe_customer_id` varchar(255) DEFAULT NULL COMMENT 'ID do cliente no Stripe',
  `status` enum('ativa','cancelada','pendente','expirada') NOT NULL DEFAULT 'pendente',
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `cancelada_em` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `plan_id` (`plan_id`),
  KEY `status` (`status`),
  KEY `data_fim` (`data_fim`),
  CONSTRAINT `fk_subscriptions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_subscriptions_plan` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Tabela: imoveis
-- ========================================
CREATE TABLE `imoveis` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL COMMENT 'Corretor responsável',
  `tipo_negocio` enum('compra','aluguel') NOT NULL,
  `tipo_imovel` varchar(50) NOT NULL COMMENT 'Casa, Apartamento, Terreno, etc',
  `estado` varchar(2) NOT NULL COMMENT 'UF',
  `cidade` varchar(100) NOT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `quartos` int(11) NOT NULL DEFAULT 0,
  `suites` int(11) NOT NULL DEFAULT 0,
  `banheiros` int(11) NOT NULL DEFAULT 0,
  `vagas` int(11) NOT NULL DEFAULT 0,
  `area_privativa` decimal(10,2) NOT NULL COMMENT 'Área em m²',
  `area_total` decimal(10,2) DEFAULT NULL COMMENT 'Área total em m²',
  `preco` decimal(12,2) NOT NULL,
  `valor_m2` decimal(10,2) NOT NULL COMMENT 'Preço por m²',
  `condominio` decimal(10,2) DEFAULT NULL,
  `iptu` decimal(10,2) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `caracteristicas` text DEFAULT NULL COMMENT 'JSON com características',
  `link` varchar(500) DEFAULT NULL COMMENT 'Link do anúncio',
  `telefone` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `imagens` text DEFAULT NULL COMMENT 'JSON com URLs das imagens',
  `destaque` tinyint(1) NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `tipo_negocio` (`tipo_negocio`),
  KEY `tipo_imovel` (`tipo_imovel`),
  KEY `estado` (`estado`),
  KEY `cidade` (`cidade`),
  KEY `preco` (`preco`),
  KEY `ativo` (`ativo`),
  KEY `destaque` (`destaque`),
  FULLTEXT KEY `ft_search` (`descricao`,`caracteristicas`),
  CONSTRAINT `fk_imoveis_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Tabela: sessions (Sessões do CodeIgniter)
-- ========================================
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- Dados Iniciais: Usuário Admin
-- ========================================
INSERT INTO `users` (`nome`, `email`, `senha`, `role`, `ativo`) VALUES
('Administrador', 'admin@conectcorretores.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);
-- Senha: password

-- ========================================
-- Dados Iniciais: Planos
-- ========================================
INSERT INTO `plans` (`nome`, `descricao`, `preco`, `tipo`, `limite_imoveis`, `ativo`) VALUES
('Plano Mensal', 'Acesso completo ao sistema por 1 mês', 49.90, 'mensal', 50, 1),
('Plano Trimestral', 'Acesso completo ao sistema por 3 meses com 10% de desconto', 134.73, 'trimestral', 100, 1),
('Plano Semestral', 'Acesso completo ao sistema por 6 meses com 15% de desconto', 254.49, 'semestral', 200, 1),
('Plano Anual', 'Acesso completo ao sistema por 1 ano com 20% de desconto', 479.04, 'anual', NULL, 1);

-- ========================================
-- Índices Adicionais para Performance
-- ========================================

-- Índice composto para busca de imóveis
ALTER TABLE `imoveis` ADD INDEX `idx_busca` (`estado`, `cidade`, `tipo_negocio`, `ativo`);

-- Índice para assinaturas ativas
ALTER TABLE `subscriptions` ADD INDEX `idx_ativas` (`user_id`, `status`, `data_fim`);

-- ========================================
-- Views Úteis
-- ========================================

-- View: Assinaturas com informações do plano e usuário
CREATE OR REPLACE VIEW `v_subscriptions_completa` AS
SELECT 
    s.id,
    s.user_id,
    u.nome AS corretor_nome,
    u.email AS corretor_email,
    s.plan_id,
    p.nome AS plan_nome,
    p.tipo AS plan_tipo,
    p.preco AS plan_preco,
    s.stripe_subscription_id,
    s.stripe_customer_id,
    s.status,
    s.data_inicio,
    s.data_fim,
    DATEDIFF(s.data_fim, CURDATE()) AS dias_restantes,
    s.created_at
FROM subscriptions s
INNER JOIN users u ON s.user_id = u.id
INNER JOIN plans p ON s.plan_id = p.id;

-- View: Imóveis com informações do corretor
CREATE OR REPLACE VIEW `v_imoveis_completa` AS
SELECT 
    i.*,
    u.nome AS corretor_nome,
    u.email AS corretor_email,
    u.telefone AS corretor_telefone,
    u.whatsapp AS corretor_whatsapp
FROM imoveis i
INNER JOIN users u ON i.user_id = u.id;

-- View: Estatísticas por corretor
CREATE OR REPLACE VIEW `v_stats_corretor` AS
SELECT 
    u.id AS user_id,
    u.nome,
    u.email,
    COUNT(DISTINCT i.id) AS total_imoveis,
    COUNT(DISTINCT CASE WHEN i.tipo_negocio = 'compra' THEN i.id END) AS imoveis_venda,
    COUNT(DISTINCT CASE WHEN i.tipo_negocio = 'aluguel' THEN i.id END) AS imoveis_aluguel,
    COUNT(DISTINCT s.id) AS total_assinaturas,
    MAX(s.data_fim) AS ultima_assinatura_fim
FROM users u
LEFT JOIN imoveis i ON u.id = i.user_id AND i.ativo = 1
LEFT JOIN subscriptions s ON u.id = s.user_id
WHERE u.role = 'corretor'
GROUP BY u.id, u.nome, u.email;

-- ========================================
-- Triggers
-- ========================================

-- Trigger: Calcular valor por m² automaticamente
DELIMITER $$
CREATE TRIGGER `tr_imoveis_valor_m2` BEFORE INSERT ON `imoveis`
FOR EACH ROW
BEGIN
    IF NEW.area_privativa > 0 THEN
        SET NEW.valor_m2 = NEW.preco / NEW.area_privativa;
    END IF;
END$$

CREATE TRIGGER `tr_imoveis_valor_m2_update` BEFORE UPDATE ON `imoveis`
FOR EACH ROW
BEGIN
    IF NEW.area_privativa > 0 THEN
        SET NEW.valor_m2 = NEW.preco / NEW.area_privativa;
    END IF;
END$$
DELIMITER ;

-- ========================================
-- Stored Procedures Úteis
-- ========================================

-- Procedure: Verificar se assinatura está ativa
DELIMITER $$
CREATE PROCEDURE `sp_verificar_assinatura`(IN p_user_id INT)
BEGIN
    SELECT 
        CASE 
            WHEN COUNT(*) > 0 THEN 1
            ELSE 0
        END AS assinatura_ativa
    FROM subscriptions
    WHERE user_id = p_user_id
      AND status = 'ativa'
      AND data_fim >= CURDATE();
END$$
DELIMITER ;

-- Procedure: Estatísticas do dashboard admin
DELIMITER $$
CREATE PROCEDURE `sp_dashboard_admin`()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM users WHERE role = 'corretor') AS total_corretores,
        (SELECT COUNT(*) FROM imoveis WHERE ativo = 1) AS total_imoveis,
        (SELECT COUNT(*) FROM subscriptions WHERE status = 'ativa') AS assinaturas_ativas,
        (SELECT SUM(p.preco) FROM subscriptions s INNER JOIN plans p ON s.plan_id = p.id WHERE s.status = 'ativa') AS receita_mensal;
END$$
DELIMITER ;

-- ========================================
-- Comentários nas Tabelas
-- ========================================
ALTER TABLE `users` COMMENT = 'Tabela de usuários (corretores e administradores)';
ALTER TABLE `plans` COMMENT = 'Planos de assinatura disponíveis';
ALTER TABLE `subscriptions` COMMENT = 'Assinaturas dos corretores';
ALTER TABLE `imoveis` COMMENT = 'Imóveis cadastrados pelos corretores';

-- ========================================
-- FIM DO SCHEMA
-- ========================================
