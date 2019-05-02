<?php
	require_once(app::path.'view/header.php');
?>

    <div id="page-wrapper" >
		  <div class="header"> 
            <h1 class="page-header">
                 Abertura da Solicitação
            </h1>
					<ol class="breadcrumb">
					  <li><a href="#">Workflow Manager</a></li>
					  <li class="active">Abertura da Solicitação</li>
					</ol> 
									
		  </div>
		
         <div id="page-inner"> 
    		 <div class="row">
    		 <div class="col-lg-12">
    		 <div class="card">
                <div class="card-action">
                    Abertura da Solicitação
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

                    <form action="solicitacoes.php" method="post" name="cad_solicitacao" id="cad_solicitacao" enctype="multipart/form-data">

                    <div class="col s8">
                      <div class="row">
                        <div class="col s10">
                        <label for="empresa">Empresa</label>
                          <input id="empresa" type="text" maxlength="255" readonly="" value="<?php echo $empresa_session['razao_social']; ?>">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col s7">
                        <label for="solicitante">Solicitante</label>
                          <input type="text" id="solicitante" name="solicitante" maxlength="255" readonly="" value="<?php  echo $usuario_session['nome'];?>">
                        </div>

                        <div class="col s3">
                        <label for="data">Data</label>
                          <input type="text" id="data" readonly="" maxlength="255" value="<?php echo date("d/m/Y"); ?>">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col s6">
                        <label for="evento_id">Evento</label>
                          <select id="evento_id" name="evento_id" class="form-control input-sm">
                            <option value="" disabled selected="">Evento</option>
                            <?php $empresa->montaSelectEventoEmpresa($solicitacao->evento_id); ?>
                          </select>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col s6">
                            <label>Tipo</label><br>
                            <input type="radio" <?php if ($solicitacao->tipo == 'F') { echo 'checked'; } ?> checked id="tipo_f" name="tipo" value="F" onclick="show_func()"/>
                            <label for="tipo_f">Funcionário</label>
                            <input type="radio" <?php if ($solicitacao->tipo == 'G') { echo 'checked'; } ?> id="tipo_g" name="tipo" value="G" onclick="esconde_func()" />
                            <label for="tipo_g">Geral</label>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col s10">
                        <label for="descricao_solicitacao">Descrição</label>
                          <textarea name="descricao_solicitacao" id="descricao_solicitacao" class="validate" style="height:150px;"><?php echo $solicitacao->descricao_solicitacao; ?></textarea>
                        </div>
                      </div>

                      <div class="row" id="div_funcionario">
                        <div class="col s6">
                        <label for="funcionario_id">Funcionário</label>
                          <select id="funcionario_id" name="funcionario_id" class="form-control input-sm">
                            <option value="" disabled selected="">Funcionário</option>
                            <?php $funcionario->montaSelectFuncionarioSolicitacao($solicitacao->funcionario_id); ?>
                          </select>
                        </div>
                      </div>

                      <div class="row">                        
                        <div class="">
                            <?php 
                              if (!empty($solicitacao->id)) { 

                                 $pasta = app::path.'files/arquivo_solicitacao';
                                 if (is_dir($pasta)) {
                                    $diretorio = dir($pasta);
                                    while($arquivo = $diretorio -> read()){
                                       if ($solicitacao->id == preg_replace("/[^0-9]/", "", $arquivo)) {
                                          echo "<a href='".app::dominio.'files/arquivo_solicitacao/'.$arquivo."' target='_blank'>Visualizar Documento </a>";
                                          echo "<input type='hidden' name='file' id='file' value='".$pasta.'/'.$arquivo."'>";
                                          echo "<input type='hidden' name='excluir_anexo' id='excluir_anexo' value='0'>";
                                          echo " -- <a href='#' onclick='excluir_anexo();'>Excluir </a>";
                                       }
                                    }
                                 }
                              } 
                            ?><br>
                        </div>
                        <div class="col s5">
                            <label for="documentos">Documentos</label>
                            <input type="file" id="documentos[]" name="documentos[]" multiple="multiple"><br/>
                        </div>
                      </div>
                      
                      <br />
                      <div class="row">
                      <div class="input-field">
                      </div>
                        <input type="hidden" id="id_usuariosolicitante" name="id_usuariosolicitante" value="<?php echo $usuario_session['usuarioid']; ?>">
                        <input type="hidden" id="data_solicitacao" name="data_solicitacao" value="<?php echo date("Y-m-d H:i:s"); ?>">
                        <input type="hidden" id="empresa_id" name="empresa_id" value="<?php echo $empresa_session['id']; ?>">
                        <input type="hidden" id="action" name="action" value="1">

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

<script type="text/javascript">
  function esconde_func(){
    var div = document.getElementById("div_funcionario");
    div.style.display = "none";
  }

  function show_func(){
    var div = document.getElementById("div_funcionario");
    div.style.display = "block";
  }

  window.onload = function(e){ 
    if (document.getElementById("tipo_g").checked) {
      var div = document.getElementById("div_funcionario");
      div.style.display = "none";
    }; 
  }
</script>

<script src="<?php echo app::dominio; ?>view/assets/js/solicitacoes/solicitacao.js"></script>
