<?php
require_once('model/funcionario.php');
require_once('model/empresa.php');

define('SAVE', 1);
define('GET', 2);
define('DEL', 3);

$empresa  	 								= new empresa;
$funcionario 						    	= new funcionario;
$funcionario->id							= $funcionario->getRequest('id', 0);
$funcionario->codigo			 		   	= $funcionario->getRequest('codigo', '');
$funcionario->nome			    			= $funcionario->getRequest('nome', '');
$funcionario->cpf							= $funcionario->getRequest('cpf', '');
$funcionario->empresa_id					= $funcionario->getRequest('empresa_id', '');
$funcionario->data_cadastro					= $funcionario->getRequest('data_cadastro', '');

$msg 							    		= $funcionario->getRequest('msg', '');	
$success 						    		= $funcionario->getRequest('success', '');	
$action 						 			= $funcionario->getRequest('action', 0);
    
if ($action == SAVE) {
	$success = $funcionario->save();
	$msg     = $funcionario->msg; 
	
	if ($success) {
		header("LOCATION:/folha-pagamento/funcionarios.php?id=".$funcionario->id."&msg=".$msg."&success=".$success);
	}
}

if ($action == GET) {
	echo json_encode(array('success'=>$funcionario->get($funcionario->getRequest('id')), 'msg'=>$funcionario->msg, 'data'=>$funcionario->array));
	exit;
}

if ($action == DEL) {
	$success = $funcionario->deleta($funcionario->id);
	$msg = $funcionario->msg;
	require_once('consulta_funcionarios.php');
	exit;
}

require_once('view/funcionarios/frm_funcionario.php');
?>