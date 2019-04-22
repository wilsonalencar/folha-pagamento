<?php
  require_once(app::path.'view/header.php');
?>


<div id="page-wrapper">
<?php
	if (!isset($_GET['id_empresa']) || empty($_GET['id_empresa'])) {
?>
	<div class="header"> 
		<div class="card" align="center">
            <h4 class="page-header">
            	<b>Início</b> / Selecionar Empresa
            </h4>
		</div>								
	</div>

    <div class="col-md-12">
	    <div class="card row">
	    	<h4>&#8198&#8198&#8198&#8198&#8198&#8198<b>Selecionar Empresa</b></h4><hr>
		<div class="col-md-10">
            <select id="id_empresa" class="form-control">
                <option value="" disabled selected="">Empresas</option>
                <?php $empresa->montaSelectEmpresa($_SESSION['folha']['id_empresa']); ?>
            </select>
		</div>
		<div class="col-md-1">
	        <a href="#" onclick="submitfunction()" id="submit" class="waves-effect waves-light btn">SELECIONAR</a>
		</div>
		<br>
		<br>
		<br>
		</div>
	</div>
<form action="" method="GET" id="submitEmpresa">
	<input type="hidden" name="id_empresa" id="id_empresa_selected">
</form>
<?php
  } else {
  	if (isset($_GET['id_empresa']) && !empty($_GET['id_empresa'])) {
      $_SESSION['folha']['id_empresa'] = $_GET['id_empresa'];
  	}
  }
  require_once(app::path.'view/footer.php');
?>


<script type="text/javascript">
	
function submitfunction(){
    
    var empresa = document.getElementById("id_empresa").value;

    if (empresa == '') {
        alert('Favor selecionar uma empresa');
        return false;
    }

    $('#id_empresa_selected').val($('#id_empresa').val());
    $('#submitEmpresa').submit();
};

</script>