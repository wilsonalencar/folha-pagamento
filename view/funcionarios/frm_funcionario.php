<?php
	require_once(app::path.'view/header.php');
?>
    
    <div id="page-wrapper" >
		  <div class="header"> 
            <h1 class="page-header">
                 Funcionários
            </h1>
					<ol class="breadcrumb">
					  <li><a href="#">Configuration</a></li>
					  <li><a href="#">Funcionários</a></li>
					  <li class="active">Cadastro de Funcionários</li>
					</ol> 
									
		  </div>
		
         <div id="page-inner"> 
    		 <div class="row">
    		 <div class="col-lg-12">
    		 <div class="card">
                <div class="card-action">
                    Cadastro de Funcionários
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

                    <form class="col s12" action="funcionarios.php" method="post" name="cad_funcionario">
                      <div class="row">
                        <div class="col s6">
                        <label for="empresa_id">Empresa</label>
                          <select id="empresa_id" name="empresa_id" class="form-control input-sm">
                            <option value="" disabled selected="">Empresa</option>
                            <?php $empresa->montaSelectEmpresa(); ?>
                          </select>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col s3">
                        <label for="codigo">Código</label>
                          <input type="number" id="codigo" name="codigo" class="validate" maxlength="255" value="<?php echo $funcionario->codigo; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col s6">
                        <label for="nome">Nome</label>
                          <input type="text" id="nome" name="nome" class="validate" maxlength="255" value="<?php echo $funcionario->nome; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col s3">
                        <label for="cpf">CPF</label>
                          <input type="text" id="cpf" name="cpf" value="<?php echo $funcionario->cpf; ?>" onkeypress="mask(this,val_cpf)" maxlength="14" class="validate">
                        </div>
                      </div>
                      <br />
                      <div class="row">
                      <div class="input-field">
                        </div>
                        <input type="hidden" id="id" name="id" value="<?php echo $funcionario->id; ?>">
                        <input type="hidden" id="action" name="action" value="1">
                        <?php if ($funcionario->id > 0){ ?>
                        <div class="input-field col s2">
                            <a href="<?php echo app::dominio; ?>consulta_funcionarios.php"  class="waves-effect waves-light btn">Voltar</a>
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

<script src="<?php echo app::dominio; ?>view/assets/js/funcionarios/funcionario.js"></script>

