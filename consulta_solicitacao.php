<?php
    require_once('model/solicitacao.php');
    $solicitacao = new solicitacao;
    $solicitacao->lista();
    require_once(app::path.'view/header.php');

    $_SESSION['folha']['previous_page'] = 'consulta_solicitacao.php';
?>

<style type="text/css">
  .dataTables_wrapper .dt-buttons {
    float:none !important;  
    text-align:center !important;
  }

  .dataTables_filter {
   padding-right: 15px !important;
   float: right !important;
   text-align: left !important;
}

</style>

<div id="page-wrapper">
<div class="header"> 
            <h1 class="page-header">
                Atendimento
            </h1>
            <ol class="breadcrumb">
          <li><a href="#">Workflow Manager</a></li>
          <li class="active">Atendimento</li>
        </ol> 
                        
</div>
    <!-- Advanced Tables -->
          <div class="card">
                  <div class="card-action">
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

                      <div class="table-default">
                          <form action="atendimentos.php" method="post" id="solicitacao_edit">
                              <table class="table display" id="dataTables-example">
                                  <thead>
                                      <tr class="top-table">
                                          <th style="width: 10%">Solicitação</th>
                                          <th style="width: 10%">Data</th>
                                          <th style="width: 15%">Solicitante</th>
                                          <th style="width: 20%">Evento</th>
                                          <th style="width: 30%">Descrição</th>
                                          <th style="width: 30%">Status</th>
                                          <th style="width: 15%">Alterar</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  <?php
                                  if (!empty($solicitacao->array)) {
                                      foreach($solicitacao->array as $row){ ?>
                                          <tr>
                                              <td><?php echo $row['id']; ?></td>
                                              <td><?php echo $row['data_solicitacao']; ?></td>
                                              <td><?php echo utf8_decode($row['nome_usuario']); ?></td>
                                              <td><?php echo utf8_decode($row['evento']); ?></td>
                                              <td><?php echo utf8_decode($row['descricao_solicitacao']); ?></td>
                                              <td><?php echo utf8_encode($row['status']); ?></td>
                                              <td>
                                                <a href="#" style="margin-left:10px" onclick="edita(this.id)" id="<?php echo $row['id']; ?>" class="btn btn-success btn-default btn-sm">Editar</a>
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

window.onload = function click(){
  $('#sideNav').click();
}

$(document).ready(function (){
    $('#dataTables-example').dataTable({
        pageLength : 15,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
        iDisplayLength: 15,
        responsive: true,
        language: {                        
            "url": "//cdn.datatables.net/plug-ins/1.10.9/i18n/Portuguese-Brasil.json"
        },
        dom: 'fBrtip',
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
        document.getElementById('solicitacao_edit').submit();
    }
}

</script>