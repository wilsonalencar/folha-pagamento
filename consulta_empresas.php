<?php
    require_once('model/empresa.php');
    $empresa = new empresa;
    $empresa->lista();
    require_once(app::path.'view/header.php');
?>
<div id="page-wrapper">
<div class="header"> 
            <h1 class="page-header">
                Consulta de Empresas
            </h1>
            <ol class="breadcrumb">
          <li><a href="#">Configuration</a></li>
          <li><a href="#">Empresas</a></li>
          <li class="active">Consulta de Empresas</li>
        </ol> 
                        
</div>
<div id="page-inner"> 

<div class="row">
    
    <div class="col-md-12">
    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                             Consulta de Empresas
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
                                <form action="empresas.php" method="post" id="empresas_edit">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%">Código</th>
                                                <th style="width: 25%">Razão Social</th>
                                                <th style="width: 25%">Nome Fantasia</th>
                                                <th style="width: 20%">CNPJ</th>
                                                <th style="width: 10%">Status</th>
                                                <th style="width: 10%">Alterar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($empresa->array)) {
                                            foreach($empresa->array as $row){ ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo $row['id']; ?></td>
                                                    <td><?php echo utf8_decode($row['razao_social']); ?></td>
                                                    <td><?php echo utf8_decode($row['nome_fantasia']); ?></td>
                                                    <td><?php echo $row['cnpj']; ?></td>
                                                    <td><?php echo $row['status']; ?></td>
                                                    <td>
                                                    <i onclick="edita(this.id)" id="<?php echo $row['id']; ?>" class="material-icons">mode_edit</i>
                                                    <i onclick="exclui(this.id)" id="<?php echo $row['id']; ?>" class="material-icons">delete</i>
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
                   columns: [ 0, 1, 2, 3, 4, 5]
                }
             },
             {
                extend: 'excelHtml5',
                exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5]
                }
             },
             {
                extend: 'csvHtml5',
                exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5]
                }
             },
             {
                extend: 'pdfHtml5',
                exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5]
                }
             },
         ]
    });        
});

function edita(id) {
    if (id > 0) {
        document.getElementById('id').value = id;
        document.getElementById('empresas_edit').submit();
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
        document.getElementById('empresas_edit').submit();
    }   
}

</script>