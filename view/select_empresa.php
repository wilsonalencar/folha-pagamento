<?php
  require_once(app::path.'view/header.php');
?>
<div id="page-wrapper">
	<div class="header"> 
		<div class="card" align="center">
            <h4 class="page-header">
            	<b>Início</b> / Selecionar Empresa
            </h4>
		</div>								
	</div>
    <div class="col-s12">
	    <div class="card">
	    	&#8198
	    	<h4>&#8198&#8198&#8198&#8198&#8198&#8198<b>Selecionar Empresa</b></h4><hr>
	    	<div class="col-s5">
                <select id="empresa" name="id_empresa" class="form-control input-sm">
                  <option value="">Empresas</option>
                  <?php $empresa->montaSelectEmpresa($empresa->id); ?>
                </select>
                &#8198
	    	</div>
		</div>
	</div>

<?php
  require_once(app::path.'view/footer.php');
?>