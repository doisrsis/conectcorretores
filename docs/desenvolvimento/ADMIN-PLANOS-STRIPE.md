# 🎯 Sistema de Gerenciamento de Planos - Integração Stripe

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 19/10/2025  
**Versão:** 1.0

---

## 📋 Visão Geral

Sistema completo de gerenciamento de planos integrado com Stripe no painel administrativo. Permite criar, editar, desativar e sincronizar planos automaticamente entre o banco de dados e o Stripe.

---

## 🎯 Funcionalidades Implementadas

### ✅ **1. Listagem de Planos**
- Visualizar todos os planos cadastrados
- Estatísticas: Total de planos, Planos ativos, Produtos no Stripe
- Status de sincronização com Stripe
- Filtros e informações detalhadas

### ✅ **2. Criar Novo Plano**
- Formulário intuitivo com validações
- Criação automática no Stripe (Produto + Preço)
- Salvamento no banco de dados
- Disponibilização automática para clientes

### ✅ **3. Editar Plano**
- Atualização de nome, descrição e limite de imóveis
- Sincronização automática com Stripe
- Alteração de preço (cria novo Price no Stripe)
- Ativar/Desativar plano

### ✅ **4. Desativar Plano**
- Verificação de assinaturas ativas
- Desativação no Stripe e banco de dados
- Proteção contra exclusão com assinaturas ativas

### ✅ **5. Sincronizar com Stripe**
- Importar produtos do Stripe para o banco
- Sincronização automática de preços
- Evita duplicações

---

## 🗂️ Estrutura de Arquivos

### **Backend:**
```
application/
├── controllers/
│   └── Admin.php (métodos: planos, planos_criar, planos_editar, planos_excluir, planos_sincronizar)
├── libraries/
│   └── Stripe_lib.php (9 novos métodos para gerenciar produtos/preços)
└── models/
    └── Plan_model.php (método: get_by_stripe_product_id)
```

### **Frontend:**
```
application/views/
├── admin/
│   └── planos/
│       ├── index.php (listagem)
│       ├── criar.php (formulário de criação)
│       └── editar.php (formulário de edição)
└── templates/
    └── sidebar.php (link "Gerenciar Planos" adicionado)
```

---

## 🔧 Métodos da Stripe_lib

### **Produtos:**
- `list_products($limit = 100)` - Listar produtos
- `create_product($name, $description)` - Criar produto
- `update_product($product_id, $data)` - Atualizar produto
- `deactivate_product($product_id)` - Desativar produto
- `get_product($product_id)` - Buscar produto por ID

### **Preços:**
- `list_prices($limit = 100)` - Listar preços
- `create_price($product_id, $amount, $currency, $interval)` - Criar preço
- `deactivate_price($price_id)` - Desativar preço
- `get_price($price_id)` - Buscar preço por ID

---

## 🚀 Como Usar

### **1. Acessar Painel Admin**
```
URL: /admin/planos
Menu: Admin Dashboard > Gerenciar Planos
```

### **2. Criar Novo Plano**

**Passo a Passo:**
1. Clique em "Novo Plano"
2. Preencha:
   - Nome do Plano (ex: "Plano Premium")
   - Descrição (opcional)
   - Preço (ex: 199.90)
   - Tipo de Cobrança (Mensal, Trimestral, Semestral, Anual)
   - Limite de Imóveis (vazio = ilimitado)
3. Clique em "Criar Plano"

**O que acontece:**
- ✅ Produto criado no Stripe
- ✅ Preço recorrente criado no Stripe
- ✅ Plano salvo no banco de dados
- ✅ Plano disponível para clientes em `/planos`

### **3. Editar Plano Existente**

**Passo a Passo:**
1. Na listagem, clique em "Editar"
2. Altere as informações desejadas
3. Clique em "Salvar Alterações"

**Importante:**
- ⚠️ **Nome/Descrição:** Atualiza no Stripe
- ⚠️ **Preço:** Cria novo Price no Stripe (desativa o antigo)
- ⚠️ **Tipo:** Não pode ser alterado após criação
- ⚠️ **Status:** Ativo/Inativo (planos inativos não aparecem para novos clientes)

