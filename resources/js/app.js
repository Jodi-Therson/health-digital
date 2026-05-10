
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Toast notifications helper
window.showToast = function(message, type = 'success') {
    const toast = document.createElement('div');
    const colors = {
        success: 'alert-success',
        error: 'alert-error',
        warning: 'alert-warning',
        info: 'alert-info',
    };
    toast.className = `alert ${colors[type] || 'alert-info'} fade-in`;
    toast.style.minWidth = '280px';
    toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.12)';
    toast.innerHTML = message;

    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 4000);
};

// Confirm modal helper
window.confirmAction = function(message, callback) {
    if (confirm(message)) callback();
};

// Auto-hide alerts after 5s
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-auto-hide]').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, parseInt(el.dataset.autoHide) || 5000);
    });
});
