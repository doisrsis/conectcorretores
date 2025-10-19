# 📦 Instalação da Versão 1.1 - CRUD Simplificado de Imóveis

> **Autor:** Rafael Dias - doisr.com.br  
> **Data:** 18/10/2025  
> **Versão:** 1.1.0

---

## 📋 O que mudou?

### ✅ Melhorias Implementadas:

1. **Formulário Simplificado** - Apenas campos essenciais
2. **Integração ViaCEP** - Busca automática de endereço por CEP
3. **Estados e Cidades no Banco** - Performance e confiabilidade
4. **Máscaras Automáticas** - R$, telefone e CEP
5. **Cálculo Automático** - Valor por m² calculado em tempo real
6. **UX Melhorada** - Interface mais intuitiva

### 📊 Campos do Novo Formulário:

| Campo | Tipo | Obrigatório |
|-------|------|-------------|
| CEP | Input com máscara | Não |
| UF (Estado) | Select | Sim |
| Cidade | Select dinâmico | Sim |
| Bairro | Input | Sim |
| Tipo de Negócio | Select (Compra/Aluguel) | Sim |
| Tipo de Imóvel | Select (8 opções) | Sim |
| Quartos | Select (1-5+) | Sim |
| Vagas | Select (1-5+) | Sim |
| Preço | Input com máscara R$ | Sim |
| Área Privativa | Number (m²) | Sim |
| Valor m² | Calculado automaticamente | - |
| Link do Site | URL | Não |
| Telefone | Input com máscara | Não |
| WhatsApp | Input com máscara | Não |

### ❌ Campos Removidos:

- Endereço completo, número, complemento
- Suítes, banheiros
- Área total
- Descrição
- Características
- Condomínio, IPTU
- Upload de imagens

---

## 🚀 Passo a Passo de Instalação

### 1️⃣ Backup do Banco de Dados

**IMPORTANTE:** Faça backup antes de aplicar a migration!

```bash
# Via linha de comando
mysqldump -u root -p corretor_saas > backup_antes_v1.1.sql

# Ou use o phpMyAdmin para exportar
```

### 2️⃣ Aplicar Migration no Banco

```bash
# Navegar até a pasta do projeto
cd c:\xampp\htdocs\conectcorretores

# Aplicar migration
mysql -u root -p corretor_saas < database/migration_v1.1.sql
```

**Ou via phpMyAdmin:**
1. Acesse: http://localhost/phpmyadmin
2. Selecione o banco `corretor_saas`
3. Clique em "SQL"
4. Cole o conteúdo de `database/migration_v1.1.sql`
5. Clique em "Executar"

### 3️⃣ Verificar Instalação

Execute estas queries para verificar:

```sql
-- Verificar se tabelas foram criadas
SHOW TABLES LIKE 'estados';
SHOW TABLES LIKE 'cidades';

-- Verificar se estados foram populados (deve retornar 27)
SELECT COUNT(*) FROM estados;

-- Verificar estrutura da tabela imoveis
DESCRIBE imoveis;

-- Verificar se colunas foram adicionadas
SHOW COLUMNS FROM imoveis LIKE 'cep';
SHOW COLUMNS FROM imoveis LIKE 'estado_id';
SHOW COLUMNS FROM imoveis LIKE 'cidade_id';
```

### 4️⃣ Verificar Arquivos Criados

Confirme que estes arquivos existem:

```
✅ database/migration_v1.1.sql
✅ application/models/Estado_model.php
✅ application/models/Cidade_model.php
✅ application/controllers/Imoveis.php (atualizado)
✅ application/views/imoveis/form.php (novo)
✅ assets/js/imoveis-form.js
```

### 5️⃣ Testar Funcionalidades

1. **Acessar formulário:**
   ```
   http://localhost/conectcorretores/imoveis/novo
   ```

2. **Testar busca de CEP:**
   - Digite um CEP válido (ex: 01310-100)
   - Clique em "Buscar"
   - Verifique se UF, Cidade e Bairro foram preenchidos

3. **Testar seleção manual:**
   - Selecione um Estado
   - Verifique se as cidades carregam
   - Selecione uma Cidade

4. **Testar máscaras:**
   - Digite um preço → deve formatar como R$ 1.000,00
   - Digite um telefone → deve formatar como (11) 9 1234-5678
   - Digite um CEP → deve formatar como 12345-678

