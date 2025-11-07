<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .reset-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }
        .reset-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .reset-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .reset-header i {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        .reset-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
        }
        .reset-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        .reset-body {
            padding: 40px 30px;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px;
            margin-bottom: 20px;
        }
        .password-requirements {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .password-requirements h6 {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
            font-size: 0.85rem;
            color: #666;
        }
        .password-requirements li {
            margin-bottom: 5px;
        }
        .password-toggle {
            position: relative;
        }
        .password-toggle .toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 1.2rem;
        }
        .password-toggle .toggle-icon:hover {
            color: #667eea;
        }
        .user-greeting {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        .user-greeting i {
            color: #667eea;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .user-greeting p {
            margin: 0;
            font-weight: 500;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <!-- Header -->
            <div class="reset-header">
                <i class="bi bi-shield-lock-fill"></i>
                <h1>Redefinir Senha</h1>
                <p>Crie uma nova senha segura</p>
            </div>

            <!-- Body -->
            <div class="reset-body">
                <!-- Mensagens Flash -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Saudação ao Usuário -->
                <div class="user-greeting">
                    <i class="bi bi-person-circle"></i>
                    <p>Olá, <strong><?= $user_name ?></strong>!</p>
                </div>

                <!-- Requisitos de Senha -->
                <div class="password-requirements">
                    <h6><i class="bi bi-info-circle-fill me-2"></i>Requisitos da Senha:</h6>
                    <ul>
                        <li>Mínimo de 6 caracteres</li>
                        <li>Recomendado: letras, números e símbolos</li>
                        <li>Não use senhas óbvias ou fáceis</li>
                    </ul>
                </div>

                <!-- Formulário -->
                <form action="<?= base_url('password/update_password') ?>" method="POST" id="resetForm">
                    <input type="hidden" name="token" value="<?= $token ?>">

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock-fill me-2"></i>Nova Senha
                        </label>
                        <div class="password-toggle">
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password" 
                                name="password" 
                                placeholder="Digite sua nova senha"
                                required
                                minlength="6"
                                autofocus
                            >
                            <i class="bi bi-eye-fill toggle-icon" id="togglePassword"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirm" class="form-label">
                            <i class="bi bi-lock-fill me-2"></i>Confirmar Nova Senha
                        </label>
                        <div class="password-toggle">
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password_confirm" 
                                name="password_confirm" 
                                placeholder="Digite novamente sua nova senha"
                                required
                                minlength="6"
                            >
                            <i class="bi bi-eye-fill toggle-icon" id="togglePasswordConfirm"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle-fill me-2"></i>Redefinir Senha
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <p class="text-white mb-0">
                <small>© <?= date('Y') ?> ConectCorretores - Todos os direitos reservados</small>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this;
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye-fill');
                icon.classList.add('bi-eye-slash-fill');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye-fill');
            }
        });

        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const password = document.getElementById('password_confirm');
            const icon = this;
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye-fill');
                icon.classList.add('bi-eye-slash-fill');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash-fill');
                icon.classList.add('bi-eye-fill');
            }
        });

        // Validar senhas iguais
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;

            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('As senhas não coincidem!');
                return false;
            }
        });
    </script>
</body>
</html>
