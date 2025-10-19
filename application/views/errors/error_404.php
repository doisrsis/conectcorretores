<?php $this->load->view('templates/header'); ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <!-- Ícone 404 -->
        <div class="mb-8">
            <svg class="w-32 h-32 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <!-- Título -->
        <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
        
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">
            <?php echo isset($heading) ? $heading : 'Página não encontrada'; ?>
        </h2>
        
        <p class="text-gray-600 mb-8">
            <?php echo isset($message) ? $message : 'A página que você está procurando não existe.'; ?>
        </p>

        <!-- Botões -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?php echo base_url(); ?>" class="btn-primary px-6 py-3">
                Voltar para Início
            </a>
            <a href="javascript:history.back()" class="btn-outline px-6 py-3">
                Voltar
            </a>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
