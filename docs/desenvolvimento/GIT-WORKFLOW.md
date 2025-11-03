# 🔄 Fluxo de Trabalho Git - ConectCorretores

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 19/10/2025

---

## 📋 Repositórios Configurados

Temos **2 repositórios remotos**:

- **`origin`** → GitHub (backup e versionamento principal)
  - URL: `https://github.com/doisrsis/conectcorretores.git`
  
- **`cpanel`** → Servidor de Desenvolvimento Online
  - URL: `ssh://dois8950@doisr.com.br:1157/home/dois8950/conectcorretores.doisr.com.br`

---

## 🚀 Fluxo de Trabalho Diário

### **Opção 1: Desenvolvimento Local → GitHub → cPanel (Recomendado)**

```bash
# 1. Fazer alterações no código local (localhost)
# 2. Testar localmente em http://localhost/conectcorretores/

# 3. Adicionar arquivos modificados
git add .

# 4. Fazer commit com mensagem descritiva
git commit -m "feat: descrição da funcionalidade"

# 5. Enviar para GitHub (backup principal)
git push origin main

# 6. Enviar para cPanel (ambiente de dev online)
git push cpanel main

# 7. Testar no ambiente online
# Acesse: https://conectcorretores.doisr.com.br/
```

---

### **Opção 2: Desenvolvimento Direto no cPanel**

```bash
# 1. Conectar via SSH ao cPanel
ssh -p 1157 dois8950@doisr.com.br

# 2. Navegar até o projeto
cd conectcorretores.doisr.com.br

# 3. Fazer alterações no código

# 4. Adicionar e commitar
git add .
git commit -m "feat: alteração feita no cPanel"

# 5. Enviar para GitHub
git push origin main

# 6. No localhost, baixar as alterações
git pull origin main
```

---

## 📝 Comandos Git Essenciais

### **Ver Status**

```bash
# Ver arquivos modificados
git status

# Ver diferenças no código
git diff

# Ver diferenças de um arquivo específico
git diff arquivo.php
```

### **Adicionar Arquivos**

```bash
# Adicionar todos os arquivos modificados
git add .

# Adicionar arquivo específico
git add application/controllers/Imoveis.php

# Adicionar pasta específica
git add application/views/
```

### **Fazer Commit**

```bash
# Commit com mensagem
git commit -m "feat: adicionar integração Stripe"

# Commit com mensagem detalhada
git commit -m "feat: integração Stripe

- Adicionar SDK Stripe
- Criar controller de pagamentos
- Implementar webhook"

# Adicionar e commitar em um comando
git commit -am "fix: corrigir bug no login"
```

### **Enviar Alterações (Push)**

```bash
# Enviar para GitHub
git push origin main

# Enviar para cPanel
git push cpanel main

# Enviar para ambos de uma vez
git push origin main && git push cpanel main
```

### **Baixar Alterações (Pull)**

```bash
# Baixar do GitHub
git pull origin main

# Baixar do cPanel
git pull cpanel main
```

### **Ver Histórico**

```bash
# Ver commits recentes
git log --oneline

# Ver últimos 5 commits
git log --oneline -5

# Ver commits com detalhes
git log

# Ver commits de um arquivo específico
git log -- application/controllers/Imoveis.php
```

---

## 🌿 Trabalhando com Branches

### **Criar Nova Branch para Feature**

```bash
# Criar e mudar para nova branch
git checkout -b feature/nova-funcionalidade

# Fazer alterações e commitar
git add .
git commit -m "feat: implementar nova funcionalidade"

# Enviar branch para GitHub
git push origin feature/nova-funcionalidade

# Voltar para main
git checkout main

# Fazer merge da feature
git merge feature/nova-funcionalidade

# Deletar branch local
git branch -d feature/nova-funcionalidade

# Deletar branch remota
git push origin --delete feature/nova-funcionalidade
```

### **Listar Branches**

