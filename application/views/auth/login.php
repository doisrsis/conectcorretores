<?php $this->load->view('templates/header'); ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-50 via-white to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="<?php echo base_url(); ?>" class="inline-flex items-center space-x-2 mb-4">
                <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span class="text-3xl font-bold text-gray-900">ConectCorretores</span>
            </a>
            <p class="text-gray-600">Faça login para continuar</p>
        </div>

        <!-- Card de Login -->
        <div class="card">
            <!-- Mensagens de Feedback -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert-success">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert-error">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?php if (validation_errors()): ?>
                <div class="alert-error">
                    <?php echo validation_errors(); ?>
                </div>
            <?php endif; ?>

            <!-- Formulário -->
            <?php echo form_open('login', ['class' => 'space-y-6']); ?>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?php echo set_value('email'); ?>"
                            class="input pl-10" 
                            placeholder="seu@email.com"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <!-- Senha -->
                <div>
                    <label for="senha" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Senha
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="senha" 
                            name="senha" 
                            class="input pl-10" 
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <!-- Botão de Login -->
                <button type="submit" class="btn-primary w-full">
                    Entrar
                </button>

            <?php echo form_close(); ?>

            <!-- Link para Registro -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Não tem uma conta? 
                    <a href="<?php echo base_url('register'); ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                        Cadastre-se
                    </a>
                </p>
            </div>
        </div>

        <!-- Link para Home -->
        <div class="mt-6 text-center">
            <a href="<?php echo base_url(); ?>" class="text-gray-600 hover:text-gray-900 text-sm">
                ← Voltar para o início
            </a>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
