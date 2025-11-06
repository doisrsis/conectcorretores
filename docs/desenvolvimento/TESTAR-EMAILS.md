# 🧪 Como Testar Sistema de Emails

**Autor:** Rafael Dias - doisr.com.br  
**Data:** 06/11/2025

---

## ✅ Pré-requisitos

- [x] SMTP configurado em `config/email.php`
- [x] Credenciais adicionadas (Gmail ou SendGrid)
- [x] XAMPP rodando

---

## 🚀 Passo a Passo

### **1. Configurar Email de Teste**

Editar: `application/controllers/Test_email.php`

Linha 198:
```php
$user->email = 'seu-email-de-teste@gmail.com'; // ⚠️ ALTERAR AQUI!
```

Trocar por seu email real onde quer receber os testes.

---

### **2. Acessar Página de Testes**

Abrir navegador:
```
http://localhost/conectcorretores/test_email
```

Você verá uma lista com 10 tipos de emails para testar.

---

### **3. Testar Cada Email**

Clicar em cada link para testar:

1. **Email de Boas-Vindas**
   - Testa template de cadastro
   - Deve chegar em segundos

2. **Assinatura Ativada**
   - Testa confirmação de plano
   - Mostra detalhes da assinatura

3. **Pagamento Confirmado**
   - Testa recibo de pagamento
   - Mostra valor e data

4. **Lembrete de Renovação**
   - Testa aviso 7 dias antes
   - Mostra dias restantes

5. **Falha no Pagamento**
   - Testa alerta de problema
   - Mostra instruções

6. **Plano Vencido**
   - Testa notificação de expiração
   - Link para renovar

7. **Upgrade Confirmado**
   - Testa mudança para plano maior
   - Mostra diferenças

8. **Downgrade Confirmado**
   - Testa mudança para plano menor
   - Avisa sobre limites

9. **Cancelamento Confirmado**
   - Testa confirmação de cancelamento
   - Pede feedback

10. **Recuperação de Senha**
    - Testa link de reset
    - Mostra validade

---

## ✅ Resultado Esperado

### **Sucesso:**
```
✅ Email Enviado com Sucesso!
Verifique sua caixa de entrada.
```

### **Erro:**
```
❌ Erro ao Enviar Email
Verifique as configurações SMTP.

Debug: [detalhes do erro]
```

---

## 🔍 Verificar Email Recebido

### **Checklist:**
- [ ] Email chegou na caixa de entrada
- [ ] Não caiu em spam
- [ ] Layout está correto
- [ ] Imagens carregaram
- [ ] Links funcionam
- [ ] Responsivo no celular
- [ ] Texto legível
- [ ] Cores corretas

---

## 🐛 Troubleshooting

### **Erro: "SMTP connect() failed"**
**Causa:** Credenciais incorretas ou porta bloqueada  
**Solução:**
- Verificar email e senha de app
- Verificar se porta 587 está aberta
- Testar com outro email

### **Erro: "Could not authenticate"**
**Causa:** Senha de app incorreta  
**Solução:**
- Gerar nova senha de app no Google
- Copiar sem espaços
- Verificar se verificação em 2 etapas está ativa

### **Email cai em spam**
**Causa:** Falta de autenticação SPF/DKIM  
**Solução:**
- Usar SendGrid em produção
- Configurar SPF/DKIM no domínio
- Evitar palavras de spam no assunto

### **Email não chega**
**Causa:** Bloqueio do provedor  
**Solução:**
- Verificar logs do servidor
- Testar com outro email
- Verificar limite de envios do Gmail

---

## 📊 Limites de Envio

### **Gmail:**
- 500 emails/dia
- 100 destinatários/email
- Bom para desenvolvimento

### **SendGrid (Grátis):**
- 100 emails/dia
- Ilimitado destinatários
- Melhor entregabilidade

### **SendGrid (Pago):**
- A partir de 40.000 emails/mês
- $19.95/mês
- Analytics completo

---

## 🔐 Segurança

### **⚠️ IMPORTANTE:**

1. **Nunca commitar credenciais:**
   ```
   # Adicionar ao .gitignore:
   application/config/email.php
   ```

2. **Usar variáveis de ambiente em produção:**
   ```php
   $config['smtp_user'] = getenv('SMTP_USER');
   $config['smtp_pass'] = getenv('SMTP_PASS');
   ```

3. **Deletar Test_email.php em produção:**
   ```
   application/controllers/Test_email.php
   ```

---

## 📝 Próximos Passos

Após testar todos os emails:

1. ✅ Integrar com cadastro de usuário
2. ✅ Integrar com webhooks do Stripe
3. ✅ Integrar com upgrade/downgrade
4. ✅ Integrar com cancelamento
5. ✅ Testar fluxo completo
6. ✅ Configurar SendGrid para produção

---

## 📚 Referências

- [Gmail App Passwords](https://support.google.com/accounts/answer/185833)
- [SendGrid Setup](https://docs.sendgrid.com/for-developers/sending-email/getting-started-smtp)
- [Email Testing Best Practices](https://www.emailonacid.com/blog/article/email-development/email-testing-best-practices/)

---

**Testes essenciais para garantir que emails funcionam! 🧪**

Para suporte: Rafael Dias - doisr.com.br
