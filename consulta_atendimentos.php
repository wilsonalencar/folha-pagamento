<?php
    require_once('model/solicitacao.php');
    require_once('model/funcionario.php');

    $funcionario = new funcionario;
    $solicitacao = new solicitacao;
    $solicitacao->listaAtendimento($_POST);
    require_once(app::path.'view/header.php');

    $_SESSION['folha']['previous_page'] = 'consulta_atendimentos.php';
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
                Solicitações
            </h1>
            <ol class="breadcrumb">
          <li><a href="#">Cockpit</a></li>
          <li class="active">Solicitações</li>
        </ol> 
                        
</div>
    <!-- Advanced Tables -->
          <div class="card">
                  <div class="card-action">
                      <a href="#" data-toggle="modal" data-target="#ModalPesquisa">Filtros</a>
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
                          <form action="atendimentos.php" method="post" id="atendimento_edit">
                              <table class="table display" id="dataTables-example">
                                  <thead>
                                      <tr class="top-table">
                                          <th style="width: 5%">Solicitação</th>
                                          <th style="width: 10%">Data</th>
                                          <th style="width: 10%">Solicitante</th>
                                          <th style="width: 15%">Evento</th>
                                          <th style="width: 15%">Funcionário</th>
                                          <th style="width: 30%">Descrição</th>
                                          <th style="width: 10%">Status</th>
                                          <th style="width: 5%">Detalhe</th>
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
                                              <td><?php echo utf8_decode($row['nome_funcionario']); ?></td>
                                              <td><?php echo utf8_decode($row['descricao_solicitacao']); ?></td>
                                              <td><?php echo utf8_encode($row['status']); ?></td>
                                              <td>
                                                <a href="#" style="margin-left:10px" onclick="edita(this.id)" id="<?php echo $row['id']; ?>" class="btn btn-success btn-default btn-sm">Detalhe</a>
                                              </td>
                                          </tr>
                                      <?php } }?>
                                 </tbody>
                              </table>
                          <input type="hidden" value="0" name="id" id="id">
                          <input type="hidden" value="2" name="action" id="action">
                        </form>
                      </div>  
                  </div>

                  <div id="ModalPesquisa" class="modal fade" >
                    <div class="modal-header">
                        <h4 class="modal-title">Filtros</h4>
                    </div>
                    <form class="col s12" action="" method="POST" name="filtro">
                        <div class="modal-body">
                          <div class="row">
                            <div class="col s3">
                            <label for="solicitacao">Solicitação</label>
                              <select id="solicitacao" name="id" class="form-control input-sm">
                                <option value="">Todos</option>
                                <?php $solicitacao->montaSelectNumSolicitacao(); ?>
                              </select>
                            </div>
                            <div class="col s3">
                            <label for="status">Status</label>
                              <select id="status" name="status_id" class="form-control input-sm">
                                <option value="">Todos</option>
                                <?php $solicitacao->montaSelectStatusSolicitacao(); ?>
                              </select>
                            </div>
                            <div class="col s3">
                            <label for="solicitante">Solicitante</label>
                            <select id="solicitante" name="solicitante" class="form-control input-sm">
                              <option value="">Todos</option>
                              <?php $solicitacao->montaSelectSolicitante(); ?>
                            </select>
                            </div>
                            <div class="col s3">
                            <label for="funcionario">Funcionário</label>
                            <select id="funcionario" name="funcionario" class="form-control input-sm">
                              <option value="">Todos</option>
                              <?php $funcionario->montaSelectFuncionarioSolicitacao(); ?>
                            </select>
                            </div>
                            <div class="col s12">&#8200</div>
                            <div class="col s2">
                                Período
                                <input type="date" name="data_busca_periodo" class="validate" value="" maxlength="8">
                            </div>
                            <div class="col s8"></div>
                            <div class="col s2">
                              <br/>
                              <br/>
                              <input type="submit" name="clear" value="Limpar filtros" class="btn btn-success">
                            </div>
                          </div>
                        <div class="modal-footer">
                            <input type="hidden" name="action" value="4">
                            <button type="submit" class="btn btn-success">OK</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                        </div>
                    </form>    
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
                   columns: [ 0, 1, 2, 3, 4, 5, 6]
                }
             },
             {
                extend: 'excelHtml5',
                exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5, 6]
                }
             },
             {
                extend: 'csvHtml5',
                exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5, 6]
                }
             },
             {
                extend: 'pdfHtml5',
                exportOptions: {
                   columns: [ 0, 1, 2, 3, 4, 5, 6]
                }
             },
         ]
    });        
});

function edita(id) {
    if (id > 0) {
        document.getElementById('id').value = id;
        document.getElementById('atendimento_edit').submit();
    }
}

</script>