### **4. Desativar Plano**

**Passo a Passo:**
1. Na listagem, clique em "Desativar"
2. Confirme a ação

**Validações:**
- ❌ Não permite desativar plano com assinaturas ativas
- ✅ Desativa no Stripe e banco de dados
- ✅ Plano não aparece mais para novos clientes

### **5. Sincronizar com Stripe**

**Quando usar:**
- Você criou produtos diretamente no Stripe Dashboard
- Quer importar produtos existentes para o banco

**Passo a Passo:**
1. Clique em "Sincronizar Stripe"
2. Confirme a ação
3. Sistema importa produtos que não existem no banco

---

## 🔄 Fluxo de Criação de Plano

```
┌─────────────────────────────────────────────────────────┐
│ 1. Admin preenche formulário                            │
│    - Nome, Descrição, Preço, Tipo, Limite              │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ 2. Sistema cria PRODUTO no Stripe                       │
│    POST /v1/products                                     │
│    { name, description }                                 │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ 3. Sistema cria PREÇO no Stripe                         │
│    POST /v1/prices                                       │
│    { product_id, amount, currency, recurring }          │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ 4. Sistema salva no BANCO DE DADOS                      │
│    INSERT INTO plans                                     │
│    { nome, preco, stripe_product_id, stripe_price_id }  │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ 5. Plano disponível para CLIENTES                       │
│    Aparece automaticamente em /planos                    │
└─────────────────────────────────────────────────────────┘
```

---

## 🔄 Fluxo de Edição de Plano

```
┌─────────────────────────────────────────────────────────┐
│ 1. Admin altera informações                             │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────────────────────┐
│ 2. Atualiza PRODUTO no Stripe                           │
│    PATCH /v1/products/{id}                              │
│    { name, description }                                 │
└────────────────┬────────────────────────────────────────┘
                 │
                 ▼
         ┌───────┴───────┐
         │ Preço mudou?  │
         └───┬───────┬───┘
             │ SIM   │ NÃO
             ▼       ▼
    ┌────────────┐  ┌────────────┐
    │ Desativa   │  │ Pula esta  │
    │ preço      │  │ etapa      │
    │ antigo     │  └────────────┘
    └─────┬──────┘
          │
          ▼
    ┌────────────┐
    │ Cria novo  │
    │ preço      │
    └─────┬──────┘
          │
          ▼
┌─────────────────────────────────────────────────────────┐
│ 3. Atualiza BANCO DE DADOS                              │
│    UPDATE plans SET ...                                  │
└─────────────────────────────────────────────────────────┘
```

---

## 📊 Campos da Tabela `plans`

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | INT | ID único do plano |
| `nome` | VARCHAR(100) | Nome do plano |
| `descricao` | TEXT | Descrição detalhada |
| `preco` | DECIMAL(10,2) | Valor da assinatura |
| `tipo` | ENUM | mensal, trimestral, semestral, anual |
| `stripe_product_id` | VARCHAR(255) | ID do produto no Stripe |
| `stripe_price_id` | VARCHAR(255) | ID do preço no Stripe |
| `limite_imoveis` | INT | Limite de imóveis (NULL = ilimitado) |
| `ativo` | TINYINT(1) | 1 = Ativo, 0 = Inativo |
| `created_at` | TIMESTAMP | Data de criação |
| `updated_at` | TIMESTAMP | Data de atualização |

---

## ⚠️ Regras de Negócio

### **Criação:**
- ✅ Todos os planos são criados no Stripe automaticamente
- ✅ Planos criados ficam ativos por padrão
- ✅ Preço deve ser maior que 0
- ✅ Nome é obrigatório

### **Edição:**
- ✅ Alterar preço cria novo Price no Stripe
- ✅ Preço antigo é desativado (não deletado)
- ✅ Assinaturas existentes mantêm preço antigo
- ✅ Novas assinaturas usam novo preço
- ❌ Não é possível alterar o tipo após criação

### **Desativação:**
- ❌ Não pode desativar plano com assinaturas ativas
- ✅ Plano é desativado no Stripe e banco
- ✅ Plano não aparece para novos clientes
- ✅ Assinaturas existentes continuam funcionando

