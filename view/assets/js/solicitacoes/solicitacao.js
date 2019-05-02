var id = $("#id").val();
if (id > 0) {
   getDataSolicitacao(id);
}


function excluir_anexo()
{
  if (!confirm('Tem certeza que deseja excluir esse anexo?')) {
    return false;
  }

   $("#excluir_anexo").val(1);
   document.getElementById('cad_solicitacao').submit();
}

function getDataSolicitacao(id)
{
   $.ajax({
        url : 'solicitacoes.php',
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
            $("#evento_id").val(d.data.evento_id);
            $(".tipo_f:checked").val();
            $(".tipo_g:checked").val();
            $('textarea#descricao_solicitacao').val();
            $("#funcionario_id").val(d.data.funcionario_id);
        }
    });
}

$( document ).ready(function() {

  $( "#btnSubmit" ).click(function() {

    var evento = document.getElementById("evento_id").value;
    var funcionario = document.getElementById("funcionario_id").value;

    if (evento == '') {
        alert('Informar um evento');
        $("#evento_id").focus();
        return false;
    }

    if ($("#descricao_solicitacao").val() == '') {
        alert('Informar a descrição');
        $("#descricao_solicitacao").focus();
        return false;
    }

    if (document.getElementById("tipo_f").checked && funcionario == '') {
        alert('Informar um funcionário');
        $("#funcionario_id").focus();
        return false;
    }

    return true;
  });

});
