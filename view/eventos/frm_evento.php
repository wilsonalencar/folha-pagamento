<?php
	require_once(app::path.'view/header.php');
?>
    
    <div id="page-wrapper" >
		  <div class="header"> 
            <h1 class="page-header">
                 Eventos
            </h1>
					<ol class="breadcrumb">
					  <li><a href="#">Configuration</a></li>
					  <li><a href="#">Eventos</a></li>
					  <li class="active">Cadastro de Eventos</li>
					</ol> 
									
		  </div>
		
         <div id="page-inner"> 
    		 <div class="row">
    		 <div class="col-lg-12">
    		 <div class="card">
                <div class="card-action">
                    Cadastro de Eventos
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
                     
                    <form class="col s12" action="eventos.php" method="post" name="cad_evento">
                      <div class="row">
                        <div class="col s6">
                        <label for="descricao">Evento</label>
                          <input type="text" id="descricao" name="descricao" class="validate" maxlength="255" value="<?php echo $evento->descricao; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col s3">
                        <label for="Status">Status</label>
                          <select id="status" name="status" class="form-control input-sm">
                            <option value="<?php echo $evento::STATUS_SISTEMA_ATIVO ?>">Ativo</option>
                            <option value="<?php echo $evento::STATUS_SISTEMA_INATIVO ?>">Inativo</option>
                          </select>
                        </div>
                      <br />
                      <div class="input-field">
                        </div>
                        <br/>
                        <br/>
                        <input type="hidden" id="id" name="id" value="<?php echo $evento->id; ?>">
                        <input type="hidden" id="action" name="action" value="1">

                        <?php if ($evento->id > 0){ ?>
                        <div class="input-field col s2">
                            <a href="<?php echo app::dominio; ?>consulta_eventos.php" class="waves-effect waves-light btn">Voltar</a>
                        </div>
                        <?php } ?>

                        <div class="input-field col s1">
                            <input type="submit" name="salvar" value="salvar" id="submit" class="waves-effect waves-light btn">
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

<script src="<?php echo app::dominio; ?>view/assets/js/eventos/evento.js"></script>