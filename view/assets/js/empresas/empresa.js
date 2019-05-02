
var id = $("#id").val();
if (id > 0) {
   getDataEmpresa(id);
}


function getDataEmpresa(id)
{
   $.ajax({
        url : 'empresas.php',
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
            $("#razao_social").val(d.data.razao_social);
            $("#nome_fantasia").val(d.data.nome_fantasia);
            $("#cnpj").val(d.data.cnpj);
            $("#status").val(d.data.status);
        }
    });
}

$( document ).ready(function() {

  $( "#submit" ).click(function() {

    var eventos = document.getElementById("id_eventos").value;
    
    if ($("#razao_social").val() == '') {
        alert('Informar a razão social da empresa');
        $("#razao_social").focus();
        return false;
    }

    if ($("#nome_fantasia").val() == '') {
        alert('Informar o nome fantasia da empresa');
        $("#nome_fantasia").focus();
        return false;
    }

    if ($("#cnpj").val() == '') {
        alert('Informar o CNPJ da empresa');
        $("#cnpj").focus();
        return false;
    }

    if (eventos == '') {
        alert('Informar o evento da empresa');
        $("#id_eventos").focus();
        return false;
    }
    
    return true;
  });

});
