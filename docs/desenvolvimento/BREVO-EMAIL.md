# 📧 CONFIGURAÇÃO BREVO (Sendinblue)

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 08/11/2025  
**Projeto:** ConectCorretores

---

## 🎯 O QUE É O BREVO?

O **Brevo** (antigo Sendinblue) é uma plataforma profissional de envio de emails transacionais e marketing.

### **Vantagens:**
- ✅ **Alta taxa de entrega** - Emails não caem em spam
- ✅ **300 emails/dia grátis** - Plano gratuito generoso
- ✅ **Estatísticas completas** - Acompanhe aberturas, cliques, bounces
- ✅ **Templates profissionais** - Editor visual de emails
- ✅ **API completa** - Integração avançada
- ✅ **Suporte em português** - Documentação e suporte

---

## ⚙️ CONFIGURAÇÃO SMTP

### **Como Obter Credenciais:**

1. Acesse: https://app.brevo.com/settings/keys/smtp
2. Clique em **"Generate a new SMTP key"**
3. Copie o login e a chave gerada
4. Configure em `application/config/email.php`

```php
Servidor: smtp-relay.brevo.com
Porta: 587
Criptografia: TLS
Login: [seu-id]@smtp-brevo.com
Senha: [sua-chave-smtp]
```

### **Arquivo de Configuração:**

`application/config/email.php`:

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Protocolo de envio
$config['email_protocol'] = 'smtp';

// Configurações SMTP - BREVO
$config['smtp_host'] = 'smtp-relay.brevo.com';
$config['smtp_port'] = 587;
$config['smtp_crypto'] = 'tls';

// Credenciais SMTP
$config['smtp_user'] = 'seu-id@smtp-brevo.com';
$config['smtp_pass'] = 'sua-chave-smtp-brevo';

// Configurações de email
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config['wordwrap'] = TRUE;

// Remetente padrão
$config['from_email'] = 'noreply@conectcorretores.com.br';
$config['from_name'] = 'ConectCorretores';
```

---

## 🔐 SEGURANÇA

### **Chave SMTP:**
A chave SMTP é como uma senha de aplicativo. Ela permite enviar emails sem expor sua senha principal.

### **Gerenciar Chaves:**
1. Acesse: https://app.brevo.com/settings/keys/smtp
2. Você pode criar múltiplas chaves
3. Pode revogar chaves comprometidas
4. Cada chave tem estatísticas separadas

### **⚠️ NUNCA COMMITAR:**
```gitignore
application/config/email.php
```

---

## 📊 MONITORAMENTO

### **Painel Brevo:**
https://app.brevo.com/

### **Estatísticas Disponíveis:**
- 📨 **Emails enviados** - Total de envios
- ✅ **Taxa de entrega** - Emails que chegaram
- 📬 **Taxa de abertura** - Quantos abriram
- 🖱️ **Taxa de cliques** - Quantos clicaram em links
- ⚠️ **Bounces** - Emails que retornaram
- 🚫 **Spam** - Marcados como spam
- ❌ **Unsubscribes** - Descadastramentos

### **Logs em Tempo Real:**
- Ver cada email enviado
- Status de entrega
- Horário de abertura
- Cliques em links
- Dispositivo usado

---

## 📈 LIMITES DO PLANO GRATUITO

### **Plano Free:**
- ✅ **300 emails/dia**
- ✅ **Ilimitados contatos**
- ✅ **Templates ilimitados**
- ✅ **Estatísticas completas**
- ✅ **Suporte por email**

### **Quando Fazer Upgrade:**
- Se enviar mais de 300 emails/dia
- Se precisar de suporte prioritário
- Se quiser remover marca Brevo dos emails

### **Planos Pagos:**
- **Starter:** R$ 125/mês - 20.000 emails/mês
- **Business:** R$ 325/mês - 100.000 emails/mês
- **Enterprise:** Customizado

---

## 🧪 TESTAR ENVIO

### **Via Controller de Teste:**
```
http://localhost/conectcorretores/test_email
https://conectcorretores.doisr.com.br/test_email
```

### **Testes Disponíveis:**
1. Email de Boas-Vindas
2. Assinatura Ativada
3. Pagamento Confirmado
4. Lembrete de Renovação
5. Falha no Pagamento
6. Plano Vencido
7. Upgrade Confirmado
8. Downgrade Confirmado
9. Cancelamento Confirmado
10. Recuperação de Senha

---

## 🎨 TEMPLATES

### **Criar Template no Brevo:**

1. Acesse: https://app.brevo.com/camp/lists/template
2. Clique em **"Create a template"**
3. Use o editor visual
4. Salve o template

### **Usar Template no Código:**

```php
// Enviar com template do Brevo
$this->load->library('email');

