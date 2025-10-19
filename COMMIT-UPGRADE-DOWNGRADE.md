# 🚀 Commit: Sistema de Upgrade/Downgrade de Planos

**Data:** 19/10/2025  
**Autor:** Rafael Dias - doisr.com.br

---

## 📋 Resumo do Commit

Implementação completa do sistema de upgrade e downgrade de planos com integração Stripe, permitindo que usuários troquem de plano sem cancelar a assinatura atual.

---

## ✨ Funcionalidades Adicionadas

### **1. Botões Inteligentes de Upgrade/Downgrade**
- Comparação automática de preços entre plano atual e outros planos
- Botão verde "Fazer Upgrade" para planos mais caros
- Botão amarelo "Fazer Downgrade" para planos mais baratos
- Exibição da diferença de valor (+R$ XX ou Economize R$ XX)
- Botão "Plano Atual" desabilitado para o plano ativo

### **2. Processamento de Upgrade**
- Troca imediata de plano via Stripe API
- Cálculo proporcional automático (proration)
- Cobrança da diferença no cartão já cadastrado
- Atualização automática no banco de dados
- Redirecionamento para dashboard com mensagem de sucesso

### **3. Processamento de Downgrade**
- Troca imediata de plano via Stripe API
- Crédito proporcional aplicado ao próximo pagamento
- Verificação de limite de imóveis
- Inativação automática de imóveis se exceder o limite
- Mensagem orientando reativação manual de imóveis

### **4. Integração Stripe**
- Método `update_subscription()` para atualizar assinaturas
- Suporte a proration behavior (always_invoice)
- Tratamento de erros da API Stripe
- Uso de cartão já cadastrado (sem re-inserir dados)

### **5. Gestão de Imóveis no Downgrade**
- Contagem de imóveis ativos do usuário
- Inativação automática quando excede limite do novo plano
- Métodos no model para gerenciar status dos imóveis

---

## 📁 Arquivos Modificados

### **Views:**
- `application/views/planos/index.php`
  - Lógica de botões inteligentes
  - Exibição de diferença de preço
  - Funções JavaScript upgrade/downgrade

### **Controllers:**
- `application/controllers/Planos.php`
  - Método `upgrade()` - Processar upgrade de plano
  - Método `downgrade()` - Processar downgrade de plano
  - Validações de segurança e regras de negócio

### **Libraries:**
- `application/libraries/Stripe_lib.php`
  - Método `update_subscription()` - Atualizar assinatura no Stripe

### **Models:**
- `application/models/Subscription_model.php`
  - Correção: Adicionar campos `plan_descricao` e `plan_limite_imoveis` nas queries
  - Métodos: `get_by_id()`, `get_active_by_user()`, `get_by_user()`

- `application/models/Imovel_model.php`
  - Método `count_by_user()` - Contar imóveis do usuário
  - Método `inativar_todos_by_user()` - Inativar todos os imóveis

### **Views (Correções):**
- `application/views/dashboard/index.php`
  - Widget de plano com informações completas
  - Barra de progresso de uso de imóveis
  - Botões de upgrade e cancelamento

- `application/views/dashboard/perfil.php`
  - Widget de plano no topo da página
  - Informações detalhadas do plano atual
  - Links para upgrade e gerenciamento

---

## 📝 Documentação Criada

- `MELHORIAS-UX-PLANOS.md` - Documentação de melhorias de UX
- `UPGRADE-DOWNGRADE-IMPLEMENTADO.md` - Documentação técnica completa
- `COMMIT-UPGRADE-DOWNGRADE.md` - Este arquivo (resumo do commit)

---

## 🔧 Detalhes Técnicos

### **Fluxo de Upgrade:**
1. Validação de login e assinatura ativa
2. Verificação se novo plano é mais caro
3. Atualização via Stripe API (subscription update)
4. Stripe calcula e cobra proporcional automaticamente
5. Atualização do plan_id no banco de dados
6. Retorno JSON com sucesso

### **Fluxo de Downgrade:**
1. Validação de login e assinatura ativa
2. Verificação se novo plano é mais barato
3. Contagem de imóveis ativos do usuário
4. Inativação de imóveis se exceder limite do novo plano
5. Atualização via Stripe API (subscription update)
6. Stripe calcula e credita proporcional
7. Atualização do plan_id no banco de dados
8. Retorno JSON com mensagem sobre imóveis

### **Segurança:**
- Validação de autenticação em todos os endpoints
- Verificação de propriedade da assinatura
- Sanitização de inputs
- Try-catch em chamadas Stripe
- Validação de planos ativos

### **Cálculo Proporcional (Stripe):**
- `proration_behavior: 'always_invoice'`
- Upgrade: Cobra diferença imediatamente
- Downgrade: Credita diferença no próximo pagamento
- Stripe gerencia automaticamente

---

## 🎯 Regras de Negócio Implementadas

