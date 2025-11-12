# FASE 3 - FORMULARIOS DE IMOVEIS

Projeto: ConectCorretores  
Data: 11/11/2025  
Autor: Rafael Dias - doisr.com.br  

## FORMULARIO ADAPTADO

### Arquivo Criado:
`application/views/imoveis/form_tabler.php`

### Funcionalidades:
- Cadastro de novo imóvel
- Edição de imóvel existente
- Busca de endereço por CEP
- Validação de campos obrigatórios
- Máscaras de entrada (CEP, preço, área)

---

## ESTRUTURA DO FORMULARIO

### 1. Buscar Endereço por CEP
**Card:** Busca automática de endereço

**Campos:**
- CEP (input com máscara)
- Botão "Buscar CEP"

**Funcionalidade:**
- Preenche automaticamente: Estado, Cidade, Bairro
- Integração com API ViaCEP

---

### 2. Localização
**Card:** Dados de localização do imóvel

**Campos:**
- Estado (UF) - Select obrigatório
- Cidade - Select obrigatório (carrega após selecionar estado)
- Bairro - Input obrigatório

**Validações:**
- Todos os campos são obrigatórios
- Estado e Cidade carregam via AJAX

---

### 3. Características do Imóvel
**Card:** Tipo e características

**Campos:**
- Tipo de Negócio - Select obrigatório (Venda/Aluguel)
- Tipo de Imóvel - Select obrigatório (Casa/Apartamento/Terreno/Comercial/Rural)
- Quantidade de Quartos - Select opcional (1-5+)
- Quantidade de Vagas - Select opcional (1-5+)

**Validações:**
- Tipo de Negócio e Tipo de Imóvel são obrigatórios

---

### 4. Valores
**Card:** Preço e área

**Campos:**
- Preço (R$) - Input obrigatório com máscara de moeda
- Área Privativa (m²) - Input obrigatório

**Máscaras:**
- Preço: R$ 0,00
- Área: Apenas números

---

### 5. Link do Imóvel
**Card:** URL externa (opcional)

**Campos:**
- URL do Imóvel - Input opcional (tipo URL)

**Descrição:**
- Link para página do imóvel no site do corretor
- Validação de formato de URL

---

### 6. Botões de Ação
**Card Footer:** Ações do formulário

**Botões:**
- Cancelar (link para /imoveis)
- Salvar (submit do formulário)

**Textos:**
- Novo: "Cadastrar Imóvel"
- Editar: "Atualizar Imóvel"

---

## LAYOUT TABLER

### Componentes Utilizados:

#### Cards:
```html
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Título</h3>
    </div>
    <div class="card-body">
        <!-- Campos -->
    </div>
</div>
```

#### Form Controls:
```html
<label class="form-label required">Campo</label>
<input type="text" class="form-control">
<small class="form-hint">Dica</small>
```

#### Grid System:
```html
<div class="row">
    <div class="col-md-6 mb-3">
        <!-- Campo -->
    </div>
</div>
```

---

## CONTROLLER ATUALIZADO

### Arquivo: `application/controllers/Imoveis.php`

### Métodos Modificados:

#### 1. novo()
**Linha 171:** Alterado de `imoveis/form` para `imoveis/form_tabler`

```php
$this->load->view('imoveis/form_tabler', $data);
```

#### 2. editar($id)
**Linha 278:** Alterado de `imoveis/form` para `imoveis/form_tabler`

```php
$this->load->view('imoveis/form_tabler', $data);
```

---

## RECURSOS DO FORMULARIO

### 1. Mensagens de Erro
- Alert danger para erros de validação
- Alert danger para erros de sistema
- Botão de fechar (dismissible)
- Ícones SVG do Tabler

### 2. Validações
- Campos obrigatórios marcados com asterisco vermelho
- Validação client-side (HTML5)
- Validação server-side (CodeIgniter)
- Mensagens de erro claras

### 3. UX/UI
- Layout responsivo (mobile-first)
- Cards organizados por seção
- Labels descritivos
- Form hints (dicas)
- Botões com ícones
- Cores semânticas

### 4. Acessibilidade
- Labels associados aos inputs
- Placeholders informativos
- Required indicators
- ARIA labels nos botões

---

## SCRIPTS NECESSARIOS

### JavaScript:
O formulário depende do arquivo `assets/js/imoveis-form.js` que deve conter:

1. **Busca de CEP:**
   - Integração com ViaCEP
   - Preenchimento automático dos campos
   - Loading state no botão

2. **Máscaras:**
   - CEP: 00000-000
   - Preço: R$ 0,00
   - Área: Apenas números

3. **Carregamento de Cidades:**
   - AJAX para buscar cidades do estado
   - Seleção automática ao editar

4. **Validações:**
   - Validação de campos obrigatórios
   - Validação de formato de URL
   - Feedback visual

---

## TESTES NECESSARIOS

### Cadastro:
- [x] Formulário carrega corretamente
- [ ] Busca de CEP funciona
- [ ] Estados carregam
- [ ] Cidades carregam após selecionar estado
- [ ] Máscaras aplicadas corretamente
- [ ] Validações funcionam
- [ ] Cadastro salva no banco
- [ ] Redirecionamento após salvar

### Edição:
- [x] Formulário carrega com dados
- [ ] Campos preenchidos corretamente
- [ ] Estado e cidade pré-selecionados
- [ ] Máscaras aplicadas nos valores
- [ ] Atualização salva no banco
- [ ] Redirecionamento após atualizar

### Responsividade:
- [ ] Layout mobile (< 768px)
- [ ] Layout tablet (768px - 1024px)
- [ ] Layout desktop (> 1024px)

---

## PROXIMOS PASSOS

1. **Testar formulário:**
   - Cadastro de novo imóvel
   - Edição de imóvel existente
   - Validações

2. **Adaptar página de visualização:**
   - `imoveis/ver.php` → `imoveis/ver_tabler.php`

3. **Verificar JavaScript:**
   - Confirmar se `assets/js/imoveis-form.js` existe
   - Adaptar para Tabler se necessário

4. **Melhorias futuras:**
   - Upload de imagens
   - Editor de descrição (WYSIWYG)
   - Mapa de localização
   - Galeria de fotos

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

## NOTAS IMPORTANTES

1. O formulário usa o mesmo controller e validações do formulário antigo
2. Apenas a view foi adaptada para o Tabler
3. JavaScript de máscaras e busca de CEP deve ser mantido
4. Validações server-side permanecem as mesmas
5. Layout responsivo e acessível
