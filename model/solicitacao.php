<?php
require_once('app.php');

class solicitacao extends app
{
	public $id;
	public $empresa_id;
	public $id_usuariosolicitante;
	public $evento_id;
	public $descricao_solicitacao;
	public $tipo;
	public $funcionario_id;
	public $status_id;
	public $id_usuarioatendente;
	public $data_inicio_atend;
	public $data_fim_atend;
	public $descricao_atendimento;
	public $data_encerramento;
	public $aceite_encerramento;
	public $insertID;
	public $fileSolic = false;


	public function montaSelectStatusSolicitacao($selected=0)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT id,descricao FROM statussolicitacao ORDER BY descricao");

		if($result = $conn->query($query))
		{
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			echo sprintf("<option %s value='%d'>%s</option>\n", $selected == $row['id'] ? "selected" : "",
			$row['id'], utf8_encode($row['descricao']));
		}
	}

	public function montaSelectNumSolicitacao($selected=0)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT id FROM solicitacao WHERE empresa_id = %d", $_SESSION['folha']['id_empresa']);

		if($result = $conn->query($query))
		{
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			echo sprintf("<option %s value='%d'>%s</option>\n", $selected == $row['id'] ? "selected" : "",
			$row['id'], $row['id']);
		}
	}

	public function montaSelectSolicitante($selected=0)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT DISTINCT A.usuarioid, A.nome FROM usuarios A INNER JOIN solicitacao B ON B.id_usuariosolicitante = A.usuarioid WHERE B.empresa_id = %d ORDER BY A.nome ", $_SESSION['folha']['id_empresa']);

		if($result = $conn->query($query))
		{
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			echo sprintf("<option %s value='%d'>%s</option>\n", $selected == $row['usuarioid'] ? "selected" : "",
			$row['usuarioid'], utf8_decode($row['nome']));
		}
	}

	private function check(){

		if (empty($this->evento_id)) {
			$this->msg = "Favor informar o Evento.";
			return false;
		}

		if (empty($this->tipo)) {
			$this->msg = "Favor informar um Tipo.";
			return false;
		}
		
		if (empty($this->descricao_solicitacao)) {
			$this->msg = "Favor Inserir uma Descrição.";
			return false;
		}

		if ($this->tipo == 'F' && empty($this->funcionario_id)) {
			$this->msg = "Favor informar um funcionário.";
			return false;	
		}

		if (!$this->checkExiste()) {
			return false;
		}
		
		return true;
	}

	private function checkAtendimento(){
		
		if ($this->status_id == 3 && empty($this->data_fim_atend)) {
			$this->msg = "Favor informar a Data de Conclusão.";
			return false;
		}

		if (empty($this->descricao_atendimento)) {
			$this->msg = "Favor informar a Descrição do Atendimento.";
			return false;
		}
		
		if (empty($this->status_id)) {
			$this->msg = "Favor informar um Status.";
			return false;
		}

		if (!empty($this->data_fim_atend)) {;

			if ($this->data_fim_atend < $this->data_inicio_atend) {
				$this->msg = "Data de Conclusão Inválida.";
				return false;
			}
		}
		
		return true;
	}


	private function checkExiste()
	{
		$conn = $this->getDB->mysqli_connection;		
		$query = sprintf("SELECT * FROM solicitacao WHERE id = %d", $this->id);	
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro durante a verificação da solicitação";
			return false;	
		}
		
		if (!empty($result->fetch_array(MYSQLI_ASSOC))) {
			$this->msg = "Solicitação já cadastrada";
			return false;			
		}
		return true;
	}

	public function save()
	{
		if (!$this->check()) {
		 	return false;
		}

		if ($this->id > 0) {
			return $this->update();
		}
		return $this->insert();
	}

	public function saveAtendimento()
	{
		if (!$this->checkAtendimento()) {
		 	return false;
		}

		return $this->insertAtendimento();
	}

	public function insert()
	{	
		$conn = $this->getDB->mysqli_connection;

		$conn->autocommit(FALSE);

		$query = sprintf(" INSERT INTO solicitacao (empresa_id, id_usuariosolicitante, data_solicitacao, evento_id, descricao_solicitacao, tipo, status_id)
		VALUES (%d, %d, '%s', %d, '%s', '%s', %d)", 
			$this->empresa_id, $this->id_usuariosolicitante, $this->data_solicitacao, $this->evento_id, utf8_encode($this->descricao_solicitacao), $this->tipo, funcionalidadeConst::AGUARADADO_ATEND);

		if (!$conn->query($query)) {
			$this->msg = "Ocorreu um erro, contate o administrador do sistema!";
			return false;	
		}

		$lastinsertID = $conn->insert_id;

		if (!empty($lastinsertID)) {
			$sql = sprintf(" INSERT INTO solicitacaofuncionario (solicitacao_id, funcionario_id)
			VALUES (%d, %d)", 
				$lastinsertID, $this->funcionario_id);

			if (!$conn->query($sql)) {
				$this->msg = "Ocorreu um erro no sistema, contate o administrador do sistema!";
				return false;	
			}
		}

		if (!empty($this->fileSolic)) {

			$an = new anexo;
			$an->file = $this->fileSolic;
			$an->path = 'arquivo_solicitacao';
			$an->name = $lastinsertID;
			$an->typeFile = $an::FILE_SOLICITACAO;

			if (!$an->insert()) {
				$this->msg = 'Ocorreu um erro, '. $an->msg;
				return false;
			}
		}

		$conn->commit();

		$this->msg = "Registro inserido com sucesso, o número da sua solicitação é ".$lastinsertID."!";
		return true;
	}

	public function insertAtendimento()
	{	
		$conn = $this->getDB->mysqli_connection;

		if (empty($this->data_fim_atend)) {
			$this->data_fim_atend = date('Y-m-d');
		}

		$conn->autocommit(FALSE);

		$query = sprintf(" UPDATE solicitacao SET status_id = %d, id_usuarioatendente = %d, data_inicio_atend = '%s', data_fim_atend = '%s', data_encerramento = '%s', aceite_encerramento = '%s', descricao_atendimento = '%s' WHERE id = %d", 
			$this->status_id, $this->id_usuarioatendente, $this->data_inicio_atend, $this->data_fim_atend, $this->data_fim_atend, utf8_encode($this->aceite_encerramento), utf8_encode($this->descricao_atendimento), $this->id);

		if (!$conn->query($query)) {
			$this->msg = "Ocorreu um erro, contate o administrador do sistema!";
			return false;	
		}

		$conn->commit();

		$this->msg = "Solicitação atualizada com successo!";
		return true;
	}

	public function get($id)
	{	
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT A.id, B.razao_social as nome_empresa, C.nome as usuario_solicitante, A.data_solicitacao, A.evento_id, A.status_id , A.tipo, A.descricao_solicitacao, A.data_fim_atend, A.descricao_atendimento, D.funcionario_id, E.descricao as desc_evento, F.nome as nome_funcionario FROM solicitacao A INNER JOIN empresa B ON A.empresa_id = B.id INNER JOIN usuarios C ON A.id_usuariosolicitante = C.usuarioid INNER JOIN solicitacaofuncionario D ON A.id = D.solicitacao_id INNER JOIN eventos E ON A.evento_id = E.id INNER JOIN funcionarios F ON D.funcionario_id = F.id WHERE A.id = %d", $id);

		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro no carregamento da solicitação";	
			return false;	
		}

		$row = $result->fetch_array(MYSQLI_ASSOC);

		if (empty($row)) {
			$query_geral = sprintf("SELECT A.id, B.razao_social as nome_empresa, C.nome as usuario_solicitante, A.data_solicitacao, A.evento_id, A.status_id , A.tipo, A.descricao_solicitacao, A.data_fim_atend, A.descricao_atendimento, E.descricao as desc_evento FROM solicitacao A INNER JOIN empresa B ON A.empresa_id = B.id INNER JOIN usuarios C ON A.id_usuariosolicitante = C.usuarioid INNER JOIN solicitacaofuncionario D ON A.id = D.solicitacao_id INNER JOIN eventos E ON A.evento_id = E.id WHERE A.id = %d", $id);

				if (!$result = $conn->query($query_geral)) {
					$this->msg = "Ocorreu um erro no carregamento da solicitação";	
					return false;	
				}

				$row_geral = $result->fetch_array(MYSQLI_ASSOC);

				$row_geral['nome_empresa'] = utf8_decode($row_geral['nome_empresa']);
				$row_geral['usuario_solicitante'] = utf8_decode($row_geral['usuario_solicitante']);
				$row_geral['descricao_solicitacao'] = utf8_decode($row_geral['descricao_solicitacao']);
				$row_geral['desc_evento'] = utf8_decode($row_geral['desc_evento']);
				$timestamp = strtotime($row_geral['data_solicitacao']);
				$row_geral['data_solicitacao'] = date("d/m/Y", $timestamp);

				if (!empty($row_geral['data_fim_atend'])) {
					$date_atend = strtotime($row_geral['data_fim_atend']);
					$row_geral['data_fim_atend'] = date("d/m/Y", $date_atend);
				}

				$this->array = $row_geral;
				$this->msg = 'Registro carregado com sucesso';
				return true;
		}else {

			$row['nome_empresa'] = utf8_decode($row['nome_empresa']);
			$row['usuario_solicitante'] = utf8_decode($row['usuario_solicitante']);
			$row['descricao_solicitacao'] = utf8_decode($row['descricao_solicitacao']);
			$row['desc_evento'] = utf8_decode($row['desc_evento']);
			$row['nome_funcionario'] = utf8_decode($row['nome_funcionario']);
			$timestamp = strtotime($row['data_solicitacao']);
			$row['data_solicitacao'] = date("d/m/Y", $timestamp);

			if (!empty($row['data_fim_atend'])) {
				$date_atend = strtotime($row['data_fim_atend']);
				$row['data_fim_atend'] = date("d/m/Y", $date_atend);
			}

			$this->array = $row;
			$this->msg = 'Registro carregado com sucesso';
			return true;
		}
	}

	public function getAtendimento($id)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT A.id, B.razao_social as nome_empresa, C.nome as usuario_solicitante, A.data_solicitacao, A.evento_id, A.tipo, A.descricao_solicitacao, A.data_fim_atend, A.data_inicio_atend, A.descricao_atendimento, A.status_id, A.aceite_encerramento, D.funcionario_id, E.descricao as desc_evento, F.nome as nome_funcionario FROM solicitacao A INNER JOIN empresa B ON A.empresa_id = B.id INNER JOIN usuarios C ON A.id_usuariosolicitante = C.usuarioid INNER JOIN solicitacaofuncionario D ON A.id = D.solicitacao_id INNER JOIN eventos E ON A.evento_id = E.id INNER JOIN funcionarios F ON D.funcionario_id = F.id WHERE A.id = %d", $id);

		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro no carregamento do atendimento";	
			return false;	
		}

		$row = $result->fetch_array(MYSQLI_ASSOC);

		if (empty($row)) {
			$query_geral = sprintf("SELECT A.id, B.razao_social as nome_empresa, C.nome as usuario_solicitante, A.data_solicitacao, A.evento_id, A.tipo, A.descricao_solicitacao, A.data_fim_atend, A.data_inicio_atend, A.descricao_atendimento, A.status_id, A.aceite_encerramento, D.funcionario_id, E.descricao as desc_evento FROM solicitacao A INNER JOIN empresa B ON A.empresa_id = B.id INNER JOIN usuarios C ON A.id_usuariosolicitante = C.usuarioid INNER JOIN solicitacaofuncionario D ON A.id = D.solicitacao_id INNER JOIN eventos E ON A.evento_id = E.id WHERE A.id = %d", $id);

			if (!$result = $conn->query($query_geral)) {
					$this->msg = "Ocorreu um erro no carregamento da solicitação";	
					return false;	
				}

			$row_geral = $result->fetch_array(MYSQLI_ASSOC);

			$row_geral['nome_empresa'] = utf8_decode($row_geral['nome_empresa']);
			$row_geral['usuario_solicitante'] = utf8_decode($row_geral['usuario_solicitante']);
			$row_geral['descricao_solicitacao'] = utf8_decode($row_geral['descricao_solicitacao']);
			$row_geral['desc_evento'] = utf8_decode($row_geral['desc_evento']);

			if ($row_geral['data_fim_atend']) {
				$data_fim_atend_formated = strtotime($row_geral['data_fim_atend']);
				$row_geral['data_fim_atend'] = date("d/m/Y", $data_fim_atend_formated);
			}

			if ($row_geral['data_inicio_atend']) {
				$data_inicio_atend_formated = strtotime($row_geral['data_inicio_atend']);
				$row_geral['data_inicio_atend'] = date("d/m/Y", $data_inicio_atend_formated);
			}

			if ($row_geral['data_solicitacao']) {
				$timestamp = strtotime($row_geral['data_solicitacao']);
				$row_geral['data_solicitacao'] = date("d/m/Y", $timestamp);
			}

			$this->array = $row_geral;
			$this->msg = 'Registro carregado com sucesso';
			return true;
		}else {

			$row['nome_empresa'] = utf8_decode($row['nome_empresa']);
			$row['usuario_solicitante'] = utf8_decode($row['usuario_solicitante']);
			$row['descricao_solicitacao'] = utf8_decode($row['descricao_solicitacao']);
			$row['desc_evento'] = utf8_decode($row['desc_evento']);
			$row['nome_funcionario'] = utf8_decode($row['nome_funcionario']);

			if ($row['data_fim_atend']) {
				$data_fim_atend_formated = strtotime($row['data_fim_atend']);
				$row['data_fim_atend'] = date("d/m/Y", $data_fim_atend_formated);
			}

			if ($row['data_inicio_atend']) {
				$data_inicio_atend_formated = strtotime($row['data_inicio_atend']);
				$row['data_inicio_atend'] = date("d/m/Y", $data_inicio_atend_formated);
			}

			if ($row['data_solicitacao']) {
				$timestamp = strtotime($row['data_solicitacao']);
				$row['data_solicitacao'] = date("d/m/Y", $timestamp);
			}

			$this->array = $row;
			$this->msg = 'Registro carregado com sucesso';
			return true;
		}
	}

	public function lista()
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT A.id, A.data_solicitacao, A.id_usuariosolicitante, A.evento_id, B.nome as nome_usuario, C.descricao as evento, A.descricao_solicitacao, A.status_id, D.descricao as status FROM solicitacao A INNER JOIN usuarios B ON A.id_usuariosolicitante = B.usuarioid INNER JOIN eventos C ON A.evento_id = C.id INNER JOIN statussolicitacao D ON A.status_id = D.id WHERE A.empresa_id = %d AND A.status_id IN (1,2,3)", $_SESSION['folha']['id_empresa']);

		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro no carregamento das solicitações";	
			return false;	
		}
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$timestamp = strtotime($row['data_solicitacao']);
			$row['data_solicitacao'] = date("d/m/Y", $timestamp);
			$this->array[] = $row;
		}
	}

	public function listaAtendimento($array)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = "SELECT A.id, A.data_solicitacao, A.id_usuariosolicitante, A.evento_id, B.nome as nome_usuario, C.descricao as evento, A.descricao_solicitacao, A.status_id, D.descricao as status FROM solicitacao A INNER JOIN usuarios B ON A.id_usuariosolicitante = B.usuarioid INNER JOIN eventos C ON A.evento_id = C.id INNER JOIN statussolicitacao D ON A.status_id = D.id INNER JOIN solicitacaofuncionario E ON A.id = E.solicitacao_id WHERE A.empresa_id = ".$_SESSION['folha']['id_empresa'];

		if (!empty($array['clear'])) {
			$_SESSION['folha']['filtro']['status_id'] = '';
			$_SESSION['folha']['filtro']['id'] = '';
			$_SESSION['folha']['filtro']['funcionario'] = '';
			$_SESSION['folha']['filtro']['solicitante'] = '';
			$_SESSION['folha']['filtro']['data_busca_periodo'] = '';
		}
		
		if (!empty($array['status_id'])) {
			$_SESSION['folha']['filtro']['status_id'] = $array['status_id'];
		}
		$status_id = $_SESSION['folha']['filtro']['status_id'];

		if (!empty($status_id)) {
			$query .= ' AND A.status_id = '.$status_id;
		}

		if (!empty($array['id'])) {
			$_SESSION['folha']['filtro']['id'] = $array['id'];
		}
		$id = $_SESSION['folha']['filtro']['id'];

		if (!empty($id)) {
			$query .= ' AND A.id = '.$id;
		}

		if (!empty($array['solicitante'])) {
			$_SESSION['folha']['filtro']['solicitante'] = $array['solicitante'];
		}
		$solicitante = $_SESSION['folha']['filtro']['solicitante'];

		if (!empty($solicitante)) {
			$query .= ' AND A.id_usuariosolicitante = '.$solicitante;
		}

		if (!empty($array['funcionario'])) {
			$_SESSION['folha']['filtro']['funcionario'] = $array['funcionario'];
		}
		$funcionario = $_SESSION['folha']['filtro']['funcionario'];

		if (!empty($funcionario)) {
			$query .= ' AND E.funcionario_id = '.$funcionario;
		}

		if (!empty($array['data_busca_periodo'])) {
			$_SESSION['folha']['filtro']['data_busca_periodo'] = $array['data_busca_periodo'];
		}
		$data_busca_periodo = $_SESSION['folha']['filtro']['data_busca_periodo'];

		if (!empty($data_busca_periodo)) {
			$query .= ' AND DATE_FORMAT(A.data_solicitacao, "%Y-%m-%d") = "'.$data_busca_periodo.'"';
		}

		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro no carregamento das solicitações";	
			return false;	
		}
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$timestamp = strtotime($row['data_solicitacao']);
			$row['data_solicitacao'] = date("d/m/Y", $timestamp);
			$this->array[] = $row;
		}
	}
}

?>