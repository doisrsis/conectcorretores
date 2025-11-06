# 📚 Documentação - ConectCorretores

**Autor:** Rafael Dias - doisr.com.br  
**Última atualização:** 02/11/2025

---

## 📋 Relatórios para Cliente

- **[Relatório do Sistema de Assinaturas](RELATORIO-CLIENTE-SISTEMA-ASSINATURAS.md)**
  - Linguagem não-técnica para apresentação
  - O que está funcionando
  - Melhorias sugeridas com priorização
  - Impacto no negócio
  - Status: 📊 Pronto para Apresentação

## 📖 Índice

### 🔧 Desenvolvimento

- **[Como Testar Sincronização](desenvolvimento/COMO-TESTAR-SINCRONIZACAO.md)**
  - Guia completo de testes da sincronização com Stripe
  - Webhook, Cron e Login
  - Troubleshooting

- **[Stripe Customer ID](desenvolvimento/STRIPE_CUSTOMER_ID.md)**
  - O que é e para que serve
  - Quando é criado/salvo
  - Implementações futuras

- **[Gestão de Imóveis por Plano](desenvolvimento/GESTAO-IMOVEIS-POR-PLANO.md)**
  - Sistema de controle de acesso
  - Bloqueio e reativação automática
  - Cron de desativação
  - Status: ✅ Implementado

- **[Melhorias no Cadastro de Imóveis](desenvolvimento/MELHORIAS-CADASTRO-IMOVEIS.md)**
  - Remoção de campos de contato
  - Novo campo "Link do Imóvel"
  - Migration SQL
  - Status: ✅ Implementado | ⏳ Migration Pendente

- **[Análise do Sistema de Assinaturas](desenvolvimento/ANALISE-SISTEMA-ASSINATURAS.md)**
  - Análise completa do fluxo atual
  - Gaps identificados
  - Melhorias sugeridas (UX, Stripe, Segurança)
  - Priorização de implementações
  - Status: 📊 Análise Completa

- **[Sistema de Emails Transacionais](desenvolvimento/SISTEMA-EMAILS-IMPLEMENTADO.md)**
  - Estrutura completa implementada
  - 10 templates de emails prontos
  - Biblioteca de envio configurada
  - Guia de integração e testes
  - Status: ✅ Implementado e Testado

- **[Integração de Emails](desenvolvimento/INTEGRACAO-EMAILS-COMPLETA.md)**
  - 7 emails integrados no sistema
  - Cadastro, webhooks, upgrade, downgrade, cancelamento
  - Fluxo completo documentado
  - Status: ✅ 70% Completo | ⏳ CRON jobs pendentes

- **[Configurar Webhook Secret](desenvolvimento/CONFIGURAR-WEBHOOK-STRIPE-CLI.md)**
  - Guia completo de configuração
  - Stripe CLI setup
  - Testes de webhooks
  - Status: ✅ Configurado e Testado

- **[Git Workflow](desenvolvimento/GIT-WORKFLOW.md)**
  - Fluxo de trabalho com Git
  - Comandos úteis
  - Boas práticas

---

### 🐛 Bugs Resolvidos

- **[Bug: Select UF e Máscara de Preço](bugs/BUG-SELECT-UF-E-MASCARA-PRECO.md)**
  - Problema: Select UF vazio e interferência na máscara
  - Solução: Remover máscaras de campos inexistentes e campo Valor m²
  - Data: 03/11/2025

- **[Bug: Data Stripe Resolvido](bugs/BUG-DATA-STRIPE-RESOLVIDO.md)**
  - Problema: Datas sendo sobrescritas
  - Solução: Validação de datas
  - Data: 19/10/2025

- **[Corrigir Usuários (ID = 0)](bugs/CORRIGIR-USUARIOS.md)**
  - Problema: AUTO_INCREMENT perdido
  - Solução: Scripts SQL de correção
  - Data: 02/11/2025

---

## 🗄️ Scripts SQL

Localização: `/database/fixes/`

- `fix_users_table.sql` - Corrigir tabela users (deletar ID = 0)
- `fix_users_keep_data.sql` - Corrigir tabela users (manter dados)

---

## 📁 Estrutura de Pastas

```
docs/
├── desenvolvimento/     # Guias de desenvolvimento
├── bugs/               # Documentação de bugs resolvidos
└── README.md          # Este arquivo

database/
├── fixes/             # Scripts de correção
├── migrations/        # Migrações (futuro)
└── seeds/            # Dados iniciais (futuro)

scripts/
└── windows/          # Scripts .bat (futuro)
```

---

## 🚀 Como Usar Esta Documentação

### **Encontrar Guias de Desenvolvimento:**
```
docs/desenvolvimento/
```

### **Ver Bugs Resolvidos:**
```
docs/bugs/
```

### **Executar Scripts SQL:**
```
database/fixes/
```

---

## ✅ Convenções

### **Nomenclatura de Arquivos:**

**Documentação:**
- `NOME-DO-DOCUMENTO.md` (MAIÚSCULAS com hífens)

**Scripts SQL:**
- `fix_nome_descritivo.sql` (minúsculas com underscores)
- `migration_YYYYMMDD_descricao.sql` (para migrações)

**Scripts:**
- `nome-descritivo.bat` (minúsculas com hífens)

---

## 📝 Contribuindo

Ao criar nova documentação:

1. ✅ Escolher pasta apropriada
2. ✅ Seguir convenção de nomenclatura
3. ✅ Adicionar ao índice deste README
4. ✅ Incluir autor e data

---

## 🔍 Busca Rápida

### **Stripe:**
- [Stripe Customer ID](desenvolvimento/STRIPE_CUSTOMER_ID.md)
- [Testar Sincronização](desenvolvimento/COMO-TESTAR-SINCRONIZACAO.md)
- [Bug Data Stripe](bugs/BUG-DATA-STRIPE-RESOLVIDO.md)

### **Banco de Dados:**
- [Corrigir Usuários](bugs/CORRIGIR-USUARIOS.md)
- [Scripts SQL](../database/fixes/)

### **Git:**
- [Git Workflow](desenvolvimento/GIT-WORKFLOW.md)

---

**Documentação organizada! 🎉**

Para suporte: Rafael Dias - doisr.com.br
