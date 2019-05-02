<?php
    require_once('model/funcionario.php');
    $funcionario = new funcionario;
    $funcionario->lista();
    require_once(app::path.'view/header.php');
?>

<div id="page-wrapper">
<div class="header"> 
            <h1 class="page-header">
                Consulta de Funcionários
            </h1>
            <ol class="breadcrumb">
          <li><a href="#">Configuration</a></li>
          <li><a href="#">Funcionários</a></li>
          <li class="active">Consulta de Funcionários</li>
        </ol> 
                        
</div>
<div id="page-inner"> 

<div class="row">
    
    <div class="col-md-12">
    <!-- Advanced Tables -->
                    <div class="card">
                        <div class="card-action">
                             Consulta de Funcionários
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
                                <form action="funcionarios.php" method="post" id="funcionarios_edit">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th style="display: none">Id</th>
                                                <th style="width: 10%">Código Funcionário</th>
                                                <th style="width: 20%">Nome</th>
                                                <th style="width: 20%">CPF</th>
                                                <th style="width: 30%">Empresa</th>
                                                <th style="width: 10%">Data Cadastro</th>
                                                <th style="width: 10%">Alterar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (!empty($funcionario->array)) {
                                            foreach($funcionario->array as $row){ ?>
                                                <tr class="odd gradeX">
                                                    <td style="display: none"><?php echo $row['id']; ?></td>
                                                    <td><?php echo $row['codigo']; ?></td>
                                                    <td><?php echo utf8_decode($row['nome']); ?></td>
                                                    <td><?php echo ($row['cpf']); ?></td>
                                                    <td><?php echo utf8_decode($row['nome_empresa']); ?></td>
                                                    <td><?php echo $row['data_cadastro']; ?></td>
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
                pageSize: 'A4',
                title: 'Configuration - Funcionários',
                exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5]
                }
             },
             {
                extend: 'excelHtml5',
                pageSize: 'A4',
                title: 'Configuration - Funcionários',
                exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5]
                }
             },
             {
                extend: 'csvHtml5',
                pageSize: 'A4',
                title: 'Configuration - Funcionários',
                exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5]
                }
             },
             {
                extend: 'pdfHtml5',
                pageSize: 'A4',
                title: 'Configuration - Funcionários',
                exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5]
                },
                customize : function(doc) {
                    doc.content[1].table.widths = [ '5%', '10%', '30%', '15%', '30%', '14%'];
                }
             },
         ]
    });        
});

function edita(id) {
    if (id > 0) {
        document.getElementById('id').value = id;
        document.getElementById('funcionarios_edit').submit();
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
        document.getElementById('funcionarios_edit').submit();
    }   
}

</script>