/**
 * Script de Checkout com Cupom - ConectCorretores
 * 
 * Gerencia validação e aplicação de cupons no checkout
 * 
 * @author Rafael Dias - doisr.com.br
 * @date 10/11/2025
 */

// Variáveis globais para cupom
let currentPlanId = null;
let currentCoupon = null;
let currentPlanPrice = null;

// Abrir modal de cupom
function abrirModalCupom() {
    document.getElementById('couponModal').classList.remove('hidden');
    document.getElementById('coupon-code').value = '';
    document.getElementById('coupon-message').classList.add('hidden');
    document.getElementById('coupon-summary').classList.add('hidden');
    document.getElementById('proceed-with-coupon-btn').disabled = true;
    currentCoupon = null;
}

// Fechar modal de cupom
function fecharModalCupom() {
    document.getElementById('couponModal').classList.add('hidden');
    currentPlanId = null;
    currentCoupon = null;
    currentPlanPrice = null;
}

// Validar cupom via AJAX
async function validarCupom() {
    const codigo = document.getElementById('coupon-code').value.trim().toUpperCase();
    const messageDiv = document.getElementById('coupon-message');
    const summaryDiv = document.getElementById('coupon-summary');
    const validateBtn = document.getElementById('validate-coupon-btn');
    const proceedBtn = document.getElementById('proceed-with-coupon-btn');
    
    if (!codigo) {
        messageDiv.className = 'alert-error';
        messageDiv.textContent = 'Digite um código de cupom';
        messageDiv.classList.remove('hidden');
        return;
    }
    
    // Loading
    validateBtn.disabled = true;
    validateBtn.textContent = 'Validando...';
    messageDiv.classList.add('hidden');
    summaryDiv.classList.add('hidden');
    
    try {
        const userId = document.querySelector('[data-user-id]')?.dataset.userId || '';
        const response = await fetch(baseUrl + 'cupons/validate_ajax', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `codigo=${codigo}&user_id=${userId}&plan_price=${currentPlanPrice}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Cupom válido
            currentCoupon = data.coupon;
            
            // Mostrar resumo
            const desconto = parseFloat(data.coupon.desconto);
            const valorFinal = parseFloat(data.coupon.valor_final);
            const valorCupom = parseFloat(data.coupon.valor);
            
            // Formatar texto do tipo de desconto
            let tipoTexto;
            if (data.coupon.tipo === 'percent') {
                tipoTexto = `${valorCupom.toFixed(0)}% de desconto`;
            } else {
                tipoTexto = `R$ ${valorCupom.toFixed(2).replace('.', ',')} de desconto`;
            }
            
            document.getElementById('discount-details').innerHTML = `
                <strong>${data.coupon.codigo}</strong>: ${tipoTexto}<br>
                De <span class="line-through">R$ ${currentPlanPrice.toFixed(2).replace('.', ',')}</span> 
                por <strong class="text-green-700">R$ ${valorFinal.toFixed(2).replace('.', ',')}</strong>
            `;
            
            summaryDiv.classList.remove('hidden');
            proceedBtn.disabled = false;
            
        } else {
            // Cupom inválido
            messageDiv.className = 'alert-error';
            messageDiv.textContent = data.message;
            messageDiv.classList.remove('hidden');
            currentCoupon = null;
            proceedBtn.disabled = true;
        }
        
    } catch (error) {
        console.error('Erro:', error);
        messageDiv.className = 'alert-error';
        messageDiv.textContent = 'Erro ao validar cupom. Tente novamente.';
        messageDiv.classList.remove('hidden');
        currentCoupon = null;
        proceedBtn.disabled = true;
    } finally {
        validateBtn.disabled = false;
        validateBtn.textContent = 'Validar';
    }
}

// Prosseguir sem cupom
async function prosseguirSemCupom() {
    // Salvar planId antes de fechar modal (que limpa as variáveis)
    const planId = currentPlanId;
    
    fecharModalCupom();
    await processarCheckout(planId, null);
}

// Prosseguir com cupom
async function prosseguirComCupom() {
    console.log('prosseguirComCupom chamado', currentCoupon);
    
    if (!currentCoupon) {
        alert('Valide o cupom antes de continuar');
        return;
    }
    
    // Salvar cupom antes de fechar modal (que limpa as variáveis)
    const couponCode = currentCoupon.codigo;
    const planId = currentPlanId;
    
    fecharModalCupom();
    console.log('Processando checkout com cupom:', couponCode);
    await processarCheckout(planId, couponCode);
}

// Processar checkout (com ou sem cupom)
async function processarCheckout(planId, couponCode) {
    console.log('processarCheckout chamado', {planId, couponCode});
    
    const button = document.querySelector(`[data-plan-id="${planId}"]`);
    if (!button) {
        console.error('Botão não encontrado para planId:', planId);
        alert('Erro: Botão não encontrado. Recarregue a página.');
        return;
    }
    
    const originalHTML = button.innerHTML;
    
    // Desabilitar botão e mostrar loading
    button.disabled = true;
    button.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span> Processando...';
    
    try {
        // Preparar body
        let body = `plan_id=${planId}`;
        if (couponCode) {
            body += `&coupon_code=${couponCode}`;
        }
        
        console.log('Enviando requisição:', {url: baseUrl + 'planos/criar_checkout_session', body});
        
        // Criar sessão de checkout
        const response = await fetch(baseUrl + 'planos/criar_checkout_session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: body
        });
        
        console.log('Resposta recebida:', response.status);
        const data = await response.json();
        console.log('Dados da resposta:', data);
        
        if (data.success) {
            console.log('Redirecionando para Stripe checkout...');
            // Redirecionar para checkout do Stripe
            const result = await stripe.redirectToCheckout({
                sessionId: data.session_id
            });
            
            if (result.error) {
                alert('Erro: ' + result.error.message);
                button.disabled = false;
                button.innerHTML = originalHTML;
            }
        } else {
            alert('Erro: ' + data.error);
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao processar pagamento. Tente novamente.');
        button.disabled = false;
        button.innerHTML = originalHTML;
    }
}

// Permitir Enter no campo de cupom
document.addEventListener('DOMContentLoaded', function() {
    const couponInput = document.getElementById('coupon-code');
    if (couponInput) {
        couponInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                validarCupom();
            }
        });
    }
});
