function toggleMedicamentos() {
    var estado = $('#estado-dropdown').val();
    if (estado === 'realizada') {
        $('#card-medicamentos').slideDown();
    } else {
        $('#card-medicamentos').slideUp();
    }
}

$(document).ready(function() {
    // Executar ao carregar a página
    toggleMedicamentos();

    // Executar quando mudar o dropdown
    $('#estado-dropdown').on('change', toggleMedicamentos);
});