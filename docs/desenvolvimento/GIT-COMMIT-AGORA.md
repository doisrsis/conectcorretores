# 🚀 Comandos Git para Commit Atual

**Data:** 19/10/2025  
**Feature:** Sistema de Upgrade/Downgrade de Planos

---

## 📋 Passo a Passo

### **1. Verificar Status**
```bash
git status
```

**Você verá:**
```
modified:   application/views/planos/index.php
modified:   application/views/dashboard/index.php
modified:   application/views/dashboard/perfil.php
modified:   application/controllers/Planos.php
modified:   application/libraries/Stripe_lib.php
modified:   application/models/Subscription_model.php
modified:   application/models/Imovel_model.php
new file:   MELHORIAS-UX-PLANOS.md
new file:   UPGRADE-DOWNGRADE-IMPLEMENTADO.md
new file:   COMMIT-UPGRADE-DOWNGRADE.md
new file:   GIT-COMMIT-AGORA.md
```

---

### **2. Adicionar Arquivos ao Stage**

**Opção A - Adicionar todos:**
```bash
git add .
```

**Opção B - Adicionar seletivamente:**
```bash
# Views
git add application/views/planos/index.php
git add application/views/dashboard/index.php
git add application/views/dashboard/perfil.php

# Controllers
git add application/controllers/Planos.php

# Libraries
git add application/libraries/Stripe_lib.php

# Models
git add application/models/Subscription_model.php
git add application/models/Imovel_model.php

# Documentação
git add MELHORIAS-UX-PLANOS.md
git add UPGRADE-DOWNGRADE-IMPLEMENTADO.md
git add COMMIT-UPGRADE-DOWNGRADE.md
git add GIT-COMMIT-AGORA.md
```

---

### **3. Verificar o que será commitado**
```bash
git status
```

---

### **4. Fazer o Commit**

**Mensagem Curta:**
```bash
git commit -m "feat: Implementar sistema de upgrade/downgrade de planos"
```

**Mensagem Completa (Recomendado):**
```bash
git commit -m "feat: Implementar sistema de upgrade/downgrade de planos

- Adicionar botões inteligentes de upgrade/downgrade na página de planos
- Implementar métodos upgrade() e downgrade() no controller Planos
- Adicionar método update_subscription() na biblioteca Stripe
- Implementar gestão automática de imóveis no downgrade
- Corrigir bug de propriedades não definidas no Subscription_model
- Adicionar widgets de plano no dashboard e perfil
- Criar documentação completa do sistema

Funcionalidades:
- Upgrade: Troca imediata com cobrança proporcional
- Downgrade: Troca imediata com crédito proporcional
- Inativação automática de imóveis se exceder limite
- Integração completa com Stripe API

Arquivos modificados:
- application/views/planos/index.php
- application/views/dashboard/index.php
- application/views/dashboard/perfil.php
- application/controllers/Planos.php
- application/libraries/Stripe_lib.php
- application/models/Subscription_model.php
- application/models/Imovel_model.php

Documentação:
- MELHORIAS-UX-PLANOS.md
- UPGRADE-DOWNGRADE-IMPLEMENTADO.md
- COMMIT-UPGRADE-DOWNGRADE.md"
```

---

### **5. Verificar o Commit**
```bash
git log -1
```

---

### **6. Fazer Push para o Repositório**

**Branch atual:**
```bash
git push origin main
```

**Ou se estiver em outra branch:**
```bash
git push origin nome-da-branch
```

---

## 🎯 Resumo Rápido (Copy & Paste)

```bash
# 1. Ver status
git status

# 2. Adicionar tudo
git add .

# 3. Commit
git commit -m "feat: Implementar sistema de upgrade/downgrade de planos

- Adicionar botões inteligentes de upgrade/downgrade
- Implementar métodos upgrade() e downgrade() no controller
- Adicionar método update_subscription() no Stripe_lib
- Implementar gestão automática de imóveis no downgrade
- Corrigir bug no Subscription_model
- Adicionar widgets de plano no dashboard e perfil
- Criar documentação completa"

# 4. Push
git push origin main
```

---

## 📊 Estatísticas do Commit

**Arquivos Modificados:** 7  
**Arquivos Novos:** 4  
**Total:** 11 arquivos

**Linhas Adicionadas:** ~800+  
**Linhas Removidas:** ~50

**Funcionalidades:** 3 principais
- Sistema de upgrade
- Sistema de downgrade
- Widgets de plano

---

## ✅ Checklist Antes do Push

- [ ] Código testado localmente?
- [ ] Sem erros de sintaxe?
- [ ] Documentação criada?
- [ ] Comentários adicionados?
- [ ] Arquivos desnecessários excluídos?
- [ ] .gitignore atualizado (se necessário)?

---

## 🔄 Se Precisar Desfazer

**Desfazer último commit (mantém alterações):**
```bash
git reset --soft HEAD~1
```

**Desfazer add (unstage):**
```bash
git reset HEAD arquivo.php
```

**Descartar alterações de um arquivo:**
```bash
git checkout -- arquivo.php
```

---

## 📝 Convenções de Commit

### **Tipos:**
- `feat:` Nova funcionalidade
- `fix:` Correção de bug
- `docs:` Documentação
- `style:` Formatação (sem mudança de código)
- `refactor:` Refatoração
- `test:` Testes
- `chore:` Tarefas de manutenção

### **Formato:**
```
tipo: Descrição curta (máx 50 caracteres)

Descrição detalhada (opcional)
- Item 1
- Item 2

Closes #123
```

---

## 🎯 Próximo Commit (Futuro)

Após implementar sincronização:
```bash
git commit -m "feat: Implementar sincronização de assinaturas com Stripe

- Adicionar sincronização no login
- Melhorar webhook com validação de assinatura
- Criar método _sync_subscription_status()
- Implementar cron job de sincronização diária
- Adicionar logs de sincronização"
```

---

**Pronto para executar! 🚀**

Execute os comandos acima na ordem e está feito!
