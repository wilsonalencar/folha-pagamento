<?php
require_once('model/usuario.php');
require_once('model/empresa.php');

$empresa = new empresa;
$usuario = new usuario;
$usuario->email = $usuario->getRequest('email', '');
$usuario->senha = $usuario->getRequest('senha', '');
$msg = '';
$logout = $usuario->getRequest('logout', false);

if (!empty($_POST)) {
	$success = $usuario->login();
	$msg = $usuario->msg;
	
	if ($success) {	
		header('LOCATION:/folha-pagamento/');
		exit();
	}	
}

if (isset($_SESSION['folha']) && !empty($_SESSION['folha']) && !$logout) {
	$_SESSION['folha'] = array();
	header('LOCATION:/');die();
}

if (isset($_SESSION['folha']) && !empty($_SESSION['folha']) && $logout) {
	@session_destroy();
	header('LOCATION:/login.php');die();
}

if (!isset($_SESSION['folha']['logado']) && empty($_SESSION['folha']['logado'])) {
	@session_destroy();
	header('LOCATION:/login.php');die();
}

?>