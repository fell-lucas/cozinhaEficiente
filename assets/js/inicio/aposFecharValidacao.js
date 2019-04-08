$('#validarModal').on('hidden.bs.modal', function () {
    window.setTimeout(function () {
        $('#cadastrarProduto').modal('toggle');
    }, 50)
    $('#validarModalMsg').html('');
});