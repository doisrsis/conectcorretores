# 📁 Organização do Projeto

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 02/11/2025

---

## 🎯 Estrutura de Pastas

```
conectcorretores/
│
├── 📚 docs/                          # Documentação
│   ├── desenvolvimento/              # Guias de desenvolvimento
│   │   ├── COMO-TESTAR-SINCRONIZACAO.md
│   │   ├── STRIPE_CUSTOMER_ID.md
│   │   └── GIT-WORKFLOW.md
│   │
│   ├── bugs/                         # Bugs resolvidos
│   │   ├── BUG-DATA-STRIPE-RESOLVIDO.md
│   │   └── CORRIGIR-USUARIOS.md
│   │
│   ├── README.md                     # Índice da documentação
│   └── ORGANIZACAO-PROJETO.md        # Este arquivo
│
├── 🗄️ database/                      # Scripts SQL
│   ├── fixes/                        # Correções
│   │   ├── fix_users_table.sql
│   │   └── fix_users_keep_data.sql
│   │
│   ├── migrations/                   # Migrações (futuro)
│   ├── seeds/                        # Dados iniciais (futuro)
│   └── backups/                      # Backups (gitignored)
│
├── 🔧 scripts/                       # Scripts auxiliares
│   └── windows/                      # Scripts Windows (.bat)
│
├── 💻 application/                   # Código da aplicação
│   ├── controllers/
│   ├── models/
│   ├── views/
│   ├── libraries/
│   ├── config/
│   └── ...
│
├── 🎨 assets/                        # Assets públicos
│   ├── css/
│   ├── js/
│   └── images/
│
├── 📦 vendor/                        # Dependências (gitignored)
├── 📤 uploads/                       # Uploads de usuários (gitignored)
│
├── .gitignore                        # Arquivos ignorados pelo Git
├── README.md                         # README principal
└── composer.json                     # Dependências PHP
```

---

## 📝 Convenções de Nomenclatura

### **Documentação (.md)**

```
NOME-DO-DOCUMENTO.md
```

**Exemplos:**
- `COMO-TESTAR-SINCRONIZACAO.md`
- `BUG-DATA-STRIPE-RESOLVIDO.md`
- `STRIPE_CUSTOMER_ID.md`

**Regras:**
- ✅ MAIÚSCULAS
- ✅ Hífens para separar palavras
- ✅ Underscores para nomes técnicos (ex: STRIPE_CUSTOMER_ID)
- ✅ Descritivo e claro

---

### **Scripts SQL**

```
tipo_descricao.sql
```

**Exemplos:**
- `fix_users_table.sql`
- `migration_20251102_add_stripe_fields.sql`
- `seed_plans.sql`

**Regras:**
- ✅ minúsculas
- ✅ Underscores para separar palavras
- ✅ Prefixo indicando tipo (fix, migration, seed)
- ✅ Data para migrações (YYYYMMDD)

---

### **Scripts (.bat, .sh)**

```
nome-descritivo.bat
```

**Exemplos:**
- `start-server.bat`
- `backup-database.bat`
- `deploy-production.sh`

**Regras:**
- ✅ minúsculas
- ✅ Hífens para separar palavras
- ✅ Extensão apropriada (.bat para Windows, .sh para Linux/Mac)

---

## 📂 Onde Criar Cada Tipo de Arquivo

### **Documentação de Desenvolvimento**
```
docs/desenvolvimento/
```

**Quando usar:**
- Guias de como fazer algo
- Tutoriais
- Explicações técnicas
- Workflows

**Exemplos:**
- Como testar funcionalidades
- Como usar bibliotecas
- Fluxos de trabalho

---

### **Documentação de Bugs**
```
docs/bugs/
```

**Quando usar:**
- Bug foi identificado e resolvido
- Documentar solução para referência futura
- Explicar causa raiz

**Exemplos:**
- Problemas de sincronização
- Erros de banco de dados
- Bugs de lógica

---

### **Scripts de Correção SQL**
```
database/fixes/
```

**Quando usar:**
- Corrigir dados existentes
- Ajustar estrutura de tabelas
- Resolver inconsistências

**Exemplos:**
- Corrigir AUTO_INCREMENT
- Atualizar dados em lote
- Limpar registros duplicados

---

### **Migrações SQL**
```
database/migrations/
```

**Quando usar:**
- Criar novas tabelas
- Adicionar colunas
- Modificar estrutura (versionado)

**Formato:**
```
migration_YYYYMMDD_descricao.sql
```

**Exemplo:**
```sql
-- migration_20251102_add_stripe_customer_id.sql

ALTER TABLE users 
ADD COLUMN stripe_customer_id VARCHAR(255) DEFAULT NULL 
COMMENT 'ID do cliente no Stripe';
```

---

### **Seeds SQL**
```
database/seeds/
```

**Quando usar:**
- Dados iniciais do sistema
- Dados de teste
- Configurações padrão

**Exemplos:**
- Planos iniciais
- Usuário admin
- Configurações do sistema

---

### **Scripts Auxiliares**
```
scripts/windows/    (para .bat)
scripts/linux/      (para .sh)
```

