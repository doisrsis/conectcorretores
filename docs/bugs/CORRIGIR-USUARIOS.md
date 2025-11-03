# 🔧 Corrigir Problema de ID = 0 na Tabela Users

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 02/11/2025

---

## 🐛 Problemas Identificados

1. ✅ Novos usuários recebem `id = 0` (deveria ser auto-increment)
2. ✅ Erro ao cadastrar (mas usuário é criado)
3. ✅ Dashboard mostra dados de outro usuário
4. ✅ Múltiplos usuários com mesmo ID

---

## 🔍 Causa Raiz

O campo `id` da tabela `users` **perdeu o AUTO_INCREMENT**.

**Como isso acontece:**
- Migração mal executada
- Alteração manual da tabela
- Importação de SQL sem AUTO_INCREMENT
- Restauração de backup incorreta

---

## ✅ Solução (Escolha UMA das opções)

### **OPÇÃO 1: Deletar Usuários com ID = 0 (Recomendado se forem testes)**

```sql
-- 1. Fazer backup
CREATE TABLE users_backup AS SELECT * FROM users;

-- 2. Deletar registros inválidos
DELETE FROM users WHERE id = 0;

-- 3. Reativar AUTO_INCREMENT
ALTER TABLE users MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT;

-- 4. Definir próximo ID
ALTER TABLE users AUTO_INCREMENT = 4;

-- 5. Verificar
SHOW CREATE TABLE users;
SELECT id, nome, email FROM users ORDER BY id;
```

---

### **OPÇÃO 2: Manter Usuários com ID = 0 (Atribuir IDs únicos)**

```sql
-- 1. Fazer backup
CREATE TABLE users_backup AS SELECT * FROM users;

-- 2. Atribuir IDs únicos aos registros com ID = 0
SET @new_id = 3; -- Último ID válido

UPDATE users 
SET id = (@new_id := @new_id + 1) 
WHERE id = 0 
ORDER BY created_at ASC;

-- 3. Verificar se ainda há ID = 0
SELECT COUNT(*) FROM users WHERE id = 0;
-- Deve retornar 0

-- 4. Reativar AUTO_INCREMENT
ALTER TABLE users MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT;

-- 5. Definir próximo ID
SELECT @max_id := MAX(id) FROM users;
SET @sql = CONCAT('ALTER TABLE users AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 6. Verificar
SHOW CREATE TABLE users;
SELECT id, nome, email FROM users ORDER BY id;
```

---

## 📋 Passo a Passo (phpMyAdmin)

### **1. Acessar phpMyAdmin**
```
http://localhost/phpmyadmin
```

### **2. Selecionar Banco de Dados**
```
Clique em: conectcorretores (ou nome do seu banco)
```

### **3. Abrir Aba SQL**
```
Clique na aba "SQL" no topo
```

### **4. Executar Script**

**Se quiser DELETAR usuários com ID = 0:**
```sql
-- Copie e cole este script completo:

-- Backup
CREATE TABLE users_backup AS SELECT * FROM users;

-- Deletar inválidos
DELETE FROM users WHERE id = 0;

-- Reativar AUTO_INCREMENT
ALTER TABLE users MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT;

-- Próximo ID
ALTER TABLE users AUTO_INCREMENT = 4;
```

**OU se quiser MANTER usuários com ID = 0:**
```sql
-- Copie e cole este script completo:

-- Backup
CREATE TABLE users_backup AS SELECT * FROM users;

-- Atribuir IDs únicos
SET @new_id = 3;
UPDATE users SET id = (@new_id := @new_id + 1) WHERE id = 0 ORDER BY created_at ASC;

-- Reativar AUTO_INCREMENT
ALTER TABLE users MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT;

-- Próximo ID
SELECT @max_id := MAX(id) FROM users;
SET @sql = CONCAT('ALTER TABLE users AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
```

### **5. Clicar em "Executar"**

### **6. Verificar Resultado**
```sql
-- Ver estrutura da tabela
SHOW CREATE TABLE users;

-- Deve conter: AUTO_INCREMENT=X

-- Ver usuários
SELECT id, nome, email, created_at FROM users ORDER BY id;

-- Não deve ter nenhum ID = 0
```

---

## 🧪 Testar Após Correção

### **1. Cadastrar Novo Usuário**
```
1. Acessar: http://localhost/conectcorretores/register
2. Preencher formulário
3. Cadastrar
4. Deve redirecionar para /planos (sem erro)
```

### **2. Verificar ID no Banco**
```sql
SELECT id, nome, email FROM users ORDER BY id DESC LIMIT 1;
```

**Resultado esperado:**
```
ID deve ser 4, 5, 6... (não 0)
```