### **Sincronização:**
- ✅ Importa apenas produtos que não existem no banco
- ✅ Evita duplicações
- ✅ Busca primeiro preço ativo de cada produto
- ✅ Converte interval do Stripe para tipo do banco

---

## 🧪 Testes Recomendados

### **1. Criar Plano**
```
✓ Criar plano mensal de R$ 49,90
✓ Verificar criação no Stripe Dashboard
✓ Verificar registro no banco de dados
✓ Verificar aparição em /planos para clientes
```

### **2. Editar Plano**
```
✓ Alterar nome e descrição
✓ Verificar atualização no Stripe
✓ Alterar preço de R$ 49,90 para R$ 59,90
✓ Verificar criação de novo Price no Stripe
✓ Verificar desativação do Price antigo
```

### **3. Desativar Plano**
```
✓ Tentar desativar plano com assinaturas ativas (deve falhar)
✓ Desativar plano sem assinaturas
✓ Verificar desativação no Stripe
✓ Verificar que não aparece mais em /planos
```

### **4. Sincronizar**
```
✓ Criar produto no Stripe Dashboard
✓ Clicar em "Sincronizar Stripe"
✓ Verificar importação para o banco
```

---

## 🔐 Segurança

### **Validações:**
- ✅ Apenas admin pode acessar `/admin/planos`
- ✅ Validação de campos obrigatórios
- ✅ Proteção contra SQL Injection (CodeIgniter Query Builder)
- ✅ Proteção contra XSS (htmlspecialchars automático)
- ✅ Confirmação antes de desativar

### **Tratamento de Erros:**
- ✅ Try/Catch em todas as chamadas Stripe
- ✅ Mensagens de erro amigáveis
- ✅ Rollback automático em caso de falha
- ✅ Logs de erros

---

## 📱 Responsividade

Todas as views são totalmente responsivas:
- ✅ Desktop (1920px+)
- ✅ Tablet (768px - 1024px)
- ✅ Mobile (320px - 767px)

---

## 🎨 Interface

### **Cores:**
- **Primária:** Azul (#3B82F6)
- **Sucesso:** Verde (#10B981)
- **Aviso:** Amarelo (#F59E0B)
- **Erro:** Vermelho (#EF4444)
- **Stripe:** Roxo (#8B5CF6)

### **Ícones:**
- Heroicons (SVG inline)
- Tailwind CSS para estilização

---

## 🔗 URLs do Sistema

| Função | URL | Método |
|--------|-----|--------|
| Listar planos | `/admin/planos` | GET |
| Criar plano | `/admin/planos_criar` | GET/POST |
| Editar plano | `/admin/planos_editar/{id}` | GET/POST |
| Desativar plano | `/admin/planos_excluir/{id}` | GET |
| Sincronizar | `/admin/planos_sincronizar` | GET |

---

## 📝 Próximas Melhorias (Opcional)

- [ ] Duplicar plano existente
- [ ] Histórico de alterações de preço
- [ ] Relatório de receita por plano
- [ ] Exportar planos para CSV
- [ ] Planos com trial period
- [ ] Cupons de desconto
- [ ] Planos com múltiplos preços (mensal/anual)

---

## 🆘 Troubleshooting

### **Erro: "Stripe API Key not found"**
**Solução:** Verificar `application/config/stripe.php`

### **Erro: "Product not created in Stripe"**
**Solução:** Verificar credenciais Stripe e conexão com internet

### **Erro: "Cannot deactivate plan with active subscriptions"**
**Solução:** Aguardar cancelamento de assinaturas ou desativar ao invés de excluir

### **Plano não aparece para clientes**
**Solução:** Verificar se `ativo = 1` no banco de dados

---

## ✅ Checklist de Implementação

- [x] Métodos Stripe_lib criados
- [x] Controller Admin atualizado
- [x] Views criadas (index, criar, editar)
- [x] Menu lateral atualizado
- [x] Método get_by_stripe_product_id no Plan_model
- [x] Documentação completa
- [ ] Testes realizados
- [ ] Deploy em produção

---

**Sistema pronto para uso! 🎉**

Para dúvidas ou suporte: Rafael Dias - doisr.com.br
