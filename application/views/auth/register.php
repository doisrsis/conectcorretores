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
            <p class="text-gray-600">Crie sua conta gratuitamente</p>
        </div>

        <!-- Card de Registro -->
        <div class="card">
            <!-- Mensagens de Feedback -->
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
            <?php echo form_open('register', ['class' => 'space-y-4']); ?>
                
                <!-- Nome -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nome Completo *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            value="<?php echo set_value('nome'); ?>"
                            class="input pl-10" 
                            placeholder="Seu nome completo"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email *
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
                        >
                    </div>
                </div>

                <!-- Telefone -->
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Telefone
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <input 
                            type="tel" 
                            id="telefone" 
                            name="telefone" 
                            value="<?php echo set_value('telefone'); ?>"
                            class="input pl-10" 
                            placeholder="(11) 98765-4321"
                        >
                    </div>
                </div>

                <!-- WhatsApp -->
                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1.5">
                        WhatsApp
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <input 
                            type="tel" 
                            id="whatsapp" 
                            name="whatsapp" 
                            value="<?php echo set_value('whatsapp'); ?>"
                            class="input pl-10" 
                            placeholder="(11) 98765-4321"
                        >
                    </div>
                </div>

                <!-- Senha -->
                <div>
                    <label for="senha" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Senha *
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
                    <p class="text-xs text-gray-500 mt-1">Mínimo de 6 caracteres</p>
                </div>

                <!-- Confirmar Senha -->
                <div>
                    <label for="senha_confirm" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Confirmar Senha *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="senha_confirm" 
                            name="senha_confirm" 
                            class="input pl-10" 
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <!-- Botão de Registro -->
                <button type="submit" class="btn-primary w-full mt-6">
                    Criar Conta
                </button>

            <?php echo form_close(); ?>

            <!-- Link para Login -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Já tem uma conta? 
                    <a href="<?php echo base_url('login'); ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                        Faça login
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
