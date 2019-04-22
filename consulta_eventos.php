<?php
    require_once('model/evento.php');
    $evento = new evento;
    $evento->lista();
    require_once(app::path.'view/header.php');
?>
<div id="page-wrapper">
<div class="header"> 
            <h1 class="page-header">
                Consulta de Eventos
            </h1>
            <ol class="breadcrumb">
          <li><a href="#">Configuration</a></li>
          <li><a href="#">Eventos</a></li>
          <li class="active">Consulta de Eventos</li>
        </ol> 
                        
</div>
<div id="page-inner"> 

<div class="row">
    
    <div class="col-md-12">
    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                             Consulta de Eventos
                        </div>
                        <div class="card-content">
                        <?php
                              if (!empty($msg)) { 

                                if ($success) {
                                    echo "<div class='alert alert-success'>
                                            <strong>Sucesso !</strong> $msg
                                          </div>";
                                  }

                                  if (!$success) {
                                    echo "<div class='alert alert-danger'>
                                            <strong>ERRO !</strong> $msg
                                          </div>";

                                  }                           
                                }
                             ?> 
                            <div class="table-responsive">
                                <form action="eventos.php" method="post" id="eventos_edit">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%">Código</th>
                                                <th style="width: 50%">Evento</th>
                                                <th style="width: 30%">Status</th>
                                                <th style="width: 10%">Alterar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($evento->array)) {
                                            foreach($evento->array as $row){ ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo $row['id']; ?></td>
                                                    <td><?php echo utf8_decode($row['descricao']); ?></td>
                                                    <td><?php echo $row['status']; ?></td>
                                                    <td>
                                                    <i onclick="edita(<?php echo $row['id']; ?>)" class="material-icons">mode_edit</i>
                                                    <i onclick="exclui(<?php echo $row['id']; ?>)" class="material-icons">delete</i>
                                                    </td>
                                                </tr>
                                            <?php } }?>
                                       </tbody>
                                    </table>
                                <input type="hidden" value="0" name="id" id="id">
                                <input type="hidden" value="0" name="action" id="action">
                              </form>
                            </div>  
                        </div>
                    </div>
<?php
    require_once(app::path.'view/footer.php');
?>
<script>

$(document).ready(function (){
    $('#dataTables-example').dataTable({
        language: {                        
            "url": "//cdn.datatables.net/plug-ins/1.10.9/i18n/Portuguese-Brasil.json"
        },
        dom: '<"centerBtn"B>frtip',
        buttons: [
             {
                extend: 'copyHtml5',
                exportOptions: {
                   columns: [ 0, 1, 2, 3]
                }
             },
             {
                extend: 'excelHtml5',
                exportOptions: {
                   columns: [ 0, 1, 2, 3]
                }
             },
             {
                extend: 'csvHtml5',
                exportOptions: {
                   columns: [ 0, 1, 2, 3]
                }
             },
             {
                extend: 'pdfHtml5',
                exportOptions: {
                   columns: [ 0, 1, 2, 3]
                }
             },
         ]
    });        
});

function edita(id) {
    if (id > 0) {
        document.getElementById('id').value = id;
        document.getElementById('eventos_edit').submit();
    }
}

function exclui(id) {
    var r = confirm("Certeza que quer excluir este registro?");
    if (r != true) {
        return false;
    } 
    if (id > 0) {
        document.getElementById('id').value = id;
        document.getElementById('action').value = "3";
        document.getElementById('eventos_edit').submit();
    }   
}

</script>