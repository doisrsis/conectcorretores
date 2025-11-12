<?php
/**
 * Admin - Editar Plano (Tabler)
 * 
 * @author Rafael Dias - doisr.com.br (12/11/2025 00:20)
 */

$this->load->view('templates/tabler/header');
$this->load->view('templates/tabler/navbar');
$this->load->view('templates/tabler/sidebar');
?>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Admin</div>
                    <h2 class="page-title">Editar Plano</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="<?php echo base_url('admin/planos'); ?>" class="btn btn-outline-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-lg-8 mx-auto">
                    
                    <!-- Stripe Info -->
                    <?php if ($plan->stripe_product_id): ?>
                    <div class="alert alert-info mb-3">
                        <div class="d-flex">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                            </div>
                            <div>
                                <h4 class="alert-title">Sincronizado com Stripe</h4>
                                <div class="text-muted">
                                    <strong>Product ID:</strong> <?php echo $plan->stripe_product_id; ?><br>
                                    <strong>Price ID:</strong> <?php echo $plan->stripe_price_id; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Form Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informações do Plano</h3>
                            <div class="card-subtitle">As alterações serão sincronizadas com o Stripe</div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="<?php echo base_url('admin/planos_editar/' . $plan->id); ?>">
                                
                                <!-- Nome -->
                                <div class="mb-3">
                                    <label class="form-label required">Nome do Plano</label>
                                    <input type="text" name="nome" class="form-control" value="<?php echo $plan->nome; ?>" required placeholder="Ex: Plano Básico">
                                    <small class="form-hint">Nome que aparecerá para os clientes</small>
                                </div>

                                <!-- Descrição -->
                                <div class="mb-3">
                                    <label class="form-label">Descrição</label>
                                    <textarea name="descricao" class="form-control" rows="3" placeholder="Descrição detalhada do plano..."><?php echo $plan->descricao; ?></textarea>
                                    <small class="form-hint">Opcional - Descreva os benefícios do plano</small>
                                </div>

                                <!-- Preço e Tipo -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Preço (R$)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" name="preco" class="form-control" step="0.01" min="0" value="<?php echo $plan->preco; ?>" required placeholder="0,00">
                                            </div>
                                            <small class="form-hint">
                                                <?php if ($plan->stripe_price_id): ?>
                                                    ⚠️ Alterar o preço criará um novo Price no Stripe
                                                <?php else: ?>
                                                    Valor da assinatura
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tipo de Cobrança</label>
                                            <input type="text" class="form-control" value="<?php echo ucfirst($plan->tipo); ?>" readonly disabled>
                                            <small class="form-hint">Não é possível alterar o tipo após criação</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Limite de Imóveis -->
                                <div class="mb-3">
                                    <label class="form-label">Limite de Imóveis</label>
                                    <input type="number" name="limite_imoveis" class="form-control" min="0" value="<?php echo $plan->limite_imoveis; ?>" placeholder="Deixe em branco para ilimitado">
                                    <small class="form-hint">Quantidade máxima de imóveis que o corretor pode cadastrar (vazio = ilimitado)</small>
                                </div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label class="form-label">Status do Plano</label>
                                    <div>
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="ativo" value="1" <?php echo $plan->ativo == 1 ? 'checked' : ''; ?>>
                                            <span class="form-check-label">Ativo</span>
                                        </label>
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="ativo" value="0" <?php echo $plan->ativo == 0 ? 'checked' : ''; ?>>
                                            <span class="form-check-label">Inativo</span>
                                        </label>
                                    </div>
                                    <small class="form-hint">Planos inativos não aparecem para novos clientes</small>
                                </div>

                                <!-- Aviso -->
                                <?php if ($plan->stripe_price_id): ?>
                                <div class="alert alert-warning">
                                    <div class="d-flex">
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
                                        </div>
                                        <div>
                                            <h4 class="alert-title">Atenção ao alterar o preço</h4>
                                            <div class="text-muted">
                                                Se você alterar o preço:
                                                <ul class="mb-0 mt-2">
                                                    <li>O preço antigo será desativado no Stripe</li>
                                                    <li>Um novo preço será criado</li>
                                                    <li>Assinaturas existentes manterão o preço antigo</li>
                                                    <li>Novas assinaturas usarão o novo preço</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Botões -->
                                <div class="card-footer text-end">
                                    <div class="d-flex">
                                        <a href="<?php echo base_url('admin/planos'); ?>" class="btn btn-link">Cancelar</a>
                                        <button type="submit" class="btn btn-primary ms-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                            Salvar Alterações
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('templates/tabler/footer'); ?>
</div>
