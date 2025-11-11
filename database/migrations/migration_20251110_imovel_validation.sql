/**
 * Migration: Sistema de Validação de Imóveis (60 dias)
 * 
 * Autor: Rafael Dias - doisr.com.br
 * Data: 10/11/2025
 * 
 * Descrição:
 * - Adiciona campo status_venda (disponivel, vendido, alugado)
 * - Adiciona campos de controle de validação (60 dias + 72h)
 * - Sistema automático de desativação de imóveis não validados
 * 
 * Funcionalidade:
 * 1. Após 60 dias do cadastro, sistema envia email ao corretor
 * 2. Corretor tem 72 horas para confirmar disponibilidade
 * 3. Sem resposta = imóvel desativado automaticamente
 */

-- ========================================
-- ADICIONAR CAMPOS DE STATUS DE VENDA
-- ========================================

ALTER TABLE imoveis 
ADD COLUMN status_venda ENUM('disponivel', 'vendido', 'alugado') NOT NULL DEFAULT 'disponivel' AFTER ativo;

ALTER TABLE imoveis 
ADD COLUMN data_venda DATE NULL AFTER status_venda;

-- ========================================
-- ADICIONAR CAMPOS DE CONTROLE DE VALIDAÇÃO
-- ========================================

ALTER TABLE imoveis 
ADD COLUMN validacao_enviada_em DATETIME NULL AFTER data_venda;

ALTER TABLE imoveis 
ADD COLUMN validacao_expira_em DATETIME NULL AFTER validacao_enviada_em;

ALTER TABLE imoveis 
ADD COLUMN validacao_confirmada_em DATETIME NULL AFTER validacao_expira_em;

ALTER TABLE imoveis 
ADD COLUMN validacao_token VARCHAR(64) NULL AFTER validacao_confirmada_em;

-- ========================================
-- CRIAR ÍNDICES PARA PERFORMANCE
-- ========================================

-- Índice para buscar por status de venda
ALTER TABLE imoveis 
ADD INDEX idx_status_venda (status_venda);

-- Índice para buscar validações expiradas
ALTER TABLE imoveis 
ADD INDEX idx_validacao_expira (validacao_expira_em);

-- Índice composto para buscar imóveis que precisam validação
-- (60 dias, ativos, não vendidos, sem validação)
ALTER TABLE imoveis 
ADD INDEX idx_validacao_pendente (created_at, ativo, status_venda, validacao_enviada_em);

-- Índice para buscar por token de validação
ALTER TABLE imoveis 
ADD INDEX idx_validacao_token (validacao_token);

-- ========================================
-- VERIFICAR RESULTADO
-- ========================================

SELECT 
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'imoveis'
AND COLUMN_NAME IN (
    'status_venda',
    'data_venda',
    'validacao_enviada_em',
    'validacao_expira_em',
    'validacao_confirmada_em',
    'validacao_token'
)
ORDER BY ORDINAL_POSITION;

-- ========================================
-- VERIFICAR ÍNDICES CRIADOS
-- ========================================

SHOW INDEX FROM imoveis 
WHERE Key_name IN (
    'idx_status_venda',
    'idx_validacao_expira',
    'idx_validacao_pendente',
    'idx_validacao_token'
);

-- ========================================
-- ESTATÍSTICAS INICIAIS
-- ========================================

SELECT 
    COUNT(*) as total_imoveis,
    SUM(CASE WHEN ativo = 1 THEN 1 ELSE 0 END) as ativos,
    SUM(CASE WHEN ativo = 0 THEN 1 ELSE 0 END) as inativos,
    SUM(CASE WHEN DATEDIFF(NOW(), created_at) >= 60 AND ativo = 1 THEN 1 ELSE 0 END) as precisam_validacao
FROM imoveis;

-- ========================================
-- NOTAS IMPORTANTES
-- ========================================

/**
 * FLUXO DO SISTEMA:
 * 
 * 1. DETECÇÃO (60 DIAS):
 *    - Cron roda diariamente
 *    - Busca: created_at >= 60 dias, ativo=1, status_venda='disponivel', validacao_enviada_em IS NULL
 *    - Gera token único (SHA-256)
 *    - Envia email com links de confirmação
 *    - Atualiza: validacao_enviada_em, validacao_expira_em (+72h), validacao_token
 * 
 * 2. AÇÃO DO CORRETOR (72 HORAS):
 *    - Opção A: Confirmar disponível → validacao_confirmada_em = NOW()
 *    - Opção B: Marcar vendido → status_venda='vendido', ativo=0
 *    - Opção C: Marcar alugado → status_venda='alugado', ativo=0
 *    - Opção D: Não responder → aguarda expiração
 * 
 * 3. EXPIRAÇÃO (SEM RESPOSTA):
 *    - Cron roda a cada 6 horas
 *    - Busca: validacao_expira_em < NOW(), validacao_confirmada_em IS NULL, ativo=1
 *    - Desativa: ativo=0
 *    - Opcional: Envia email informando desativação
 * 
 * REVALIDAÇÃO:
 * - Imóveis confirmados NÃO são validados novamente
 * - Apenas imóveis que nunca foram validados (validacao_enviada_em IS NULL)
 * 
 * REATIVAÇÃO:
 * - Corretor pode reativar manualmente pelo painel
 * - Ao reativar, campos de validação são limpos
 */
