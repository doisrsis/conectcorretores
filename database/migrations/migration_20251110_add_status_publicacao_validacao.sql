/**
 * Migration: Adicionar novos status de publicação para validação de imóveis
 * 
 * Autor: Rafael Dias - doisr.com.br
 * Data: 10/11/2025
 * 
 * Descrição:
 * Adiciona novos valores ao ENUM status_publicacao para suportar
 * o sistema de validação automática de imóveis (60 dias)
 */

-- ========================================
-- ADICIONAR NOVOS STATUS AO ENUM
-- ========================================

-- Modificar ENUM para incluir novos status de validação
ALTER TABLE imoveis 
MODIFY COLUMN status_publicacao ENUM(
    'ativo',
    'inativo_sem_plano',
    'inativo_plano_vencido',
    'inativo_manual',
    'inativo_por_tempo',
    'inativo_vendido',
    'inativo_alugado'
) NOT NULL DEFAULT 'ativo';

-- ========================================
-- VERIFICAR RESULTADO
-- ========================================

SELECT 
    COLUMN_NAME,
    COLUMN_TYPE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'imoveis'
AND COLUMN_NAME = 'status_publicacao';

-- ========================================
-- NOTAS
-- ========================================

/**
 * NOVOS STATUS ADICIONADOS:
 * 
 * - inativo_por_tempo: Imóvel desativado automaticamente após 60 dias sem validação
 * - inativo_vendido: Imóvel marcado como vendido pelo corretor
 * - inativo_alugado: Imóvel marcado como alugado pelo corretor
 * 
 * FLUXO DE USO:
 * 
 * 1. Sistema envia email após 60 dias (status permanece 'ativo')
 * 2. Corretor não responde em 72h → status = 'inativo_por_tempo'
 * 3. Corretor marca como vendido → status = 'inativo_vendido'
 * 4. Corretor marca como alugado → status = 'inativo_alugado'
 */
