
var id = $("#id").val();
if (id > 0) {
   getDataEvento(id);
}

function getDataEvento(id)
{
   $.ajax({
        url : 'eventos.php',
        type: 'POST',
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
            $("#descricao").val(d.data.descricao);
            $("#status").val(d.data.status);
        }
    });
}

$( document ).ready(function() {

  $( "#submit" ).click(function() {
    
    if ($("#descricao").val() == '') {
        alert('Informar a descricao do evento');
        $("#descricao").focus();
        return false;
    }

    return true;
  });

});
