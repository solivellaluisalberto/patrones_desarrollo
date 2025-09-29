// JavaScript para la aplicación MVC

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide alerts después de 5 segundos
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Confirmación para formularios de eliminación
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
                e.preventDefault();
            }
        });
    });

    // Validación de formularios en tiempo real
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                validateField(input);
            });
            
            input.addEventListener('input', function() {
                if (input.classList.contains('is-invalid')) {
                    validateField(input);
                }
            });
        });
    });

    // Función para validar campos individuales
    function validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required') || field.getAttribute('data-required') === 'true';
        
        // Limpiar clases previas
        field.classList.remove('is-valid', 'is-invalid');
        
        // Validar campo requerido
        if (isRequired && value === '') {
            field.classList.add('is-invalid');
            return false;
        }
        
        // Validar email
        if (field.type === 'email' && value !== '') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                field.classList.add('is-invalid');
                return false;
            }
        }
        
        // Validar números
        if (field.type === 'number' && value !== '') {
            const min = parseFloat(field.getAttribute('min'));
            const max = parseFloat(field.getAttribute('max'));
            const numValue = parseFloat(value);
            
            if (isNaN(numValue) || (min !== null && numValue < min) || (max !== null && numValue > max)) {
                field.classList.add('is-invalid');
                return false;
            }
        }
        
        // Si llegamos aquí, el campo es válido
        if (value !== '') {
            field.classList.add('is-valid');
        }
        
        return true;
    }

    // Función para mostrar notificaciones
    window.showNotification = function(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto-remove después de 5 segundos
        setTimeout(function() {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
            }
        }, 5000);
    };

    // Función para formatear números como moneda
    window.formatCurrency = function(amount) {
        return new Intl.NumberFormat('es-ES', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    };

    // Función para confirmar acciones
    window.confirmAction = function(message, callback) {
        if (confirm(message)) {
            callback();
        }
    };

    // Agregar animaciones de entrada
    const cards = document.querySelectorAll('.card');
    cards.forEach(function(card, index) {
        card.style.animationDelay = (index * 0.1) + 's';
        card.classList.add('fade-in');
    });
});

// Función global para confirmar eliminación (usada en las vistas)
function confirmDelete(id, name) {
    if (document.getElementById('deleteModal')) {
        document.getElementById('productName').textContent = name;
        document.getElementById('deleteForm').action = '/LEARN/PATRONES/mvc/public/products/delete/' + id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    } else {
        if (confirm('¿Estás seguro de que deseas eliminar "' + name + '"?')) {
            window.location.href = '/LEARN/PATRONES/mvc/public/products/delete/' + id;
        }
    }
}
