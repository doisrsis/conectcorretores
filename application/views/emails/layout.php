<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_name; ?></title>
    <style>
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        
        /* Base */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
        }
        
        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        /* Header */
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
        }
        
        .email-logo {
            font-size: 32px;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: 1px;
        }
        
        /* Content */
        .email-content {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }
        
        .email-content h1 {
            color: #667eea;
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        
        .email-content h2 {
            color: #667eea;
            font-size: 20px;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        
        .email-content p {
            margin: 15px 0;
            font-size: 16px;
        }
        
        .email-content ul {
            margin: 15px 0;
            padding-left: 20px;
        }
        
        .email-content li {
            margin: 8px 0;
        }
        
        /* Button */
        .email-button {
            display: inline-block;
            padding: 15px 30px;
            margin: 25px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
        }
        
        .email-button:hover {
            opacity: 0.9;
        }
        
        /* Info Box */
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .info-box strong {
            color: #667eea;
        }
        
        /* Warning Box */
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
            color: #856404;
        }
        
        /* Success Box */
        .success-box {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
            color: #155724;
        }
        
        /* Divider */
        .divider {
            border-top: 1px solid #e0e0e0;
            margin: 30px 0;
        }
        
        /* Footer */
        .email-footer {
            background-color: #f8f9fa;
            padding: 30px 20px;
            text-align: center;
            color: #666666;
            font-size: 14px;
        }
        
        .email-footer p {
            margin: 10px 0;
        }
        
        .email-footer a {
            color: #667eea;
            text-decoration: none;
        }
        
        .email-footer a:hover {
            text-decoration: underline;
        }
        
        /* Social Links */
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #667eea;
            text-decoration: none;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            
            .email-content {
                padding: 20px 15px !important;
            }
            
            .email-button {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table class="email-container" role="presentation" cellspacing="0" cellpadding="0" border="0">
                    <!-- Header -->
                    <tr>
                        <td class="email-header">
                            <a href="<?php echo $site_url; ?>" class="email-logo">
                                <?php echo $site_name; ?>
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="email-content">
                            <?php echo $content; ?>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="email-footer">
                            <p><strong><?php echo $site_name; ?></strong></p>
                            <p>Plataforma para corretores de imóveis</p>
                            
                            <div class="divider" style="margin: 20px 40px;"></div>
                            
                            <p>
                                <a href="<?php echo $site_url; ?>">Acessar Plataforma</a> | 
                                <a href="<?php echo $site_url; ?>dashboard">Meu Painel</a> | 
                                <a href="<?php echo $site_url; ?>suporte">Suporte</a>
                            </p>
                            
                            <p style="font-size: 12px; color: #999999; margin-top: 20px;">
                                Este é um email automático, por favor não responda.<br>
                                Se tiver dúvidas, entre em contato através do nosso suporte.
                            </p>
                            
                            <p style="font-size: 12px; color: #999999;">
                                &copy; <?php echo $current_year; ?> <?php echo $site_name; ?>. Todos os direitos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
