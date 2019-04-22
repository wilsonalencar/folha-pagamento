<?php
require_once('model/app.php');
require_once('model/empresa.php');

$empresa = new empresa();

if (empty($_SESSION['folha']['logado'])) {
	header('LOCATION:' .funcionalidadeConst::LINK_PLATAFORM);
} 

require_once('view/index.php');
?> 	