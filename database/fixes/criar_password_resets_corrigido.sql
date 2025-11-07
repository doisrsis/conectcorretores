-- ========================================
-- Criar Tabela password_resets (CORRIGIDO)
-- Autor: Rafael Dias - doisr.com.br
-- Data: 07/11/2025
-- ========================================

-- ========================================
-- CRIAR TABELA password_resets
-- ========================================

CREATE TABLE `password_resets` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `used_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `token` (`token`),
  KEY `expires_at` (`expires_at`),
  KEY `used` (`used`),
  KEY `idx_token_valid` (`token`, `used`, `expires_at`),
  CONSTRAINT `fk_password_resets_user` 
    FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) 
    ON DELETE CASCADE 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tokens de recuperação de senha';

-- ========================================
-- VERIFICAÇÃO
-- ========================================

-- Ver estrutura
DESCRIBE `password_resets`;

-- Ver índices
SHOW INDEX FROM `password_resets`;

-- ========================================
-- RESULTADO ESPERADO
-- ========================================

/*
DESCRIBE password_resets:

Field         | Type              | Null | Key | Default           | Extra
------------- | ----------------- | ---- | --- | ----------------- | ----------------
id            | int(11) unsigned  | NO   | PRI | NULL              | auto_increment
user_id       | int(11) unsigned  | NO   | MUL | NULL              |
token         | varchar(255)      | NO   | MUL | NULL              |
created_at    | timestamp         | YES  |     | CURRENT_TIMESTAMP |
expires_at    | timestamp         | YES  | MUL | NULL              |
used          | tinyint(1)        | NO   | MUL | 0                 |
used_at       | timestamp         | YES  |     | NULL              |

SHOW INDEX:

Table            | Key_name           | Column_name | Index_type
---------------- | ------------------ | ----------- | ----------
password_resets  | PRIMARY            | id          | BTREE
password_resets  | user_id            | user_id     | BTREE
password_resets  | token              | token       | BTREE
password_resets  | expires_at         | expires_at  | BTREE
password_resets  | used               | used        | BTREE
password_resets  | idx_token_valid    | token       | BTREE
password_resets  | idx_token_valid    | used        | BTREE
password_resets  | idx_token_valid    | expires_at  | BTREE
password_resets  | fk_password_resets_user | user_id | BTREE
*/

-- ========================================
-- NOTAS IMPORTANTES
-- ========================================

/*
CORREÇÕES APLICADAS:

1. ✅ created_at: timestamp NULL DEFAULT CURRENT_TIMESTAMP
   - Permite NULL
   - Valor padrão: data/hora atual

2. ✅ expires_at: timestamp NULL DEFAULT NULL
   - Permite NULL
   - Sem valor padrão (será definido no código)

3. ✅ used_at: timestamp NULL DEFAULT NULL
   - Permite NULL
   - Sem valor padrão (será definido quando usado)

4. ✅ Todos os índices criados na mesma instrução
   - Mais eficiente
   - Evita erros de duplicação

5. ✅ Foreign key com CASCADE
   - Se usuário for deletado, tokens são deletados
   - Se usuário for atualizado, tokens são atualizados

SEGURANÇA:

- Token: 64 caracteres hexadecimais (gerado no código)
- Expiração: 24 horas (definido no código)
- Uso único: flag 'used'
- Índice composto para busca eficiente de tokens válidos
*/
