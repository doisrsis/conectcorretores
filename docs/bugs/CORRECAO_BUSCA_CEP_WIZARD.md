# CORRECAO - BUSCA DE CEP NO WIZARD

Projeto: ConectCorretores  
Data: 11/11/2025  
Autor: Rafael Dias - doisr.com.br  
Tipo: Bug Fix

## PROBLEMA REPORTADO

### Sintomas:
1. Ao buscar CEP, aparecia erro: "Erro ao buscar CEP. Tente novamente."
2. Estado era preenchido corretamente
3. Bairro era preenchido corretamente
4. **Cidades não carregavam** (select permanecia vazio)

### Causa Raiz:
A função `carregarCidades()` não tinha parâmetro para selecionar cidade por nome, apenas por ID. Quando a busca de CEP tentava selecionar a cidade pelo nome retornado da API ViaCEP, a função não tinha essa capacidade.

---

## SOLUÇÃO IMPLEMENTADA

### 1. Função carregarCidades() Atualizada

**Antes:**
```javascript
function carregarCidades(estadoId, cidadeIdSelecionada = null) {
    // ... código ...
    if (cidadeIdSelecionada) {
        cidadeSelect.value = cidadeIdSelecionada;
    }
}
```

**Depois:**
```javascript
function carregarCidades(estadoId, cidadeIdSelecionada = null, cidadeNome = null) {
    // ... código ...
    
    // Selecionar por ID
    if (cidadeIdSelecionada) {
        cidadeSelect.value = cidadeIdSelecionada;
    }
    // Selecionar por nome (busca de CEP)
    else if (cidadeNome) {
        const cidadeOption = Array.from(cidadeSelect.options).find(opt => 
            opt.textContent.toLowerCase() === cidadeNome.toLowerCase()
        );
        if (cidadeOption) {
            cidadeSelect.value = cidadeOption.value;
        }
    }
}
```

### 2. Busca de CEP Corrigida

**Antes:**
```javascript
carregarCidades(estadoOption.value).then(() => {
    // Tentava usar Promise que não existia
});
```

**Depois:**
```javascript
carregarCidades(estadoOption.value, null, data.localidade);
// Passa o nome da cidade como 3º parâmetro
```

### 3. Melhorias Adicionais

**Validação de Response:**
```javascript
.then(response => {
    if (!response.ok) {
        throw new Error('Erro na requisição');
    }
    return response.json();
})
```

**Tratamento de Campos Vazios:**
```javascript
document.getElementById('bairro').value = data.bairro || '';
```

---

## FLUXO CORRIGIDO

### Busca de CEP:
```
1. Usuário digita CEP
2. Clica em "Buscar"
3. Fetch para ViaCEP
4. Retorna: {uf, localidade, bairro}
5. Preenche bairro
6. Encontra estado pelo UF
7. Seleciona estado
8. Chama carregarCidades(estadoId, null, nomeCidade)
9. Carrega cidades do banco
10. Seleciona cidade pelo nome
11. ✅ Sucesso!
```

---

## TESTES REALIZADOS

### Cenário 1: Busca de CEP Válido
- [x] CEP: 01310-100 (Av. Paulista, São Paulo)
- [x] Estado: SP selecionado
- [x] Cidade: São Paulo selecionada
- [x] Bairro: Bela Vista preenchido

### Cenário 2: CEP sem Bairro
- [x] CEP retorna bairro vazio
- [x] Campo bairro fica vazio (não dá erro)

### Cenário 3: Cidade não encontrada
- [x] Cidade não existe no banco
- [x] Select de cidades carrega normalmente
- [x] Nenhuma cidade selecionada (usuário escolhe manualmente)

---

## ARQUIVO MODIFICADO

**Arquivo:** `application/views/imoveis/form_wizard.php`

**Linhas modificadas:**
- Linha 416-452: Função `carregarCidades()` com novo parâmetro
- Linha 457-493: Busca de CEP corrigida

---

## IMPACTO

### Antes:
- ❌ Busca de CEP não funcionava completamente
- ❌ Usuário tinha que selecionar cidade manualmente
- ❌ Mensagem de erro confusa

### Depois:
- ✅ Busca de CEP funciona 100%
- ✅ Cidade selecionada automaticamente
- ✅ UX melhorada
- ✅ Menos cliques para o usuário

---

## PRÓXIMAS MELHORIAS

1. **Cache de Estados/Cidades:**
   - Evitar requisições repetidas
   - Armazenar em localStorage

2. **Feedback Visual:**
   - Loading state ao carregar cidades
   - Animação de preenchimento

3. **Validação de CEP:**
   - Validar formato antes de buscar
   - Sugestões de CEP próximos

4. **Histórico de CEPs:**
   - Salvar últimos CEPs buscados
   - Autocomplete

---

## NOTAS TÉCNICAS

### API ViaCEP:
- URL: `https://viacep.com.br/ws/{cep}/json/`
- Retorno: `{cep, logradouro, bairro, localidade, uf, ...}`
- Gratuita, sem necessidade de chave
- Rate limit: ~300 req/min

### Comparação Case-Insensitive:
```javascript
opt.textContent.toLowerCase() === cidadeNome.toLowerCase()
```
Garante que "São Paulo" = "são paulo" = "SÃO PAULO"

### Fallback:
Se cidade não for encontrada, select fica vazio e usuário escolhe manualmente.

---

## CONCLUSÃO

Bug corrigido com sucesso. A busca de CEP agora funciona completamente, preenchendo:
- ✅ Estado
- ✅ Cidade
- ✅ Bairro

Melhorando significativamente a UX do cadastro de imóveis.
