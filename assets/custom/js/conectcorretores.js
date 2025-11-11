/**
 * ConectCorretores - Scripts Customizados
 * Autor: Rafael Dias - doisr.com.br
 * Data: 11/11/2025
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('🏠 ConectCorretores - Tabler Integration v1.9.0');
    console.log('📐 Layout: Combo (Centralizado)');
    
    // ========================================
    // DARK MODE TOGGLE
    // ========================================
    const toggleDark = document.getElementById('theme-toggle-dark');
    const toggleLight = document.getElementById('theme-toggle-light');
    
    if (toggleDark) {
        toggleDark.addEventListener('click', function(e) {
            e.preventDefault();
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            console.log('🌙 Dark mode ativado');
        });
    }
    
    if (toggleLight) {
        toggleLight.addEventListener('click', function(e) {
            e.preventDefault();
            document.documentElement.setAttribute('data-bs-theme', 'light');
            localStorage.setItem('theme', 'light');
            console.log('☀️ Light mode ativado');
        });
    }
    
    // ========================================
    // CONFIRMAÇÃO DE AÇÕES
    // ========================================
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    confirmButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // ========================================
    // TOAST NOTIFICATIONS
    // ========================================
    window.showToast = function(message, type = 'success') {
        const iconMap = {
            'success': '<svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>',
            'error': '<svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>',
            'warning': '<svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>',
            'info': '<svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>'
        };
        
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show fade-in`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div>${iconMap[type] || iconMap['info']}</div>
                <div>${message}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        `;
        
        const container = document.querySelector('.page-body .container-xl');
        if (container) {
            container.insertBefore(toast, container.firstChild);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 150);
            }, 5000);
        }
    };
    
    // ========================================
    // LOADING STATES
    // ========================================
    window.setButtonLoading = function(button, loading = true) {
        if (loading) {
            button.classList.add('btn-loading');
            button.disabled = true;
        } else {
            button.classList.remove('btn-loading');
            button.disabled = false;
        }
    };
    
    // ========================================
    // AUTO-DISMISS ALERTS
    // ========================================
    const alerts = document.querySelectorAll('.alert:not(.alert-important)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // ========================================
    // TOOLTIPS
    // ========================================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // ========================================
    // POPOVERS
    // ========================================
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // ========================================
    // FORM VALIDATION
    // ========================================
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
    
    // ========================================
    // MÁSCARAS DE INPUT (se IMask estiver disponível)
    // ========================================
    if (typeof IMask !== 'undefined') {
        // Telefone
        const telefoneInputs = document.querySelectorAll('[data-mask="telefone"]');
        telefoneInputs.forEach(input => {
            IMask(input, {
                mask: '(00) 00000-0000'
            });
        });
        
        // CPF
        const cpfInputs = document.querySelectorAll('[data-mask="cpf"]');
        cpfInputs.forEach(input => {
            IMask(input, {
                mask: '000.000.000-00'
            });
        });
        
        // CNPJ
        const cnpjInputs = document.querySelectorAll('[data-mask="cnpj"]');
        cnpjInputs.forEach(input => {
            IMask(input, {
                mask: '00.000.000/0000-00'
            });
        });
        
        // CEP
        const cepInputs = document.querySelectorAll('[data-mask="cep"]');
        cepInputs.forEach(input => {
            IMask(input, {
                mask: '00000-000'
            });
        });
        
        // Moeda
        const moneyInputs = document.querySelectorAll('[data-mask="money"]');
        moneyInputs.forEach(input => {
            IMask(input, {
                mask: 'R$ num',
                blocks: {
                    num: {
                        mask: Number,
                        scale: 2,
                        thousandsSeparator: '.',
                        radix: ',',
                        mapToRadix: ['.'],
                        min: 0,
                        max: 999999999
                    }
                }
            });
        });
    }
    
    // ========================================
    // PREVIEW DE IMAGENS
    // ========================================
    const imageInputs = document.querySelectorAll('input[type="file"][data-preview]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const previewId = this.getAttribute('data-preview');
            const preview = document.getElementById(previewId);
            
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
    
    // ========================================
    // SCROLL TO TOP
    // ========================================
    const scrollToTopBtn = document.getElementById('scrollToTop');
    if (scrollToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.style.display = 'block';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });
        
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // ========================================
    // DEBOUNCE HELPER
    // ========================================
    window.debounce = function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    };
    
    console.log('✅ ConectCorretores scripts carregados com sucesso!');
});
