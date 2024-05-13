document.addEventListener('DOMContentLoaded', function () {
    $('#modalSetting').modal('show');
    $('#backgroundModal').show();
    $('#modalSetting').on('hidden.bs.modal', function () {
        history.replaceState(null, null, window.location.pathname);
    });
});

function closeModal() {
    window.location.href = "/settings";
}
