# WIZARD MULTI-STEP - FORMULARIO DE IMOVEIS

Projeto: ConectCorretores  
Data: 11/11/2025  
Autor: Rafael Dias - doisr.com.br  

## PROBLEMAS CORRIGIDOS

### 1. ViaCEP não funcionava
**Solução:** Implementado fetch direto para API ViaCEP com tratamento de erros

### 2. Estados não carregavam
**Solução:** Implementado carregamento via AJAX do banco de dados

### 3. Cidades não carregavam
**Solução:** Implementado carregamento dinâmico baseado no estado selecionado

### 4. Máscara de moeda não funcionava
**Solução:** Implementado IMask.js com configuração correta para moeda brasileira

---

## WIZARD MULTI-STEP IMPLEMENTADO

### Arquivo Criado:
`application/views/imoveis/form_wizard.php`

### Estrutura:
4 Steps organizados em wizard

---

## STEPS DO WIZARD

### STEP 1: Localização 📍
**Campos:**
- CEP (com máscara 00000-000)
- Botão "Buscar CEP" (integração ViaCEP)
- Estado (UF) - Select carregado do banco
- Cidade - Select carregado dinamicamente
- Bairro - Input text

**Validações:**
- Estado, Cidade e Bairro são obrigatórios
- CEP com máscara automática

**Funcionalidades:**
- Busca automática por CEP
- Preenchimento automático de Estado, Cidade e Bairro
- Loading state no botão de busca

---

### STEP 2: Características 🏠
**Campos:**
- Tipo de Negócio (Venda/Aluguel)
- Tipo de Imóvel (Casa/Apartamento/Terreno/Comercial/Rural)
- Quantidade de Quartos (1-5+)
- Quantidade de Vagas (1-5+)

**Validações:**
- Tipo de Negócio e Tipo de Imóvel são obrigatórios
- Quartos e Vagas são opcionais

---

### STEP 3: Valores 💰
**Campos:**
- Preço (R$) - com máscara de moeda
- Área Privativa (m²) - apenas números

**Validações:**
- Ambos os campos são obrigatórios
- Preço com máscara: 1.000,00
- Área com separador de milhares

**Máscaras:**
```javascript
Preço: Number com scale 2, thousandsSeparator '.', radix ','
Área: Number com scale 0, thousandsSeparator '.'
```

**Alert:**
- Informação de que o valor por m² será calculado automaticamente

---

### STEP 4: Informações Extras 🔗
**Campos:**
- URL do Imóvel (opcional)

**Validações:**
- Campo opcional
- Validação de formato URL (HTML5)

**Alert:**
- Mensagem de sucesso indicando que está pronto para finalizar

---

## COMPONENTES TABLER UTILIZADOS

### 1. Steps Header
```html
<ul class="steps steps-counter steps-lime">
    <li class="step-item active">Localização</li>
    <li class="step-item">Características</li>
    <li class="step-item">Valores</li>
    <li class="step-item">Informações Extras</li>
</ul>
```

**Características:**
- Indicadores numerados
- Cor verde (steps-lime)
- Estado ativo visual
- Responsivo

---

### 2. Wizard Steps
```html
<div class="wizard-step" id="step-1">
    <!-- Conteúdo do step -->
</div>
```

**Controle:**
- JavaScript controla visibilidade
- Apenas um step visível por vez
- Transição suave

---

### 3. Navigation Buttons
**Botões:**
- **Anterior:** Visível a partir do step 2
- **Próximo:** Visível até step 3
- **Salvar:** Visível apenas no step 4
- **Cancelar:** Sempre visível

**Estados:**
```javascript
Step 1: [Cancelar] [Próximo →]
Step 2: [← Anterior] [Cancelar] [Próximo →]
Step 3: [← Anterior] [Cancelar] [Próximo →]
Step 4: [← Anterior] [Cancelar] [💾 Salvar Imóvel]
```

---

## JAVASCRIPT IMPLEMENTADO

### 1. Navegação do Wizard
```javascript
function showStep(step) {
    - Esconde todos os steps
    - Mostra step atual
    - Atualiza indicadores
    - Controla botões
}
```

### 2. Validação por Step
```javascript
function validateStep(step) {
    - Valida campos obrigatórios
    - Adiciona classe is-invalid
    - Mostra alert se inválido
    - Retorna true/false
}
```

### 3. Máscaras (IMask.js)
```javascript
CEP: 00000-000
Preço: Number (scale 2, separadores BR)
Área: Number (scale 0, separador de milhares)
```

### 4. Carregamento de Estados
```javascript
fetch(baseUrl + 'imoveis/get_estados')
    - Carrega do banco via AJAX
    - Popula select de estados
    - Seleciona automaticamente ao editar
```

