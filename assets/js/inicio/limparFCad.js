$('#limparCadastrar').click(function () {
    $("#nome").val('').trigger('change');
    $("#quantidade").removeAttr('min');
    $("#quantidade").trigger('change');
    $("#quantidade").attr('min', '1');
});