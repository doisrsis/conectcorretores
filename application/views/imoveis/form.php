<?php
/**
 * Formulário de Imóveis - Versão Simplificada
 * Autor: Rafael Dias - doisr.com.br
 * Data: 18/10/2025
 */
$this->load->view('templates/dashboard_header');
$this->load->view('templates/sidebar');
?>

<!-- Main Content -->
<div class="lg:pl-64 min-h-screen flex flex-col">
    <!-- Top Bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="flex items-center justify-between px-4 py-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <h1 class="text-xl font-semibold text-gray-900">
                <?php echo isset($imovel) ? 'Editar Imóvel' : 'Cadastrar Imóvel'; ?>
            </h1>

            <a href="<?php echo base_url('imoveis'); ?>" class="text-gray-600 hover:text-gray-900">
                ← Voltar
            </a>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 p-4 lg:p-8">
        <div class="max-w-4xl mx-auto">

            <!-- Mensagens -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert-error mb-6">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <?php if (validation_errors()): ?>
                <div class="alert-error mb-6">
                    <?php echo validation_errors(); ?>
                </div>
            <?php endif; ?>

            <!-- Formulário -->
            <div class="bg-white rounded-xl shadow-lg p-6 lg:p-8 border border-gray-100">
                <?php echo form_open(isset($imovel) ? 'imoveis/editar/' . $imovel->id : 'imoveis/novo', ['class' => 'space-y-6', 'id' => 'form-imovel']); ?>

                    <!-- CEP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            CEP
                        </label>
                        <div class="flex gap-2">
                            <input type="text"
                                   id="cep"
                                   name="cep"
                                   value="<?php echo set_value('cep', isset($imovel) ? $imovel->cep : ''); ?>"
                                   class="input flex-1"
                                   placeholder="00000-000"
                                   maxlength="9">
                            <button type="button"
                                    id="btn-buscar-cep"
                                    class="btn-primary px-6">
                                🔍 Buscar
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Digite o CEP e clique em Buscar para preencher automaticamente</p>
                    </div>

                    <!-- Localização -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Localização</h3>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- UF -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    UF (Estado) *
                                </label>
                                <select id="estado_id"
                                        name="estado_id"
                                        class="input"
                                        required>
                                    <option value="">Selecione...</option>
                                </select>
                                <input type="hidden" id="estado_uf" value="<?php echo isset($imovel) ? $imovel->estado : ''; ?>">
                            </div>

                            <!-- Cidade -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Cidade *
                                </label>
                                <select id="cidade_id"
                                        name="cidade_id"
                                        class="input"
                                        required
                                        disabled>
                                    <option value="">Selecione o estado primeiro</option>
                                </select>
                                <input type="hidden" id="cidade_nome" value="<?php echo isset($imovel) ? $imovel->cidade : ''; ?>">
                            </div>
                        </div>

                        <!-- Bairro -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Bairro *
                            </label>
                            <input type="text"
                                   id="bairro"
                                   name="bairro"
                                   value="<?php echo set_value('bairro', isset($imovel) ? $imovel->bairro : ''); ?>"
                                   class="input"
                                   required>
                        </div>
                    </div>

                    <!-- Tipo de Negócio e Tipo de Imóvel -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Imóvel</h3>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Tipo de Negócio -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tipo de Negócio *
                                </label>
                                <select name="tipo_negocio" class="input" required>
                                    <option value="">Selecione...</option>
                                    <option value="compra" <?php echo set_select('tipo_negocio', 'compra', isset($imovel) && $imovel->tipo_negocio === 'compra'); ?>>Compra</option>
                                    <option value="aluguel" <?php echo set_select('tipo_negocio', 'aluguel', isset($imovel) && $imovel->tipo_negocio === 'aluguel'); ?>>Aluguel</option>
                                </select>
                            </div>

                            <!-- Tipo de Imóvel -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tipo de Imóvel *
                                </label>
                                <select name="tipo_imovel" class="input" required>
                                    <option value="">Selecione...</option>
                                    <option value="Apartamento" <?php echo set_select('tipo_imovel', 'Apartamento', isset($imovel) && $imovel->tipo_imovel === 'Apartamento'); ?>>Apartamento</option>
                                    <option value="Casa" <?php echo set_select('tipo_imovel', 'Casa', isset($imovel) && $imovel->tipo_imovel === 'Casa'); ?>>Casa</option>
                                    <option value="Condomínio" <?php echo set_select('tipo_imovel', 'Condomínio', isset($imovel) && $imovel->tipo_imovel === 'Condomínio'); ?>>Condomínio</option>
                                    <option value="Terreno" <?php echo set_select('tipo_imovel', 'Terreno', isset($imovel) && $imovel->tipo_imovel === 'Terreno'); ?>>Terreno</option>
                                    <option value="Comercial" <?php echo set_select('tipo_imovel', 'Comercial', isset($imovel) && $imovel->tipo_imovel === 'Comercial'); ?>>Comercial</option>
                                    <option value="Fazenda" <?php echo set_select('tipo_imovel', 'Fazenda', isset($imovel) && $imovel->tipo_imovel === 'Fazenda'); ?>>Fazenda</option>
                                    <option value="Sítio" <?php echo set_select('tipo_imovel', 'Sítio', isset($imovel) && $imovel->tipo_imovel === 'Sítio'); ?>>Sítio</option>
                                    <option value="Outros" <?php echo set_select('tipo_imovel', 'Outros', isset($imovel) && $imovel->tipo_imovel === 'Outros'); ?>>Outros</option>
                                </select>
                            </div>
                        </div>

                        <!-- Quartos e Vagas -->
                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <!-- Quartos -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Quantidade de Quartos
                                </label>
                                <select name="quartos" class="input">
                                    <option value="">Selecione...</option>
                                    <option value="1" <?php echo set_select('quartos', '1', isset($imovel) && $imovel->quartos == 1); ?>>1</option>
                                    <option value="2" <?php echo set_select('quartos', '2', isset($imovel) && $imovel->quartos == 2); ?>>2</option>
                                    <option value="3" <?php echo set_select('quartos', '3', isset($imovel) && $imovel->quartos == 3); ?>>3</option>
                                    <option value="4" <?php echo set_select('quartos', '4', isset($imovel) && $imovel->quartos == 4); ?>>4</option>
                                    <option value="5" <?php echo set_select('quartos', '5', isset($imovel) && $imovel->quartos >= 5); ?>>5 ou mais</option>
                                </select>
                            </div>

                            <!-- Vagas -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Quantidade de Vagas
                                </label>
                                <select name="vagas" class="input">
                                    <option value="">Selecione...</option>
                                    <option value="1" <?php echo set_select('vagas', '1', isset($imovel) && $imovel->vagas == 1); ?>>1</option>
                                    <option value="2" <?php echo set_select('vagas', '2', isset($imovel) && $imovel->vagas == 2); ?>>2</option>
                                    <option value="3" <?php echo set_select('vagas', '3', isset($imovel) && $imovel->vagas == 3); ?>>3</option>
                                    <option value="4" <?php echo set_select('vagas', '4', isset($imovel) && $imovel->vagas == 4); ?>>4</option>
                                    <option value="5" <?php echo set_select('vagas', '5', isset($imovel) && $imovel->vagas >= 5); ?>>5 ou mais</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Valores -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Valores</h3>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Preço -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Preço (R$) *
                                </label>
                                <input type="text"
                                       id="preco"
                                       name="preco"
                                       value="<?php echo set_value('preco', isset($imovel) ? number_format($imovel->preco, 2, ',', '.') : ''); ?>"
                                       class="input"
                                       placeholder="R$ 0,00"
                                       required>
                            </div>

                            <!-- Área Privativa -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Área Privativa (m²) *
                                </label>
                                <input type="text"
                                       id="area_privativa"
                                       name="area_privativa"
                                       value="<?php echo set_value('area_privativa', isset($imovel) ? $imovel->area_privativa : ''); ?>"
                                       class="input"
                                       placeholder="90"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Link do Imóvel -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Link do Imóvel</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Link para a página do imóvel no seu site (opcional)
                        </p>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                URL do Imóvel <span class="text-gray-400 font-normal">(Opcional)</span>
                            </label>
                            <input type="url"
                                   name="link_imovel"
                                   value="<?php echo set_value('link_imovel', isset($imovel) ? $imovel->link_imovel : ''); ?>"
                                   class="input"
                                   placeholder="https://seusite.com.br/imovel/123">
                            <p class="text-xs text-gray-500 mt-1">
                                💡 Insira o link da página do imóvel no seu site com todos os detalhes
                            </p>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-4 pt-6 border-t">
                        <a href="<?php echo base_url('imoveis'); ?>" class="btn-secondary flex-1 text-center">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary flex-1">
                            <?php echo isset($imovel) ? 'Atualizar Imóvel' : 'Cadastrar Imóvel'; ?>
                        </button>
                    </div>

                <?php echo form_close(); ?>
            </div>

        </div>
    </main>

    <?php $this->load->view('templates/footer'); ?>
</div>

<!-- IMask.js CDN -->
<script src="https://unpkg.com/imask"></script>

<!-- Script do Formulário -->
<script>
const baseUrl = '<?php echo base_url(); ?>';
const isEdit = <?php echo isset($imovel) ? 'true' : 'false'; ?>;
const imovelData = <?php echo isset($imovel) ? json_encode($imovel) : 'null'; ?>;
</script>
<script src="<?php echo base_url('assets/js/imoveis-form.js'); ?>"></script>

</body>
</html>
