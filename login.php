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
		header('LOCATION:/');
		exit();
	}	
}

if (isset($_SESSION) && !empty($_SESSION) && !$logout) {
	@session_destroy();
	header('LOCATION:'.funcionalidadeConst::LINK_PLATAFORM);
}

if (isset($_SESSION) && !empty($_SESSION) && $logout) {
	@session_destroy();
	header('LOCATION:'.funcionalidadeConst::LINK_PLATAFORM.'login.php');
}

if (!isset($_SESSION['logado']) && empty($_SESSION['logado'])) {
	@session_destroy();
	header('LOCATION:'.funcionalidadeConst::LINK_PLATAFORM.'login.php');
}

?>