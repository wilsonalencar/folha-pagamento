<?php
  require_once('model/solicitacao.php');
  require_once('model/empresa.php');
  require_once('model/funcionario.php');

  $empresa        = new empresa;
  $funcionario    = new funcionario;
  $solicitacao    = new solicitacao;
  
  $solicitacao->get($_POST['id']);
  $value = $solicitacao->array;

  require_once(app::path.'view/header.php');
?>

    <div id="page-wrapper" >
      <div class="header"> 
            <h1 class="page-header">
                 Atendimento da Solicitação
            </h1>
          <ol class="breadcrumb">
            <li><a href="#">Workflow Manager</a></li>
            <li class="active">Atendimento da Solicitação</li>
          </ol> 
                  
      </div>
    
         <div id="page-inner"> 
         <div class="row">
         <div class="col-lg-12">
         <div class="card">
                <div class="card-action">
                    Atendimento da Solicitação
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

                    <form action="atendimentos.php" method="post" name="cad_atendimento" id="cad_atendimento" enctype="multipart/form-data">

                    <div class="col s8">
                       <div class="row">
                        <div class="col s2">
                        <label for="solicitacao">Solicitação</label>
                          <input id="solicitacao" name="id" type="text" maxlength="255" readonly="" value="<?php echo $value['id']; ?>">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col s10">
                        <label for="empresa">Empresa</label>
                          <input id="empresa" type="text" maxlength="255" readonly="" value="<?php echo $value['nome_empresa']; ?>">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col s7">
                        <label for="solicitante">Solicitante</label>
                          <input type="text" id="solicitante" name="solicitante" maxlength="255" readonly="" value="<?php  echo $value['usuario_solicitante']; ?>">
                        </div>

                        <div class="col s3">
                        <label for="data">Data</label>
                          <input type="text" id="data" readonly="" maxlength="255" value="<?php echo $value['data_solicitacao']; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col s6">
                          <label for="evento">Evento</label>
                          <input type="text" id="evento" name="evento" maxlength="255" readonly="" value="<?php  echo $value['desc_evento']; ?>">
                          </select>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col s6">
                            <label>Tipo</label><br>
                            <input type="radio" <?php if ($value['tipo'] == 'F') { echo 'checked'; } ?>  id="tipo_f" name="tipo" value="F"/>
                            <label for="tipo_f">Funcionário</label>
                            <input type="radio" <?php if ($value['tipo'] == 'G') { echo 'checked'; } ?> id="tipo_g" name="tipo" value="G" />
                            <label for="tipo_g">Geral</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col s10">
                        <label for="descricao_solicitacao">Descrição</label>
                          <textarea readonly="" name="descricao_solicitacao" id="descricao_solicitacao" class="validate" style="height:150px;"><?php echo $value['descricao_solicitacao']; ?></textarea>
                        </div>
                      </div>

                      <div class="row" id="div_funcionario">
                        <div class="col s6">
                          <label for="funcionario">Funcionário</label>
                          <input type="text" id="funcionario" name="funcionario" maxlength="255" readonly="" value="<?php  echo $value['nome_funcionario']; ?>">
                          </select>
                        </div>
                      </div>

                      <div class="row">                        
                        <div class="col s5">
                          <div class="">
                            <label for="documentos">Documentos</label><br />
                            <?php 
                              if (!empty($value['id'])) { 

                                $pasta = app::path.'files/arquivo_solicitacao/'.$value['id'];
                                if (is_dir($pasta)) {
                                  $diretorio = dir($pasta);

                                  while($arquivo =  $diretorio -> read()){
                                    if ($arquivo != "." && $arquivo != "..") {
                                      echo "<a href='".app::dominio.'files/arquivo_solicitacao/'.$value['id'].'/'.$arquivo."' target='_blank'>".$arquivo."</a><br />";
                                      /*echo "<a href='".app::dominio.'files/arquivo_solicitacao/'.$value['id'].'/'.$arquivo."' target='_blank'>Visualizar Documento </a><br />";
                                      echo "<input type='hidden' name='file' id='file' value='".$pasta.'/'.$arquivo."'>";*/
                                    }
                                  }
                                }
                              } 
                            ?><br>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col s7">
                        <label for="atendente">Atendente</label>
                          <input type="text" id="atendente" name="atendente" maxlength="255" readonly="" value="<?php  echo $usuario_session['nome'];?>">
                        </div>
                      </div>

                      <div class="row">
                        <?php if (!empty($value['data_inicio_atend'])) { ?>
                        <div class="col s2">
                          <label for="data">Data Início</label>
                          <input type="text" id="data" readonly="" name="data_inicio_atend" maxlength="255" value="<?php echo $value['data_inicio_atend']; ?>">
                        </div>
                        <?php } ?>

                        <?php if (empty($value['data_inicio_atend'])) { ?>
                        <div class="col s2">
                          <label for="data">Data Início</label>
                          <input type="text" id="data" readonly="" maxlength="255" value="<?php echo date("d/m/Y"); ?>">
                        </div>
                        <?php } ?>

                        <div class="col s3"></div>
                        <?php if (!empty($value['data_fim_atend'])) { ?>
                        <div class="col s2">
                          <label for="data_fim_atend">Data Conclusão</label>
                          <input id="data_fim" type="text" readonly="" maxlength="255" value="<?php echo $value['data_fim_atend']; ?>">
                        </div>
                        <?php } ?>

                        <div class="col s3" id="div_data">
                          <label for="data_fim_atend">Data Conclusão</label>
                          <input id="data_fim_atend" type="datetime-local" name="data_fim_atend" maxlength="255" value="">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col s10">
                        <label for="descricao_atendimento">Atendimento</label>
                          <textarea name="descricao_atendimento" id="descricao_atendimento" class="validate" style="height:150px;"><?php echo $value['descricao_atendimento']; ?></textarea>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col s6">
                        <label for="status_id">Status</label>
                          <select id="status_id" name="status_id" class="form-control input-sm">
                            <option value="" disabled selected="">Status</option>
                            <?php $solicitacao->montaSelectStatusSolicitacao($value['status_id']); ?>
                          </select>
                        </div>
                      </div>
                      <br />
                      <div class="row">
                      <div class="input-field">
                      </div>
                        <input type="hidden" id="id" name="id" value="<?php echo $value['id']; ?>">
                        <input type="hidden" id="data_inicio_atend" name="data_inicio_atend" value="<?php echo date("Y-m-d H:i:s"); ?>">
                        <input type="hidden" id="id_usuarioatendente" name="id_usuarioatendente" value="<?php  echo $usuario_session['usuarioid'];?>">
                        <input type="hidden" id="aceite_encerramento" name="aceite_encerramento" value="<?php  echo $usuario_session['nome'];?>">
                        <input type="hidden" id="action" name="action" value="1">
                        <div class="input-field col s2">
                            <a href="<?php echo app::dominio; ?>consulta_solicitacao.php"  class="waves-effect waves-light btn">Voltar</a>
                        </div>
                        <div class="input-field col s1">
                            <input type="submit" name="salvar" value="salvar" id="btnSubmit" class="waves-effect waves-light btn">
                        </div>
                      </div>
                    </div>
                    </form>
                        
                  <div class="clearBoth"></div>
                  </div>
                  </div>
                  </div>
            </div>
      </div>

  
<?php
  require_once(app::path.'/view/footer.php');
?>

<script src="<?php echo app::dominio; ?>view/assets/js/atendimentos/atendimento.js"></script>


<script type="text/javascript">
  
  $(':radio:not(:checked)').attr('disabled', true);

  window.onload = function(e){ 
    if (document.getElementById("tipo_g").checked) {
      var div = document.getElementById("div_funcionario");
      div.style.display = "none";
    }; 

    if (document.getElementById("data_fim").value) {
      var div = document.getElementById("div_data");
      div.style.display = "none";
    };
  }

</script>