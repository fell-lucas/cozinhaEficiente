$('#saveadmProd').click(function () {
    event.preventDefault();
    $.ajax({
        type: 'ajax',
        method: 'post',
        url: $('#admProdForm').attr('action'),
        data: $('#admProdForm').serialize(),
        async: true,
        dataType: 'json',
        success: function (response) {
            $('#registros').DataTable().ajax.reload();
            $('#receitas').DataTable().ajax.reload();
            $("#nomeProdadm").val('').trigger('change');
            $("#catProdadm").val('').trigger('change');
            resetForm($('#admProdForm'));
            window.setTimeout(function () {
                $('#admProdmodal').modal('toggle');
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