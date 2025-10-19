import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Função para máscara de CPF
function maskCPF(value) {
    // Remove tudo que não é dígito
    value = value.replace(/\D/g, '');
    
    // Aplica a máscara
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    
    return value;
}

// Função para aplicar máscara em inputs de CPF
function applyCPFMask() {
    const cpfInputs = document.querySelectorAll('input[name="cpf"]');
    
    cpfInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            e.target.value = maskCPF(e.target.value);
        });
        
        // Aplicar máscara no valor inicial se existir
        if (input.value) {
            input.value = maskCPF(input.value);
        }
    });
}

// Executar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    applyCPFMask();
});

// Executar também quando Alpine.js estiver pronto
Alpine.start();

// Reaplicar máscara após mudanças dinâmicas do Alpine
document.addEventListener('alpine:init', () => {
    Alpine.nextTick(() => {
        applyCPFMask();
    });
});
