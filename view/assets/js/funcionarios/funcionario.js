
var id = $("#id").val();
if (id > 0) {
   getDataFuncionario(id);
}


function getDataFuncionario(id)
{
   $.ajax({
        url : 'funcionarios.php',
        type: 'post',
        dataType: 'JSON',
        data:
        {
            'action':2,
            'id':id
        },
        success: function(d)
        {
            if (!d.success) {
               alert(d.msg);
               return false;
            }
            
            $("#id").val(d.data.id);
            $("#empresa_id").val(d.data.empresa_id);
            $("#codigo").val(d.data.codigo);
            $("#nome").val(d.data.nome);
            $("#cpf").val(d.data.cpf);
        }
    });
}

$( document ).ready(function() {

  $( "#submit" ).click(function() {

    var empresa = document.getElementById("empresa_id").value;

    if (empresa == '') {
        alert('Informar uma empresa');
        $("#empresa_id").focus();
        return false;
    }
    
    if ($("#codigo").val() == '') {
        alert('Informar o código.');
        $("#codigo").focus();
        return false;
    }

    if ($("#nome").val() == '') {
        alert('Informar o nome do funcionário.');
        $("#nome").focus();
        return false;
    }

    if ($("#cpf").val() == '') {
        alert('Informar o CPF do funcionário');
        $("#cpf").focus();
        return false;
    }
    
    return true;
  });

});
