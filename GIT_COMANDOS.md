# 🚀 Guia Git - ConectCorretores v1.0.0

**Autor:** Rafael Dias - doisr.com.br  
**Repositório:** https://github.com/doisrsis/conectcorretores

---

## 📦 Opção 1: Usando o Script Automático (RECOMENDADO)

### Windows
```bash
# Execute o script
GIT_SETUP.bat
```

O script irá:
1. ✅ Inicializar o repositório Git
2. ✅ Adicionar todos os arquivos
3. ✅ Criar o commit inicial
4. ✅ Criar a branch main
5. ✅ Adicionar o repositório remoto
6. ✅ Fazer push para o GitHub

---

## 🔧 Opção 2: Comandos Manuais

### 1. Configure o Git (primeira vez)
```bash
git config --global user.name "Rafael Dias"
git config --global user.email "seu-email@exemplo.com"
```

### 2. Inicialize o repositório
```bash
cd c:\xampp\htdocs\conectcorretores
git init
```

### 3. Adicione todos os arquivos
```bash
git add .
```

### 4. Verifique o que será commitado
```bash
git status
```

### 5. Crie o commit inicial
```bash
git commit -m "feat: Versão inicial v1.0.0 - Sistema SaaS completo para gestão de imóveis

- Sistema de autenticação completo
- CRUD de imóveis com filtros e paginação
- Dashboard do corretor com estatísticas
- Painel administrativo completo
- Sistema de planos e assinaturas
- Design responsivo com Tailwind CSS
- Segurança implementada
- Documentação completa"
```

### 6. Renomeie a branch para main
```bash
git branch -M main
```

### 7. Adicione o repositório remoto
```bash
git remote add origin https://github.com/doisrsis/conectcorretores.git
```

### 8. Envie para o GitHub
```bash
git push -u origin main
```

---

## 🏷️ Criando a Tag v1.0.0

```bash
# Criar tag anotada
git tag -a v1.0.0 -m "Release v1.0.0 - Sistema completo e funcional"

# Enviar tag para o GitHub
git push origin v1.0.0
```

---

## 📝 Comandos Úteis para o Dia a Dia

### Ver status
```bash
git status
```

### Ver histórico
```bash
git log --oneline
```

### Adicionar arquivos específicos
```bash
git add arquivo.php
```

### Commit com mensagem
```bash
git commit -m "fix: Corrige bug no login"
```

### Enviar alterações
```bash
git push
```

### Atualizar do GitHub
```bash
git pull
```

### Ver diferenças
```bash
git diff
```

### Criar nova branch
```bash
git checkout -b feature/nova-funcionalidade
```

### Mudar de branch
```bash
git checkout main
```

### Mesclar branches
```bash
git merge feature/nova-funcionalidade
```

---

## 🔄 Workflow Recomendado

### Para novas funcionalidades:
```bash
# 1. Criar branch
git checkout -b feature/nome-da-funcionalidade

# 2. Fazer alterações e commits
git add .
git commit -m "feat: Adiciona nova funcionalidade"

# 3. Enviar branch
git push -u origin feature/nome-da-funcionalidade

# 4. Criar Pull Request no GitHub

# 5. Após aprovação, mesclar
git checkout main
git merge feature/nome-da-funcionalidade
git push
```

### Para correções de bugs:
```bash
# 1. Criar branch
git checkout -b fix/nome-do-bug

# 2. Fazer correção
git add .
git commit -m "fix: Corrige problema X"

# 3. Enviar e mesclar
git push -u origin fix/nome-do-bug
```

---

## 📋 Convenção de Commits (Conventional Commits)

Use prefixos para organizar seus commits:

- `feat:` - Nova funcionalidade
- `fix:` - Correção de bug
- `docs:` - Documentação
- `style:` - Formatação, ponto e vírgula, etc
- `refactor:` - Refatoração de código
- `test:` - Adicionar testes
- `chore:` - Tarefas de manutenção

### Exemplos:
```bash
git commit -m "feat: Adiciona sistema de favoritos"
git commit -m "fix: Corrige erro no upload de imagens"
git commit -m "docs: Atualiza README com novas instruções"
git commit -m "style: Formata código seguindo PSR-12"
git commit -m "refactor: Melhora performance do dashboard"
git commit -m "test: Adiciona testes para User_model"
git commit -m "chore: Atualiza dependências"
```

---

## 🔐 Arquivos Ignorados (.gitignore)

Os seguintes arquivos NÃO serão enviados ao GitHub:

- ✅ `application/config/database.php` (credenciais)
- ✅ `application/config/config.php` (configurações locais)
- ✅ `application/cache/*` (cache)
- ✅ `application/logs/*` (logs)
- ✅ `uploads/*` (arquivos de usuários)
- ✅ `.vscode/` (configurações do editor)
- ✅ `.idea/` (configurações do PHPStorm)

**IMPORTANTE:** Os arquivos `.example.php` SERÃO enviados como templates!

---

## 🌿 Estrutura de Branches Recomendada

```
main (produção)
├── develop (desenvolvimento)
│   ├── feature/sistema-pagamento
│   ├── feature/chat-tempo-real
│   └── feature/app-mobile
└── hotfix/correcao-urgente
```

---

## 🚨 Troubleshooting

### Erro: "remote origin already exists"
```bash
git remote remove origin
git remote add origin https://github.com/doisrsis/conectcorretores.git
```

### Erro: "failed to push some refs"
```bash
# Puxar alterações primeiro
git pull origin main --rebase
git push origin main
```

### Desfazer último commit (mantendo alterações)
```bash
git reset --soft HEAD~1
```

### Desfazer alterações em arquivo
```bash
git checkout -- arquivo.php
```

### Ver repositório remoto
```bash
git remote -v
```

---

## 📊 Verificar o que será enviado

Antes de fazer push, verifique:

```bash
# Ver arquivos modificados
git status

# Ver diferenças
git diff

# Ver o que está staged
git diff --staged

# Ver tamanho do repositório
git count-objects -vH
```

---

## ✅ Checklist Antes do Push

- [ ] Testei todas as funcionalidades
- [ ] Removi console.log e var_dump
- [ ] Atualizei a documentação
- [ ] Verifiquei o .gitignore
- [ ] Revisei o código
- [ ] Commit com mensagem clara
- [ ] Sem credenciais no código

---

## 🎯 Próximos Passos Após o Push

1. **Acesse o GitHub:**
   - https://github.com/doisrsis/conectcorretores

2. **Configure o repositório:**
   - Adicione descrição
   - Adicione topics (tags)
   - Configure GitHub Pages (se necessário)

3. **Crie a Release v1.0.0:**
   - Vá em "Releases"
   - "Create a new release"
   - Tag: v1.0.0
   - Title: "v1.0.0 - Lançamento Inicial"
   - Descrição: Copie do CHANGELOG.md

4. **Proteja a branch main:**
   - Settings > Branches
   - Add rule para "main"
   - Require pull request reviews

---

## 📞 Suporte

Dúvidas sobre Git? Consulte:
- [Git Documentation](https://git-scm.com/doc)
- [GitHub Guides](https://guides.github.com/)
- [Conventional Commits](https://www.conventionalcommits.org/)

---

**Desenvolvido com ❤️ por Rafael Dias - [doisr.com.br](https://doisr.com.br)**
