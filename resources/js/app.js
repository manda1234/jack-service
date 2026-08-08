import './bootstrap';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Swal = Swal;
window.Alpine = Alpine;

window.confirmAction = function (formId, options = {}) {
    Swal.fire({
        title: options.title || 'Apakah kamu yakin?',
        icon: options.icon || 'question',
        showCancelButton: true,
        confirmButtonColor: options.confirmColor || '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: options.confirmText || 'Ya',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
};

Alpine.start();