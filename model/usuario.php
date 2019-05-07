<?php
require_once('app.php');
require_once('funcionalidadeConst.php');

class usuario extends app
{
	public $usuarioid;
	public $nome;
	public $email;
	public $id_perfilusuario;
	public $reset_senha;
	public $status;
	public $senha;
	public $msg;

	public function login()
	{
		$conn = $this->getDB->mysqli_connection;		
		$query = sprintf("SELECT usuarioid, nome, email, id_perfilusuario, reset_senha FROM usuarios A WHERE A.email = '%s' AND A.senha = '%s' AND A.status = '%s'", 
			$this->email, md5($this->senha), $this::STATUS_SISTEMA_ATIVO);

		if (!$result = $conn->query($query)) {
			return false;	
		}

		if (!empty($row = $result->fetch_array(MYSQLI_ASSOC))){

			$_SESSION['folha']['usuarioid'] 					= $row['usuarioid'];
 			$_SESSION['folha']['nome_usuario'] 	   				= $row['nome'];
 			$_SESSION['folha']['email_usuario'] 				= $row['email'];
 			$_SESSION['folha']['id_perfilusuario'] 				= $row['id_perfilusuario'];
 			$_SESSION['folha']['reset_senha'] 					= $row['reset_senha'];
 			$_SESSION['folha']['id_empresa'] 					= '';
 			$_SESSION['folha']['filtro']['status_id'] 			= '';
			$_SESSION['folha']['filtro']['id'] 					= '';
		    $_SESSION['folha']['filtro']['solicitante'] 		= '';
			$_SESSION['folha']['filtro']['funcionario'] 		= '';
			$_SESSION['folha']['filtro']['data_busca_periodo'] 	= '';
 			
 			if (!empty($row['usuarioid'])) {
 				$_SESSION['folha']['usuarioid']     = $row['usuarioid'];
 			}
 			
 			$_SESSION['folha']['logado'] 				= 1;	
 			
 			return true;		
		}

		$this->msg = 'Login inválido';
		return false;
	}

	public function montaSelectEmpresaTeste($selected=0)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT id,razao_social FROM empresa WHERE status = '%s' ORDER BY razao_social", $this::STATUS_SISTEMA_ATIVO);

		if($result = $conn->query($query))
		{
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			echo sprintf("<option %s value='%d'>%s</option>\n", $selected == $row['id'] ? "selected" : "",
			$row['id'], $row['razao_social']);
		}
	}

	public function LoadUsuarioSystem($id)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT usuarioid,nome FROM usuarios WHERE status = '%s' AND usuarioid = %d", $this::STATUS_SISTEMA_ATIVO, $id);

		if($result = $conn->query($query))
		{
			$row = $result->fetch_array(MYSQLI_ASSOC);
			return $row;
		}
	}
}

?>