**Quando usar:**
- Automatizar tarefas
- Comandos frequentes
- Deploy
- Backup

---

## 🎯 Boas Práticas

### **1. Sempre Documentar**

Ao criar algo novo:
```
1. Criar arquivo na pasta apropriada
2. Seguir convenção de nomenclatura
3. Adicionar ao índice (docs/README.md)
4. Incluir autor e data
```

---

### **2. Manter Organizado**

```
❌ EVITAR:
conectcorretores/
├── arquivo1.md
├── script.sql
├── fix.sql
├── teste.bat
└── doc.md

✅ FAZER:
conectcorretores/
├── docs/
│   ├── desenvolvimento/arquivo1.md
│   └── bugs/doc.md
├── database/
│   └── fixes/
│       ├── script.sql
│       └── fix.sql
└── scripts/
    └── windows/teste.bat
```

---

### **3. Usar .gitignore Corretamente**

**Manter no Git:**
- ✅ Documentação (`docs/`)
- ✅ Scripts SQL (`database/`)
- ✅ Scripts auxiliares (`scripts/`)

**Ignorar:**
- ❌ Backups temporários
- ❌ Logs
- ❌ Configurações locais
- ❌ Dependências

---

### **4. README em Cada Pasta**

Pastas principais devem ter README:

```
docs/README.md           # Índice da documentação
database/README.md       # Como usar scripts SQL
scripts/README.md        # Como usar scripts
```

---

## 📋 Checklist ao Criar Arquivo

- [ ] Escolhi a pasta correta?
- [ ] Segui a convenção de nomenclatura?
- [ ] Adicionei autor e data?
- [ ] Atualizei o índice (docs/README.md)?
- [ ] Arquivo está no .gitignore (se necessário)?

---

## 🔄 Migração de Arquivos Existentes

### **Arquivos Movidos:**

```
✅ COMO-TESTAR-SINCRONIZACAO.md → docs/desenvolvimento/
✅ STRIPE_CUSTOMER_ID.md → docs/desenvolvimento/
✅ GIT-WORKFLOW.md → docs/desenvolvimento/
✅ BUG-DATA-STRIPE-RESOLVIDO.md → docs/bugs/
✅ CORRIGIR-USUARIOS.md → docs/bugs/
✅ fix_users_table.sql → database/fixes/
✅ fix_users_keep_data.sql → database/fixes/
```

### **Arquivos na Raiz (Manter):**

```
✅ README.md              # README principal do projeto
✅ .gitignore            # Configuração Git
✅ composer.json         # Dependências
✅ index.php             # Entry point
```

---

## 🆕 Criando Novos Arquivos

### **Exemplo: Nova Documentação**

```bash
# Criar em: docs/desenvolvimento/
# Nome: COMO-USAR-STRIPE.md
```

```markdown
# 🎯 Como Usar Stripe

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 02/11/2025

...
```

**Depois:**
1. Adicionar ao `docs/README.md`
2. Commit no Git

---

### **Exemplo: Novo Script SQL**

```bash
# Criar em: database/fixes/
# Nome: fix_subscriptions_dates.sql
```

```sql
-- ============================================
-- FIX: Corrigir datas de assinaturas
-- Autor: Rafael Dias - doisr.com.br
-- Data: 02/11/2025
-- ============================================

UPDATE subscriptions 
SET data_fim = DATE_ADD(data_inicio, INTERVAL 30 DAY)
WHERE data_fim < data_inicio;
```

---

### **Exemplo: Novo Script .bat**

```bash
# Criar em: scripts/windows/
# Nome: backup-database.bat
```

```batch
@echo off
echo Fazendo backup do banco de dados...
mysqldump -u root conectcorretores > backup_%date:~-4,4%%date:~-7,2%%date:~-10,2%.sql
echo Backup concluído!
pause
```

---

## 📊 Estrutura Atual vs Futura

### **Antes (Desorganizado):**
```
conectcorretores/
├── arquivo1.md
├── arquivo2.md
├── script.sql
├── fix.sql
├── teste.bat
├── application/
└── assets/
```

### **Depois (Organizado):**
```
conectcorretores/
├── docs/
│   ├── desenvolvimento/
│   └── bugs/
├── database/
│   ├── fixes/
│   ├── migrations/
│   └── seeds/
├── scripts/
│   └── windows/
├── application/
└── assets/
```

---

## ✅ Benefícios

1. ✅ **Fácil de encontrar** - Tudo no lugar certo
2. ✅ **Escalável** - Cresce de forma organizada
3. ✅ **Profissional** - Padrão de mercado
4. ✅ **Manutenível** - Fácil de manter
5. ✅ **Colaborativo** - Outros desenvolvedores entendem
6. ✅ **Git-friendly** - Fácil de versionar

---

## 🎓 Referências

- [CodeIgniter Best Practices](https://codeigniter.com/user_guide/)
- [Git Best Practices](https://git-scm.com/book/en/v2)
- [Project Structure Best Practices](https://github.com/kriasoft/Folder-Structure-Conventions)

---

**Projeto organizado! 🎉**

Para suporte: Rafael Dias - doisr.com.br