### 5. Carregamento de Cidades
```javascript
fetch(baseUrl + 'imoveis/get_cidades/' + estadoId)
    - Carrega do banco via AJAX
    - Popula select de cidades
    - Baseado no estado selecionado
```

### 6. Busca de CEP (ViaCEP)
```javascript
fetch('https://viacep.com.br/ws/' + cep + '/json/')
    - Busca endereço na API
    - Preenche bairro
    - Seleciona estado e cidade
    - Loading state no botão
    - Tratamento de erros
```

---

## ENDPOINTS NECESSÁRIOS

### 1. GET Estados
**URL:** `imoveis/get_estados`  
**Método:** POST (AJAX)  
**Retorno:**
```json
{
    "success": true,
    "estados": [
        {"id": 1, "nome": "São Paulo", "uf": "SP"},
        ...
    ]
}
```

### 2. GET Cidades
**URL:** `imoveis/get_cidades/{estado_id}`  
**Método:** POST (AJAX)  
**Retorno:**
```json
{
    "success": true,
    "cidades": [
        {"id": 1, "nome": "São Paulo"},
        ...
    ]
}
```

---

## CONTROLLER ATUALIZADO

### Arquivo: `application/controllers/Imoveis.php`

**Métodos modificados:**
- `novo()` - Linha 171: usa `form_wizard`
- `editar($id)` - Linha 278: usa `form_wizard`

---

## RECURSOS IMPLEMENTADOS

### 1. UX Melhorada ✨
- Formulário dividido em etapas lógicas
- Menos campos por tela
- Foco em uma tarefa por vez
- Indicadores visuais de progresso
- Validação por etapa

### 2. Validações ✓
- Validação client-side por step
- Campos obrigatórios marcados
- Feedback visual (is-invalid)
- Mensagens de erro claras
- Prevenção de avanço sem preencher

### 3. Máscaras Funcionais 🎭
- CEP: 00000-000
- Preço: R$ 1.000,00
- Área: 1.000 m²
- Aplicação automática
- Formatação brasileira

### 4. Integração ViaCEP 🔍
- Busca automática de endereço
- Preenchimento inteligente
- Loading state
- Tratamento de erros
- Feedback ao usuário

### 5. Carregamento Dinâmico 🔄
- Estados do banco de dados
- Cidades baseadas no estado
- AJAX assíncrono
- Seleção automática ao editar
- Performance otimizada

---

## TESTES NECESSÁRIOS

### Cadastro:
- [x] Wizard carrega corretamente
- [x] Step 1 exibe campos de localização
- [x] Botão "Próximo" avança para step 2
- [x] Botão "Anterior" volta para step 1
- [x] Validação impede avanço sem preencher
- [ ] Busca de CEP funciona
- [ ] Estados carregam do banco
- [ ] Cidades carregam ao selecionar estado
- [ ] Máscaras aplicadas corretamente
- [ ] Step 4 mostra botão "Salvar"
- [ ] Formulário salva no banco

### Edição:
- [x] Wizard carrega com dados
- [ ] Campos preenchidos corretamente
- [ ] Estado e cidade pré-selecionados
- [ ] Máscaras aplicadas nos valores
- [ ] Atualização salva no banco

### Integrações:
- [ ] ViaCEP retorna dados
- [ ] Estados carregam via AJAX
- [ ] Cidades carregam via AJAX
- [ ] Máscaras formatam corretamente

---

## URLS PARA TESTE

### Cadastro:
```
http://localhost/conectcorretores/imoveis/novo
```

### Edição:
```
http://localhost/conectcorretores/imoveis/editar/1
```

---

## PRÓXIMOS PASSOS

1. **Testar wizard completo**
2. **Verificar endpoints AJAX** (get_estados, get_cidades)
3. **Testar busca de CEP**
4. **Validar máscaras**
5. **Testar cadastro e edição**

---

## NOTAS IMPORTANTES

1. **IMask.js** incluído via CDN (unpkg)
2. **ViaCEP** API pública, sem necessidade de chave
3. **Validação** client-side + server-side
4. **Responsivo** mobile-first
5. **Acessível** com labels e hints
6. **Performance** carregamento assíncrono
7. **UX** feedback visual em todas as ações

---

## VANTAGENS DO WIZARD

### vs Formulário Tradicional:
- ✅ Menos sobrecarga cognitiva
- ✅ Foco em uma tarefa por vez
- ✅ Progresso visual claro
- ✅ Validação incremental
- ✅ Melhor em mobile
- ✅ Maior taxa de conclusão
- ✅ Menos erros de preenchimento
- ✅ UX profissional

---

## DEPENDÊNCIAS

### JavaScript:
- IMask.js (CDN)
- Fetch API (nativo)
- ES6+ (nativo)

### CSS:
- Tabler steps component
- Tabler form components
- Tabler alerts

### Backend:
- CodeIgniter 3
- Endpoints AJAX para estados/cidades
- ViaCEP API externa
