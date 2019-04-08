$('#excluirProdutobtn').click(function () {
    event.preventDefault();
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: $('#excluirForm').attr('action'),
        data: $('#excluirForm').serialize(),
        async: true,
        dataType: 'json',
        success: function (response) {
            $('#registros').DataTable().ajax.reload();
            $('#receitas').DataTable().ajax.reload();
            window.setTimeout(function () {
                $('#excluirProduto').modal('toggle');
                $('#exibealert').addClass(response[0]).html(response[1]).fadeTo(50, 1);
                window.setTimeout(function () {
                    $("#exibealert").slideUp();
                }, 4000);
            }, 1000);
        },
        error: function (response) {
            console.log(response);
        }
    });
});