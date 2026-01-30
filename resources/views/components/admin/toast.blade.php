<div id="global-toast" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;"></div>

<script>
window.showToast = function(message, type = 'success', duration = 3000) {
    var types = {
        success: 'bg-success text-white',
        danger: 'bg-danger text-white',
        info: 'bg-info text-dark',
        warning: 'bg-warning text-dark' 
    };
    var classes = types[type] || types.success;

    var $toast = $('<div class="toast align-items-center '+classes+' border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">' +
        '<div class="d-flex"><div class="toast-body">' + $('<div>').text(message).html() + '</div>' +
        '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
        '</div></div>');

    $('#global-toast').append($toast);

    // Initialize and show using Bootstrap's Toast if available
    if (window.bootstrap && window.bootstrap.Toast) {
        var toast = new bootstrap.Toast($toast[0], { delay: duration });
        toast.show();
        $toast.on('hidden.bs.toast', function(){ $(this).remove(); });
    } else {
        // Fallback: show and auto-remove
        setTimeout(function() { $toast.fadeOut(400, function(){ $(this).remove(); }); }, duration);
    }
};
</script>