### **Upgrade:**
- ✅ Permitido a qualquer momento
- ✅ Cobra diferença proporcional imediatamente
- ✅ Não afeta imóveis cadastrados
- ✅ Benefícios aplicados imediatamente

### **Downgrade:**
- ✅ Permitido a qualquer momento
- ✅ Credita diferença proporcional
- ✅ Inativa TODOS os imóveis se exceder limite
- ✅ Usuário deve reativar manualmente até o limite
- ✅ Limitações aplicadas imediatamente

---

## 🐛 Correções de Bugs

### **Bug: Propriedade `plan_limite_imoveis` não definida**
- **Arquivo:** `application/models/Subscription_model.php`
- **Problema:** Queries não selecionavam campos `descricao` e `limite_imoveis` da tabela plans
- **Solução:** Adicionar campos nas queries dos métodos:
  - `get_by_id()`
  - `get_active_by_user()`
  - `get_by_user()`

---

## 🧪 Testes Necessários

### **Teste de Upgrade:**
1. Login como corretor com plano ativo
2. Acessar /planos
3. Clicar em "Fazer Upgrade" em plano mais caro
4. Verificar mensagem de sucesso
5. Verificar novo plano no dashboard
6. Verificar cobrança no Stripe

### **Teste de Downgrade:**
1. Login como corretor com plano ativo
2. Acessar /planos
3. Clicar em "Fazer Downgrade" em plano mais barato
4. Verificar mensagem sobre imóveis (se aplicável)
5. Verificar novo plano no dashboard
6. Verificar crédito no Stripe
7. Verificar imóveis inativados (se excedeu limite)

### **Teste de Validações:**
1. Tentar upgrade sem login (deve bloquear)
2. Tentar upgrade para plano mais barato (deve dar erro)
3. Tentar downgrade para plano mais caro (deve dar erro)
4. Verificar tratamento de erros do Stripe

---

## 📊 Impacto

### **Melhorias de UX:**
- ✅ Usuário não precisa cancelar assinatura para trocar de plano
- ✅ Processo em 1 clique (direto, sem modal)
- ✅ Feedback visual claro (cores, ícones, valores)
- ✅ Mensagens informativas sobre mudanças

### **Melhorias Técnicas:**
- ✅ Integração completa com Stripe
- ✅ Cálculo proporcional automático
- ✅ Gestão inteligente de limites de imóveis
- ✅ Código modular e documentado

### **Melhorias de Negócio:**
- ✅ Facilita upgrades (aumenta receita)
- ✅ Permite downgrades controlados (reduz cancelamentos)
- ✅ Experiência fluida de troca de planos

---

## 🔄 Próximos Passos (Futuro)

1. Implementar sincronização com Stripe (webhook + login + cron)
2. Criar página de reativação de imóveis após downgrade
3. Adicionar modal de confirmação (opcional)
4. Implementar histórico de mudanças de plano
5. Adicionar notificações por email
6. Criar comparação visual de planos

---

## 📌 Notas Importantes

- Dados de cartão ficam APENAS no Stripe (PCI-DSS compliant)
- Upgrade/downgrade usa cartão já cadastrado
- Stripe gerencia cobrança/crédito proporcional automaticamente
- Sistema inativa imóveis no downgrade se exceder limite
- Usuário deve reativar imóveis manualmente

---

## ✅ Checklist de Commit

- [x] Código testado localmente
- [x] Documentação criada
- [x] Comentários adicionados
- [x] Boas práticas seguidas
- [x] Segurança validada
- [x] Integração Stripe funcionando
- [x] Tratamento de erros implementado
- [ ] Testes em ambiente de produção (após deploy)

---

## 🎯 Mensagem de Commit Sugerida

```
feat: Implementar sistema de upgrade/downgrade de planos

- Adicionar botões inteligentes de upgrade/downgrade na página de planos
- Implementar métodos upgrade() e downgrade() no controller Planos
- Adicionar método update_subscription() na biblioteca Stripe
- Implementar gestão automática de imóveis no downgrade
- Corrigir bug de propriedades não definidas no Subscription_model
- Adicionar widgets de plano no dashboard e perfil
- Criar documentação completa do sistema

Closes #[número-da-issue]
```

---

## 📦 Arquivos para Commit

### **Novos:**
```
MELHORIAS-UX-PLANOS.md
UPGRADE-DOWNGRADE-IMPLEMENTADO.md
COMMIT-UPGRADE-DOWNGRADE.md
```

### **Modificados:**
```
application/views/planos/index.php
application/views/dashboard/index.php
application/views/dashboard/perfil.php
application/controllers/Planos.php
application/libraries/Stripe_lib.php
application/models/Subscription_model.php
application/models/Imovel_model.php
```

---

**Pronto para commit! 🚀**

Comandos Git sugeridos em: `GIT_COMANDOS.md`
