<?php
require_once('model/evento.php');

define('SAVE', 1);
define('GET', 2);
define('DEL', 3);

$evento 							= new evento;
$evento->id							= $evento->getRequest('id', 0);
$evento->descricao					= $evento->getRequest('descricao', '');
$evento->status					 	= $evento->getRequest('status', 'A');

$msg 								= $evento->getRequest('msg', '');	
$success 							= $evento->getRequest('success', '');	
$action 							= $evento->getRequest('action', 0);

if ($action == SAVE) {
	$success = $evento->save();
	$msg     = $evento->msg; 
	
	if ($success) {
		header("LOCATION:eventos.php?id=".$evento->id."&msg=".$msg."&success=".$success);
	}
}

if ($action == GET) {
	$evento->get($evento->getRequest('id'));
	echo json_encode(array('success'=>$evento->get($evento->getRequest('id')), 'msg'=>$evento->msg, 'data'=>$evento->array));
	exit;
}

if ($action == DEL) {
	$success = $evento->deleta($evento->id);
	$msg = $evento->msg;
	require_once('consulta_eventos.php');
	exit;
}

require_once('view/eventos/frm_evento.php');
?>