### **3. Fazer Login**
```
1. Fazer login com novo usuário
2. Acessar dashboard
3. Deve mostrar dados CORRETOS do usuário logado
```

---

## 🔍 Verificar se Funcionou

### **Estrutura da Tabela:**
```sql
SHOW CREATE TABLE users;
```

**Deve conter:**
```sql
`id` int(11) NOT NULL AUTO_INCREMENT,
...
PRIMARY KEY (`id`),
...
AUTO_INCREMENT=8
```

### **Dados:**
```sql
SELECT id, nome, email FROM users ORDER BY id;
```

**Resultado esperado:**
```
+----+---------------------------+--------------------------------+
| id | nome                      | email                          |
+----+---------------------------+--------------------------------+
|  1 | Administrador             | admin@conectcorretores.com     |
|  2 | Rafael de Andrade Dias    | rafaeldiaswebdev@gmail.com     |
|  3 | Doisr Sistemas            | doisr.sistemas@gmail.com       |
|  4 | Rafael de Andrade Dias    | rafaeldiastecinfo@gmail.com    |
|  5 | Rodrigo Barbosa           | rodrigo@gmail.com              |
|  6 | Rodrigo Barbosa           | rodrigobarbosa@gmail.com       |
|  7 | Rodrigo Dias              | rodrigobarbosa2@gmail.com      |
+----+---------------------------+--------------------------------+
```

**SEM nenhum ID = 0** ✅

---

## ⚠️ IMPORTANTE: Após Correção

### **Todos os usuários precisam fazer login novamente**

**Por quê?**
- Os IDs mudaram (de 0 para 4, 5, 6...)
- A sessão ainda tem `user_id = 0`
- Ao acessar dashboard, busca usuário com ID errado

**Solução:**
1. Fazer logout de todos
2. Limpar sessões antigas (opcional)
3. Fazer login novamente

---

## 🗑️ Limpar Sessões Antigas (Opcional)

### **Opção 1: Deletar arquivos de sessão**
```
1. Ir para: c:\xampp\tmp\
2. Deletar todos os arquivos sess_*
```

### **Opção 2: Via código (adicionar temporariamente)**
```php
// Em: application/controllers/Auth.php
// Método login(), antes de criar sessão:

// Destruir sessão antiga
$this->session->sess_destroy();
session_start();
```

---

## 📊 Resultado Final

### **Antes:**
```
❌ ID = 0 para todos os novos usuários
❌ Erro ao cadastrar
❌ Dashboard mostra dados errados
❌ Múltiplos usuários com mesmo ID
```

### **Depois:**
```
✅ ID auto-increment funcionando (4, 5, 6...)
✅ Cadastro sem erros
✅ Dashboard mostra dados corretos
✅ Cada usuário com ID único
```

---

## 🆘 Troubleshooting

### **Problema: Ainda recebo ID = 0**

**Verificar:**
```sql
SHOW CREATE TABLE users;
```

**Procurar por:**
```
AUTO_INCREMENT
```

**Se não tiver, executar:**
```sql
ALTER TABLE users MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE users AUTO_INCREMENT = 8;
```

---

### **Problema: Erro ao executar UPDATE**

**Causa:** Modo SAFE UPDATE ativado

**Solução:**
```sql
SET SQL_SAFE_UPDATES = 0;
-- Executar UPDATE
SET SQL_SAFE_UPDATES = 1;
```

---

### **Problema: Dashboard ainda mostra dados errados**

**Causa:** Sessão antiga com user_id = 0

**Solução:**
```
1. Fazer logout
2. Limpar cache do navegador (Ctrl + Shift + Delete)
3. Fechar navegador
4. Abrir novamente
5. Fazer login
```

---

## 📝 Backup de Segurança

Antes de executar qualquer script, faça backup:

```sql
-- Backup completo da tabela
CREATE TABLE users_backup_20251102 AS SELECT * FROM users;

-- Verificar backup
SELECT COUNT(*) FROM users_backup_20251102;
```

**Para restaurar (se algo der errado):**
```sql
-- Deletar tabela atual
DROP TABLE users;

-- Renomear backup
RENAME TABLE users_backup_20251102 TO users;
```

---

## ✅ Checklist

- [ ] Backup criado
- [ ] Script executado
- [ ] AUTO_INCREMENT verificado
- [ ] Nenhum ID = 0 no banco
- [ ] Novo cadastro testado
- [ ] Login testado
- [ ] Dashboard mostrando dados corretos
- [ ] Sessões antigas limpas

---

**Problema resolvido! 🎉**

Para suporte: Rafael Dias - doisr.com.br
