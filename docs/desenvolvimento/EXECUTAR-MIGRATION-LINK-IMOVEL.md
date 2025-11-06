# 🗄️ Executar Migration - Link do Imóvel

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 06/11/2025  
**Prioridade:** 🟡 ALTA - Necessário para Produção

---

## 📋 O Que Esta Migration Faz

Esta migration implementa as melhorias no cadastro de imóveis:

**Adiciona:**
- ✅ Coluna `link_imovel` (URL opcional para página do imóvel)

**Remove:**
- ❌ Coluna `link` (Link do Site)
- ❌ Coluna `telefone` (Telefone)
- ❌ Coluna `whatsapp` (WhatsApp)

**Motivo:** Essas informações devem vir do cadastro do corretor, não do imóvel.

---

## ⚠️ Antes de Executar

### **1. Fazer Backup do Banco de Dados**

**Via phpMyAdmin:**
```
1. Acessar phpMyAdmin
2. Selecionar banco 'conectcorretores'
3. Clicar em 'Exportar'
4. Método: Rápido
5. Formato: SQL
6. Clicar em 'Executar'
7. Salvar arquivo: backup_conectcorretores_06112025.sql
```

**Via Linha de Comando:**
```bash
mysqldump -u root -p conectcorretores > backup_conectcorretores_06112025.sql
```

### **2. Verificar Dados Existentes**

**Verificar se há imóveis com dados nos campos que serão removidos:**
```sql
-- Verificar quantos imóveis têm dados nos campos
SELECT 
    COUNT(*) as total_imoveis,
    COUNT(link) as com_link,
    COUNT(telefone) as com_telefone,
    COUNT(whatsapp) as com_whatsapp
FROM imoveis;
```

**Se houver dados importantes:**
- Exportar para planilha antes de executar
- Ou migrar para tabela de backup

---

## 🚀 Executar Migration

### **Opção 1: Via phpMyAdmin (Recomendado)**

1. Acessar phpMyAdmin
2. Selecionar banco `conectcorretores`
3. Clicar na aba **SQL**
4. Abrir arquivo: `database/migrations/migration_20251103_add_link_imovel.sql`
5. Copiar todo o conteúdo
6. Colar na área de texto do phpMyAdmin
7. Clicar em **Executar**

---

### **Opção 2: Via MySQL Command Line**

```bash
# Navegar até a pasta do projeto
cd c:\xampp\htdocs\conectcorretores

# Executar migration
mysql -u root -p conectcorretores < database/migrations/migration_20251103_add_link_imovel.sql
```

---

### **Opção 3: Via Script Batch (Windows)**

Vou criar um script para facilitar:

**Arquivo:** `scripts/windows/executar-migration-link-imovel.bat`

```batch
@echo off
echo ========================================
echo Migration: Adicionar Link do Imovel
echo ========================================
echo.

echo ATENCAO: Certifique-se de ter feito backup do banco!
echo.
set /p confirma=Deseja continuar? (S/N): 

if /i "%confirma%" NEQ "S" (
    echo.
    echo Migration cancelada.
    pause
    exit
)

echo.
echo Executando migration...
echo.

mysql -u root -p conectcorretores < database\migrations\migration_20251103_add_link_imovel.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo Migration executada com sucesso!
    echo ========================================
) else (
    echo.
    echo ========================================
    echo ERRO ao executar migration!
    echo ========================================
)

echo.
pause
```

---

## ✅ Verificar se Foi Executada Corretamente

### **1. Verificar Estrutura da Tabela**

```sql
DESCRIBE imoveis;
```

**Deve mostrar:**
- ✅ Coluna `link_imovel` existe
- ❌ Coluna `link` NÃO existe
- ❌ Coluna `telefone` NÃO existe
- ❌ Coluna `whatsapp` NÃO existe

---

### **2. Verificar Dados**

```sql
-- Ver primeiros registros
SELECT id, titulo, link_imovel 
FROM imoveis 
LIMIT 5;
```

---

### **3. Testar no Sistema**

1. Acessar `/imoveis/novo`
2. Verificar que campos removidos não aparecem
3. Verificar que campo "Link do Imóvel" aparece
4. Cadastrar imóvel de teste
5. Verificar que salvou corretamente

---

## 🔄 Reverter Migration (Se Necessário)

Se algo der errado, você pode reverter:

**Arquivo:** `database/migrations/rollback_20251103_add_link_imovel.sql`

```sql
/**
 * Rollback: Reverter migration link_imovel
 * 
 * Autor: Rafael Dias - doisr.com.br
 * Data: 06/11/2025
 */

-- Remover coluna link_imovel
ALTER TABLE imoveis 
DROP COLUMN IF EXISTS link_imovel;

-- Adicionar colunas antigas de volta
ALTER TABLE imoveis 
ADD COLUMN link VARCHAR(500) NULL AFTER descricao,
ADD COLUMN telefone VARCHAR(20) NULL AFTER link,
ADD COLUMN whatsapp VARCHAR(20) NULL AFTER telefone;

-- Verificar resultado
SELECT 
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'imoveis'
AND COLUMN_NAME IN ('link_imovel', 'link', 'telefone', 'whatsapp')
ORDER BY ORDINAL_POSITION;
```

---

## 📊 Conteúdo da Migration

```sql
/**
 * Migration: Adicionar campo link_imovel na tabela imoveis
 * 
 * Autor: Rafael Dias - doisr.com.br
 * Data: 03/11/2025
 * 
 * Descrição:
 * - Adiciona coluna link_imovel (URL opcional)
 * - Remove colunas de contato (link, telefone, whatsapp)
 * - Contatos serão pegos do cadastro do corretor
 */

-- Adicionar coluna link_imovel
ALTER TABLE imoveis 
ADD COLUMN link_imovel VARCHAR(500) NULL AFTER descricao
COMMENT 'Link para página do imóvel no site do corretor';

-- Remover colunas de contato (agora vêm do corretor)
ALTER TABLE imoveis 
DROP COLUMN IF EXISTS link,
DROP COLUMN IF EXISTS telefone,
DROP COLUMN IF EXISTS whatsapp;

-- Verificar resultado
SELECT 
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'imoveis'
AND COLUMN_NAME IN ('link_imovel', 'link', 'telefone', 'whatsapp')
ORDER BY ORDINAL_POSITION;
```

---

## 🚨 Troubleshooting

### **Erro: "Table 'imoveis' doesn't exist"**
**Causa:** Banco de dados incorreto  
**Solução:** Verificar se está no banco correto

### **Erro: "Can't DROP 'link'; check that column/key exists"**
**Causa:** Coluna já foi removida anteriormente  
**Solução:** Ignorar erro, continuar

### **Erro: "Duplicate column name 'link_imovel'"**
**Causa:** Migration já foi executada  
**Solução:** Verificar se precisa executar novamente

---

## ⏱️ Tempo Estimado

- **Backup:** 2-3 minutos
- **Verificação:** 2 minutos
- **Execução:** 1 minuto
- **Testes:** 5 minutos
- **Total:** 10-15 minutos

---

## ✅ Checklist

- [ ] Backup do banco realizado
- [ ] Dados existentes verificados
- [ ] Migration executada
- [ ] Estrutura da tabela verificada
- [ ] Sistema testado
- [ ] Formulário de cadastro funcionando
- [ ] Dados salvando corretamente

---

**Migration necessária para as melhorias do cadastro de imóveis! 🗄️**

Para suporte: Rafael Dias - doisr.com.br
