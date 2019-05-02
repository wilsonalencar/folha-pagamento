<?php
	require_once(app::path.'view/header.php');
?>
    
    <div id="page-wrapper" >
		  <div class="header"> 
            <h1 class="page-header">
                 Empresas
            </h1>
					<ol class="breadcrumb">
					  <li><a href="#">Configuration</a></li>
					  <li><a href="#">Empresas</a></li>
					  <li class="active">Cadastro de Empresas</li>
					</ol> 
									
		  </div>
		
         <div id="page-inner"> 
    		 <div class="row">
    		 <div class="col-lg-12">
    		 <div class="card">
                <div class="card-action">
                    Cadastro de Empresas
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

                    <form class="col s12" action="empresas.php" method="post" name="cad_empresa">
                      
                      <div class="row">
                        <div class="col s6">
                        <label for="razao_social">Razão Social</label>
                          <input type="text" id="razao_social" name="razao_social" class="validate" maxlength="255" value="<?php echo $empresa->razao_social; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col s6">
                        <label for="nome_fantasia">Nome Fantasia</label>
                          <input type="text" id="nome_fantasia" name="nome_fantasia" class="validate" maxlength="255" value="<?php echo $empresa->nome_fantasia; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col s3">
                        <label for="cnpj">CNPJ</label>
                          <input type="text" id="cnpj" name="cnpj" value="<?php echo $empresa->cnpj; ?>" maxlength="18" onkeypress="mask(this,val_cnpj)" class="validate">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col s3">
                        <label for="status">Status</label>
                          <select id="status" name="status" class="form-control input-sm">
                            <option value="<?php echo $empresa::STATUS_SISTEMA_ATIVO ?>">Ativo</option>
                            <option value="<?php echo $empresa::STATUS_SISTEMA_INATIVO ?>">Inativo</option>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                          <div class="col s3">
                            <label for="id_eventos">Eventos</label>
                            <select id="id_eventos" name="id_eventos[]" multiple class="form-control input-sm">
                              <?php $evento->montaSelectEvento($empresa->id); ?>
                            </select>
                          </div>
                      </div>
                      <br />
                      <div class="row">
                      <div class="input-field">
                        </div>
                        <input type="hidden" id="id" name="id" value="<?php echo $empresa->id; ?>">
                        <input type="hidden" id="action" name="action" value="1">
                        <?php if ($empresa->id > 0){ ?>
                        <div class="input-field col s2">
                            <a href="<?php echo app::dominio; ?>consulta_empresas.php"  class="waves-effect waves-light btn">Voltar</a>
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

<script src="<?php echo app::dominio; ?>view/assets/js/empresas/empresa.js"></script>