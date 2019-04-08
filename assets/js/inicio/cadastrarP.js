$('#saveProduto').click(function () {
    event.preventDefault();
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: $('#produtoForm').attr('action'),
        data: $('#produtoForm').serialize(),
        async: true,
        dataType: 'json',
        success: function (response) {
            $('#registros').DataTable().ajax.reload();
            $('#receitas').DataTable().ajax.reload();
            if (response[0] == false) {
                $('#cadastrarProduto').modal('toggle');
                window.setTimeout(function () {
                    $('#validarModalMsg').html(response[1]);
                    $('#validarModal').modal('toggle');
                }, 50)
            } else {
                $('#cadastrarProduto').modal('toggle');
                $('#exibealert').addClass(response[0]).html(response[1]).fadeTo(50, 1);
                //tweaks para limpar o form após ajax (somente se a validação foi bem sucedida)
                $("#limparCadastrar").click();
                $("#nome").val([]).trigger('change');
                window.setTimeout(function () {
                    $("#exibealert").slideUp();
                    window.setTimeout(function () {
                        $('#exibealert').removeClass(response[0]).html('').fadeTo(50, 1);
                    }, 500);
                }, 4000);
            }
        },
        error: function (response) {
            console.log(response);
        }
    });
});