$config['protocol'] = 'smtp';
// ... configurações SMTP

$this->email->initialize($config);
$this->email->from('noreply@conectcorretores.com.br', 'ConectCorretores');
$this->email->to('usuario@email.com');

// Usar template HTML personalizado
$data = [
    'nome' => 'João Silva',
    'plano' => 'Profissional'
];

$mensagem = $this->load->view('emails/boas_vindas', $data, TRUE);
$this->email->message($mensagem);

$this->email->send();
```

---

## 🔧 TROUBLESHOOTING

### **Erro: "Failed to authenticate"**
**Causa:** Chave SMTP incorreta  
**Solução:** Verificar se a chave está correta em `email.php`

### **Erro: "Connection timeout"**
**Causa:** Firewall bloqueando porta 587  
**Solução:** Verificar firewall do servidor

### **Emails caindo em spam:**
**Causa:** Domínio sem autenticação SPF/DKIM  
**Solução:** Configurar SPF e DKIM no DNS

### **Limite de 300 emails atingido:**
**Causa:** Plano gratuito tem limite diário  
**Solução:** Fazer upgrade ou esperar 24h

---

## 🌐 CONFIGURAR SPF E DKIM

### **O Que São?**
- **SPF:** Autoriza servidores a enviar emails pelo seu domínio
- **DKIM:** Assina digitalmente os emails para provar autenticidade

### **Configurar no DNS:**

1. Acesse: https://app.brevo.com/settings/domains
2. Adicione seu domínio: `conectcorretores.com.br`
3. Copie os registros DNS fornecidos
4. Adicione no painel da ValueServer:

**Registro SPF (TXT):**
```
v=spf1 include:spf.brevo.com ~all
```

**Registro DKIM (TXT):**
```
Nome: mail._domainkey
Valor: [copiar do painel Brevo]
```

5. Aguarde propagação (até 48h)
6. Verifique no painel Brevo

### **Benefícios:**
- ✅ Emails não caem em spam
- ✅ Maior taxa de entrega
- ✅ Confiança dos provedores
- ✅ Estatísticas mais precisas

---

## 📞 SUPORTE

### **Documentação Oficial:**
- https://help.brevo.com/
- https://developers.brevo.com/

### **Suporte Brevo:**
- Email: support@brevo.com
- Chat: Disponível no painel

### **Desenvolvedor:**
- Rafael Dias - doisr.com.br
- Email: doisr.sistemas@gmail.com

---

## 📝 CHECKLIST DE CONFIGURAÇÃO

- [ ] Criar conta no Brevo
- [ ] Gerar chave SMTP
- [ ] Configurar `email.php` com credenciais
- [ ] Testar envio local
- [ ] Testar envio em produção
- [ ] Configurar SPF no DNS
- [ ] Configurar DKIM no DNS
- [ ] Verificar domínio no Brevo
- [ ] Criar templates de email
- [ ] Monitorar estatísticas

---

## 🚀 PRÓXIMOS PASSOS

1. **Configurar SPF/DKIM** - Melhorar deliverability
2. **Criar templates** - Emails mais bonitos
3. **Configurar webhooks** - Receber eventos em tempo real
4. **Integrar API** - Funcionalidades avançadas
5. **Monitorar métricas** - Otimizar taxa de abertura

---

**Última atualização:** 08/11/2025
