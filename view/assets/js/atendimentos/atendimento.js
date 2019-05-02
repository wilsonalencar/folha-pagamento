var id = $("#id").val();
if (id > 0) {
   getDataAtendimento(id);
}


function excluir_anexo()
{
  if (!confirm('Tem certeza que deseja excluir esse anexo?')) {
    return false;
  }

   $("#excluir_anexo").val(1);
   document.getElementById('cad_atendimento').submit();
}

function getDataAtendimento(id)
{
   $.ajax({
        url : 'atendimentos.php',
        type: 'post',
        dataType: 'JSON',
        data:
        {
            'action':5,
            'id':id
        },
        success: function(d)
        {
            if (!d.success) {
               alert(d.msg);
               return false;
            }
            $("#id").val(d.data.id);
            $("#status_id").val(d.data.status_id);
            $("#descricao_atendimento").val(d.data.descricao_atendimento);
            $("#data_fim_atend").val(d.data.data_fim_atend);
        }
    });
}

$( document ).ready(function() {

  $( "#btnSubmit" ).click(function() {

    var status = document.getElementById("status_id").value;
    var data_fim = document.getElementById("data_fim_atend").value;


    if (status == 3 && $("#data_fim_atend").val() == '') {
        alert('Informar a data de conclusão');
        $("#data_fim_atend").focus();
        return false;
    }

    if ($("#descricao_atendimento").val() == '') {
        alert('Informar a descrição do atendimento');
        $("#descricao_atendimento").focus();
        return false;
    }

    if (status == '') {
        alert('Informar um status');
        $("#status_id").focus();
        return false;
    }

    if (status != 3 && data_fim != '') {
        alert('Status inválido para data de conclusão');
        $("#status_id").focus();
        return false;
    }

    return true;
  });

});