```bash
# Ver branches locais
git branch

# Ver todas as branches (locais e remotas)
git branch -a

# Ver branch atual
git branch --show-current
```

---

## 🔧 Comandos de Manutenção

### **Desfazer Alterações**

```bash
# Desfazer alterações não commitadas em um arquivo
git checkout -- arquivo.php

# Desfazer todas as alterações não commitadas
git checkout -- .

# Desfazer último commit (mantém alterações)
git reset --soft HEAD~1

# Desfazer último commit (descarta alterações)
git reset --hard HEAD~1
```

### **Atualizar Repositório**

```bash
# Baixar alterações sem fazer merge
git fetch origin

# Ver diferenças entre local e remoto
git diff origin/main

# Fazer merge das alterações
git merge origin/main
```

### **Limpar Arquivos Não Rastreados**

```bash
# Ver arquivos que serão removidos
git clean -n

# Remover arquivos não rastreados
git clean -f

# Remover arquivos e diretórios não rastreados
git clean -fd
```

---

## 🔐 Configurar Autenticação SSH

### **1. Gerar Chave SSH**

```bash
# Gerar chave SSH (se não tiver)
ssh-keygen -t rsa -b 4096 -C "seu-email@gmail.com"

# Pressione Enter para aceitar o local padrão
# Pressione Enter para senha vazia (ou defina uma senha)
```

### **2. Copiar Chave Pública**

```bash
# Windows (PowerShell)
cat ~/.ssh/id_rsa.pub | clip

# Ou abrir manualmente
notepad C:\Users\SeuUsuario\.ssh\id_rsa.pub
```

### **3. Adicionar no cPanel**

1. Acesse **cPanel → SSH Access → Manage SSH Keys**
2. Clique em **Import Key**
3. Cole a chave pública
4. Clique em **Import**
5. Clique em **Manage** → **Authorize**

### **4. Testar Conexão**

```bash
# Testar conexão SSH
ssh -p 1157 dois8950@doisr.com.br

# Se conectar sem pedir senha, está OK!
```

---

## 📦 Gerenciar Remotes

### **Ver Remotes Configurados**

```bash
git remote -v
```

### **Adicionar Novo Remote**

```bash
git remote add nome-remote url-do-repositorio
```

### **Remover Remote**

```bash
git remote remove nome-remote
```

### **Renomear Remote**

```bash
git remote rename nome-antigo nome-novo
```

### **Alterar URL do Remote**

```bash
git remote set-url origin nova-url
```

---

## ⚙️ Configurações Úteis

### **Configurar Usuário**

```bash
# Configurar nome
git config --global user.name "Rafael Dias"

# Configurar email
git config --global user.email "seu-email@gmail.com"

# Ver configurações
git config --list
```

### **Criar Aliases**

```bash
# Alias para status
git config --global alias.st status

# Alias para commit
git config --global alias.ci commit

# Alias para push em ambos os remotes
git config --global alias.pushall '!git push origin main && git push cpanel main'

# Usar:
git st
git ci -m "mensagem"
git pushall
```

### **Configurar Editor Padrão**

```bash
# Usar VSCode como editor
git config --global core.editor "code --wait"

# Usar Notepad++
git config --global core.editor "'C:/Program Files/Notepad++/notepad++.exe' -multiInst -notabbar -nosession -noPlugin"
```

---

## 🚫 Arquivos Ignorados (.gitignore)

### **Arquivos que NÃO devem ir para o Git:**

```gitignore
# Configurações locais (diferentes em cada ambiente)
application/config/config.php
application/config/database.php
.env

# Logs e cache
application/logs/*
application/cache/*
!application/logs/index.html
!application/cache/index.html

# Uploads de usuários
uploads/*
!uploads/index.html

# Sistema operacional
.DS_Store
Thumbs.db
desktop.ini

# IDEs
.vscode/
.idea/
*.sublime-project
*.sublime-workspace

# Temporários
*.log
*.tmp
*.bak
*~
```

---

## 🎯 Padrões de Mensagens de Commit

