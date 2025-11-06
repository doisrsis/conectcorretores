<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Configurações de Email - ConectCorretores
 *
 * Autor: Rafael Dias - doisr.com.br
 * Data: 06/11/2025
 */

// Protocolo de envio
$config['email_protocol'] = 'smtp';

// Configurações SMTP
$config['smtp_host'] = 'smtp.gmail.com';
$config['smtp_port'] = 587;
$config['smtp_crypto'] = 'tls';

// Credenciais SMTP
// ⚠️ CONFIGURAR: Usar senha de app do Gmail ou credenciais de outro provedor
$config['smtp_user'] = ''; // seu-email@gmail.com
$config['smtp_pass'] = ''; // senha-de-app-do-gmail

// Configurações de email
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";
$config['wordwrap'] = TRUE;

// Remetente padrão
$config['from_email'] = 'noreply@conectcorretores.com.br';
$config['from_name'] = 'ConectCorretores';

// URLs do sistema (para links nos emails)
$config['site_url'] = base_url();
$config['site_name'] = 'ConectCorretores';

// Configurações de desenvolvimento
$config['email_debug'] = ENVIRONMENT === 'development'; // TRUE em desenvolvimento
$config['email_log'] = TRUE; // Salvar log de emails enviados

/**
 * INSTRUÇÕES PARA CONFIGURAR GMAIL:
 *
 * 1. Acessar: https://myaccount.google.com/security
 * 2. Ativar "Verificação em duas etapas"
 * 3. Acessar: https://myaccount.google.com/apppasswords
 * 4. Criar senha de app para "Email"
 * 5. Copiar a senha gerada (16 caracteres)
 * 6. Adicionar em $config['smtp_user'] e $config['smtp_pass']
 *
 * ALTERNATIVAS:
 * - SendGrid: smtp.sendgrid.net (porta 587)
 * - Mailgun: smtp.mailgun.org (porta 587)
 * - Amazon SES: email-smtp.us-east-1.amazonaws.com (porta 587)
 */
