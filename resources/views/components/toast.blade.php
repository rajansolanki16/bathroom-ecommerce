
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.Toast = function(message, type = 'success') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            icon: type,
            title: message,
            customClass: {
                popup: 'swal2-toast'
            }
        });
    };
</script>
<style>
.swal2-modern-popup {
    border-radius: 1rem !important;
    box-shadow: 0 8px 32px rgba(60,60,60,0.15) !important;
    padding: 2.5rem 2rem !important;
}
.swal2-modern-title {
    font-size: 1.35rem !important;
    font-weight: 600 !important;
    letter-spacing: 0.01em;
}
.swal2-modern-icon.swal2-success {
    color: #198754 !important;
    border-color: #198754 !important;
}
.swal2-modern-icon.swal2-error {
    color: #b02a37 !important;
    border-color: #b02a37 !important;
}
</style>