Use mensagens claras e descritivas:

### **Tipos de Commit:**

- **feat:** Nova funcionalidade
- **fix:** Correção de bug
- **docs:** Alteração em documentação
- **style:** Formatação de código (sem alterar lógica)
- **refactor:** Refatoração de código
- **test:** Adicionar ou modificar testes
- **chore:** Tarefas de manutenção

### **Exemplos:**

```bash
git commit -m "feat: adicionar integração com Stripe"
git commit -m "fix: corrigir erro 500 no login"
git commit -m "docs: atualizar README com instruções de deploy"
git commit -m "style: formatar código do controller Imoveis"
git commit -m "refactor: reorganizar estrutura de pastas"
git commit -m "chore: atualizar dependências"
```

---

## 🔄 Sincronizar Ambientes

### **Localhost → GitHub → cPanel**

```bash
# No localhost
git add .
git commit -m "feat: nova funcionalidade"
git push origin main
git push cpanel main
```

### **cPanel → GitHub → Localhost**

```bash
# No cPanel (via SSH)
git add .
git commit -m "fix: correção no servidor"
git push origin main

# No localhost
git pull origin main
```

---

## 🆘 Resolver Conflitos

### **Quando há conflitos no merge:**

```bash
# 1. Tentar fazer pull
git pull origin main

# 2. Se houver conflito, Git mostrará os arquivos
# 3. Abrir arquivos com conflito e resolver manualmente
# 4. Procurar por marcadores:
#    <<<<<<< HEAD
#    seu código
#    =======
#    código do servidor
#    >>>>>>> origin/main

# 5. Após resolver, adicionar arquivos
git add arquivo-resolvido.php

# 6. Finalizar merge
git commit -m "merge: resolver conflitos"

# 7. Enviar
git push origin main
```

---

## 📊 Ver Diferenças Entre Ambientes

```bash
# Ver diferenças entre local e GitHub
git fetch origin
git diff origin/main

# Ver diferenças entre local e cPanel
git fetch cpanel
git diff cpanel/main

# Ver diferenças de arquivo específico
git diff origin/main -- application/controllers/Imoveis.php
```

---

## 🎓 Comandos Avançados

### **Stash (Guardar alterações temporariamente)**

```bash
# Guardar alterações sem commitar
git stash

# Listar stashes
git stash list

# Recuperar último stash
git stash pop

# Recuperar stash específico
git stash apply stash@{0}

# Deletar stash
git stash drop stash@{0}
```

### **Cherry-pick (Aplicar commit específico)**

```bash
# Aplicar commit de outra branch
git cherry-pick abc1234
```

### **Rebase (Reorganizar commits)**

```bash
# Rebase interativo dos últimos 3 commits
git rebase -i HEAD~3
```

---

## 📞 Comandos de Emergência

### **Desfazer Push (CUIDADO!)**

```bash
# Desfazer último push (use com cautela!)
git reset --hard HEAD~1
git push --force origin main
```

### **Recuperar Arquivo Deletado**

```bash
# Recuperar arquivo deletado do último commit
git checkout HEAD -- arquivo.php

# Recuperar de commit específico
git checkout abc1234 -- arquivo.php
```

---

## ✅ Checklist Diário

Antes de começar a trabalhar:
- [ ] `git pull origin main` - Baixar últimas alterações
- [ ] Fazer alterações no código
- [ ] Testar localmente
- [ ] `git add .` - Adicionar arquivos
- [ ] `git commit -m "mensagem"` - Commitar
- [ ] `git push origin main` - Enviar para GitHub
- [ ] `git push cpanel main` - Enviar para cPanel
- [ ] Testar no ambiente online

---

## 📚 Recursos Úteis

- **Documentação Git:** https://git-scm.com/doc
- **GitHub Guides:** https://guides.github.com/
- **Git Cheat Sheet:** https://education.github.com/git-cheat-sheet-education.pdf

---

**Dúvidas? Consulte este guia ou entre em contato! 🚀**