5. **Testar cálculo automático:**
   - Digite Preço: R$ 500.000,00
   - Digite Área: 100
   - Valor m² deve mostrar: R$ 5.000,00

6. **Cadastrar um imóvel:**
   - Preencha todos os campos obrigatórios
   - Clique em "Cadastrar Imóvel"
   - Verifique se foi salvo corretamente

---

## 🔧 Solução de Problemas

### Erro: "Table 'estados' doesn't exist"

**Solução:** A migration não foi aplicada corretamente.
```bash
mysql -u root -p corretor_saas < database/migration_v1.1.sql
```

### Erro: "Call to undefined method Estado_model"

**Solução:** Limpar cache do CodeIgniter
```bash
# Deletar arquivos de cache
del c:\xampp\htdocs\conectcorretores\application\cache\*
```

### CEP não retorna dados

**Possíveis causas:**
1. ✅ Verificar se cURL está habilitado no PHP
2. ✅ Verificar conexão com internet
3. ✅ Testar API manualmente: https://viacep.com.br/ws/01310100/json/

### Cidades não carregam

**Solução:** Verificar no console do navegador (F12) se há erros JavaScript.

### Máscaras não funcionam

**Solução:** Verificar se IMask.js está carregando:
```html
<!-- Deve estar no form.php -->
<script src="https://unpkg.com/imask"></script>
```

---

## 📊 Estrutura do Banco Após Migration

### Novas Tabelas:

```sql
estados (27 registros)
├── id
├── uf
└── nome

cidades (vazio inicialmente, popula via ViaCEP)
├── id
├── estado_id (FK)
├── nome
├── ibge_code
└── created_at
```

### Tabela `imoveis` Atualizada:

```sql
Campos ADICIONADOS:
├── cep
├── estado_id (FK)
├── cidade_id (FK)
├── link
└── whatsapp

Campos REMOVIDOS:
├── endereco
├── numero
├── complemento
├── suites
├── banheiros
├── area_total
├── condominio
├── iptu
├── caracteristicas
└── imagens
```

---

## 🎯 Endpoints AJAX Criados

| Endpoint | Método | Descrição |
|----------|--------|-----------|
| `/imoveis/buscar_cep` | POST | Busca CEP via ViaCEP |
| `/imoveis/get_estados` | POST | Lista todos os estados |
| `/imoveis/get_cidades` | POST | Lista cidades por estado |

---

## 📝 Checklist de Instalação

- [ ] Backup do banco de dados criado
- [ ] Migration aplicada com sucesso
- [ ] 27 estados cadastrados
- [ ] Tabelas `estados` e `cidades` criadas
- [ ] Coluna `estado_id` adicionada em `imoveis`
- [ ] Coluna `cidade_id` adicionada em `imoveis`
- [ ] Models `Estado_model` e `Cidade_model` criados
- [ ] Controller `Imoveis` atualizado
- [ ] Formulário novo funcionando
- [ ] JavaScript carregando corretamente
- [ ] Máscaras funcionando (R$, telefone, CEP)
- [ ] Busca de CEP funcionando
- [ ] Seleção de estados funcionando
- [ ] Cidades carregando dinamicamente
- [ ] Cálculo de valor/m² automático
- [ ] Cadastro de imóvel funcionando
- [ ] Edição de imóvel funcionando

---

## 🔄 Rollback (Reverter Alterações)

Se precisar voltar para a versão anterior:

```bash
# Restaurar backup
mysql -u root -p corretor_saas < backup_antes_v1.1.sql

# Restaurar formulário antigo
copy application\views\imoveis\form.php.backup application\views\imoveis\form.php
```

---

## 📞 Suporte

Em caso de dúvidas ou problemas:

1. Verifique os logs do PHP: `c:\xampp\php\logs\php_error_log`
2. Verifique o console do navegador (F12)
3. Verifique os logs do Apache: `c:\xampp\apache\logs\error.log`

---

## ✅ Conclusão

Após seguir todos os passos, você terá:

- ✅ Formulário simplificado e intuitivo
- ✅ Integração com ViaCEP
- ✅ Estados e cidades no banco
- ✅ Máscaras automáticas
- ✅ Cálculo automático de valor/m²
- ✅ Melhor experiência do usuário

**Versão instalada:** v1.1.0  
**Data:** 18/10/2025  
**Desenvolvido por:** Rafael Dias - doisr.com.br

---

🎉 **Instalação Concluída!**
