<?php
require_once('model/solicitacao.php');
require_once('model/funcionario.php');
require_once('model/empresa.php');
require_once('model/usuario.php');
require_once('model/anexo.php');

define('SAVE', 1);
define('GET', 2);
define('DEL', 3);


$empresa 					= new empresa;
$usuario 					= new usuario;
$funcionario 				= new funcionario;
$solicitacao 				= new solicitacao;

$empresa_session 			= $empresa->LoadEmpresaSystem($_SESSION['folha']['id_empresa']);
$usuario_session 			= $usuario->LoadUsuarioSystem($_SESSION['folha']['usuarioid']);

$solicitacao->id									= $solicitacao->getRequest('id', 0);
$solicitacao->empresa_id 							= $solicitacao->getRequest('empresa_id', '');
$solicitacao->id_usuariosolicitante 				= $solicitacao->getRequest('id_usuariosolicitante', '');
$solicitacao->data_solicitacao  					= $solicitacao->getRequest('data_solicitacao', '');
$solicitacao->evento_id  							= $solicitacao->getRequest('evento_id', '');
$solicitacao->descricao_solicitacao  				= $solicitacao->getRequest('descricao_solicitacao', '');
$solicitacao->tipo									= $solicitacao->getRequest('tipo', '');
$solicitacao->funcionario_id						= $solicitacao->getRequest('funcionario_id', '');
$solicitacao->status_id								= $solicitacao->getRequest('status_id', '');
$solicitacao->id_usuarioatendente					= $solicitacao->getRequest('id_usuarioatendente', '');
$solicitacao->data_inicio_atend						= $solicitacao->getRequest('data_inicio_atend', '');
$solicitacao->data_fim_atend						= $solicitacao->getRequest('data_fim_atend', '');
$solicitacao->data_encerramento						= $solicitacao->getRequest('data_encerramento', '');
$solicitacao->aceite_encerramento					= $solicitacao->getRequest('aceite_encerramento', '');


$excluirAnexo 						= $solicitacao->getRequest('excluir_anexo');

$msg 								= $solicitacao->getRequest('msg', '');	
$success 							= $solicitacao->getRequest('success', '');	
$action 							= $solicitacao->getRequest('action', 0);

if ($action == SAVE) {

	if ((int)$excluirAnexo) {
		if (file_exists($_POST['file'])) {
			unlink($_POST['file']);
		}

		$msg = 'Registro atualizado';
		$success = true;

	} else {

		if (!empty($_FILES['documentos']['name'][0])) {
			$solicitacao->fileSolic = $_FILES['documentos'];
		}

		$success = $solicitacao->save();
		$msg     = $solicitacao->msg; 
	}

	if ($success) {
		header("LOCATION:solicitacoes.php?id=".$solicitacao->id."&msg=".$msg."&success=".$success);
	}
}

if ($action == GET) {
	echo json_encode(array('success'=>$solicitacao->get($solicitacao->getRequest('id')), 'msg'=>$solicitacao->msg, 'data'=>$solicitacao->array));
	exit;
}

if ($action == DEL) {
	$success = $solicitacao->deleta($solicitacao->id);
	$msg = $solicitacao->msg;
	require_once('consulta_solicitacao.php');
	exit;
}

require_once('view/solicitacao/frm_solicitacao.php');
?>