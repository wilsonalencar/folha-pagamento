<?php
require_once('model/empresa.php');
require_once('model/evento.php');

define('SAVE', 1);
define('GET', 2);
define('DEL', 3);

$evento 						    	= new evento;
$empresa 						    	= new empresa;
$empresa->id					    	= $empresa->getRequest('id', 0);
$empresa->razao_social			    	= $empresa->getRequest('razao_social', '');
$empresa->nome_fantasia			    	= $empresa->getRequest('nome_fantasia', '');
$empresa->cnpj					    	= $empresa->getRequest('cnpj', '');
$empresa->status				     	= $empresa->getRequest('status', 'A');
$empresa->id_eventos				 	= $empresa->getRequest('id_eventos', '');

$msg 							    	= $empresa->getRequest('msg', '');	
$success 						    	= $empresa->getRequest('success', '');	
$action 						 		= $empresa->getRequest('action', 0);
    
if ($action == SAVE) {
	$success = $empresa->save();
	$msg     = $empresa->msg; 
	
	if ($success) {
		header("LOCATION:/folha-pagamento/empresas.php?id=".$empresa->id."&msg=".$msg."&success=".$success);
	}
}

if ($action == GET) {
	echo json_encode(array('success'=>$empresa->get($empresa->getRequest('id')), 'msg'=>$empresa->msg, 'data'=>$empresa->array));
	exit;
}

if ($action == DEL) {
	$success = $empresa->deleta($empresa->id);
	$msg = $empresa->msg;
	require_once('consulta_empresas.php');
	exit;
}

require_once('view/empresas/frm_empresa.php');
?>