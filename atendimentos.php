<?php
require_once('model/solicitacao.php');
require_once('model/usuario.php');

define('SAVE', 1);
define('GET', 2);
define('DEL', 3);
define('FILTRA', 4);


$solicitacao 				= new solicitacao;
$usuario 					= new usuario;

$usuario_session 			= $usuario->LoadUsuarioSystem($_SESSION['folha']['usuarioid']);

$solicitacao->id									= $solicitacao->getRequest('id', 0);
$solicitacao->status_id								= $solicitacao->getRequest('status_id', '');
$solicitacao->solicitante							= $solicitacao->getRequest('solicitante', '');
$solicitacao->funcionario							= $solicitacao->getRequest('funcionario', '');
$solicitacao->data_busca_periodo					= $solicitacao->getRequest('data_busca_periodo', '');
$solicitacao->id_usuarioatendente					= $solicitacao->getRequest('id_usuarioatendente', '');
$solicitacao->data_inicio_atend						= $solicitacao->getRequest('data_inicio_atend', '');
$solicitacao->data_fim_atend						= $solicitacao->getRequest('data_fim_atend', '');
$solicitacao->data_encerramento						= $solicitacao->getRequest('data_encerramento', '');
$solicitacao->aceite_encerramento					= $solicitacao->getRequest('aceite_encerramento', '');
$solicitacao->descricao_atendimento					= $solicitacao->getRequest('descricao_atendimento', '');

$msg 											= $solicitacao->getRequest('msg', '');	
$success 										= $solicitacao->getRequest('success', '');	
$action 										= $solicitacao->getRequest('action', 0);

if ($action == SAVE) {
	$success = $solicitacao->saveAtendimento();
	$msg     = $solicitacao->msg; 
	
	if ($success) {
		$msg = $solicitacao->msg;
		require_once('consulta_solicitacao.php');
		exit;
	}
}

if ($action == GET) {
	$id = $solicitacao->id;
	require_once('view/atendimentos/frm_detalhe_solic.php');
	exit;
}

if ($action == DEL) {
	$success = $solicitacao->deleta($solicitacao->id);
	$msg = $solicitacao->msg;
	require_once('consulta_solicitacao.php');
	exit;
}

if (empty($_POST['id']) && empty($msg) && !$action == GET) {
	header("LOCATION:/folha-pagamento/".app::dominio.$_SESSION['folha']['previous_page']);
}


require_once('view/atendimentos/frm_atendimento.php');
?>