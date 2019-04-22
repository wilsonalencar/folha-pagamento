<?php
require_once('app.php');

class empresa extends app
{
	public $id;
	public $razao_social;
	public $nome_fantasia;
	public $status;
	public $cnpj;
	public $id_eventos;
	public $empresa_system;

	public function montaSelectEmpresa($selected=0)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = " SELECT A.id, A.razao_social FROM empresa A where A.status = '".$this::STATUS_SISTEMA_ATIVO."' and A.id in (select id_empresa from permissaoempresas where id_usuario = ".$_SESSION['usuarioid'].") order by A.razao_social;";

		if($result = $conn->query($query))
		{
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			echo sprintf("<option %s value='%d'>%s</option>\n", $selected == $row['id'] ? "selected" : "",
			$row['id'], utf8_decode($row['razao_social']));
		}
	}

	public function montaSelectEventoEmpresa($selected=0)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT A.id, A.descricao FROM eventos A INNER JOIN eventosempresa B ON A.id =  B.evento_id WHERE A.status = '%s' AND B.empresa_id = %d ORDER BY A.descricao", $this::STATUS_SISTEMA_ATIVO, $_SESSION['id_empresa']);

		if($result = $conn->query($query))
		{
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			echo sprintf("<option %s value='%d'>%s</option>\n", $selected == $row['id'] ? "selected" : "",
			$row['id'], utf8_decode($row['descricao']));
		}
	}

	public function LoadEmpresaSystem($id)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT id,razao_social FROM empresa WHERE status = '%s' AND id = %d", $this::STATUS_SISTEMA_ATIVO, $id);

		if($result = $conn->query($query))
		{
			$row = $result->fetch_array(MYSQLI_ASSOC);
			return $row;
		}
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

	private function check()
	{
		if (empty($this->razao_social)) {
			$this->msg = 'Informar a Razão Social.';
			return false;
		}

		if (!$this->validaCNPJ($this->cnpj)) {
			$this->msg = 'Favor inserir um CNPJ válido.';
			return false;
		}

		if (!$this->checkEmpresaExiste()) {
			return false;
		}
		
		return true;
	}

	public function update()
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf(" UPDATE empresa SET razao_social = '%s', nome_fantasia = '%s', cnpj = '%s', status = '%s' WHERE id = %d", 
			utf8_encode($this->razao_social), utf8_encode($this->nome_fantasia), $this->cnpj, $this->status, $this->id);	
	
		if (!$conn->query($query)) {
			$this->msg = "Ocorreu um erro, contate o administrador do sistema!";
			return false;	
		}

		$this->msg = "Registros atualizados com sucesso!";
		return true;
	}

	public function insert()
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf(" INSERT INTO empresa (razao_social, nome_fantasia, cnpj, status)
		VALUES ('%s','%s','%s','%s')", 
			utf8_encode($this->razao_social), utf8_encode($this->nome_fantasia), $this->cnpj, $this->status);	
		
		if (!$conn->query($query)) {
			$this->msg = "Ocorreu um erro, contate o administrador do sistema!";
			return false;	
		}

		$lastinsertID = $conn->insert_id;

		if (is_array($this->id_eventos)) {
			foreach ($this->id_eventos as $l => $evento) {
				$queryEve = "INSERT INTO eventosempresa (empresa_id, evento_id) VALUES (".$lastinsertID.",".$evento.")";
				if (!$conn->query($queryEve)) {
					$this->msg = "Ocorreu um erro no sistema, contate o administrador do sistema!";
					return false;	
				}
			}
		}

		$this->msg = "Registros inseridos com sucesso!";
		return true;
	}

	public function lista()
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT id, razao_social, nome_fantasia, cnpj, status FROM empresa");
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro no carregamento da empresa";	
			return false;	
		}
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$row['status'] = $this->formatStatus($row['status']);
			$this->array[] = $row;
		}
	}

	private function checkEmpresaExiste()
	{
		$conn = $this->getDB->mysqli_connection;		
		$query = sprintf("SELECT cnpj FROM empresa WHERE cnpj = '%s' AND id <> %d", $this->cnpj, $this->id);	
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro durante a verificação da empresa.";
			return false;	
		}
		
		if (!empty($result->fetch_array(MYSQLI_ASSOC))) {
			$this->msg = "Empresa já existente.";
			return false;			
		}
		return true;
	}

	private function formatStatus($status)
	{
		if ($status == $this::STATUS_SISTEMA_ATIVO) {
			return "Ativo";
		}
		return "Inativo";
	}

	public function deleta($id)
	{
		if (!$id) {
			return false;
		}

		if (!$this->checkVinculo()) {
		 	return false;
		}
		
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("DELETE FROM empresa WHERE id = %d ", $id);
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Não foi possível excluir a empresa.";	
			return false;	
		}
		$this->msg = 'Registro excluido com sucesso';
		return true;	
	}

	private function checkVinculo()
	{
		$conn = $this->getDB->mysqli_connection;		
		$query = sprintf("SELECT * FROM solicitacao WHERE empresa_id = %d", $this->id);
		$sql = sprintf("SELECT * FROM funcionarios WHERE empresa_id = %d", $this->id);	
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro durante a verificação do vinculo da empresa.";
			return false;	
		}
		
		if (!empty($result->fetch_array(MYSQLI_ASSOC))) {
			$this->msg = "A empresa tem um vinculo externo com a tabela solicitação, exclusão não permitida.";
			return false;			
		}

		if (!$result_sql = $conn->query($sql)) {
			$this->msg = "Ocorreu um erro durante a verificação do vinculo da empresa.";
			return false;	
		}
		
		if (!empty($result_sql->fetch_array(MYSQLI_ASSOC))) {
			$this->msg = "A empresa tem um vinculo externo com a tabela funcionários, exclusão não permitida.";
			return false;			
		}

		return true;
	}

	public function get($id)
	{
		if (!$id) {
			return false;
		}
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT id, razao_social, nome_fantasia, cnpj, status FROM empresa WHERE  id =  %d ", $id);
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro no carregamento da empresa";	
			return false;	
		}
		$this->array = $result->fetch_array(MYSQLI_ASSOC);
		$this->array['razao_social'] = utf8_decode($this->array['razao_social']);
		$this->array['nome_fantasia'] = utf8_decode($this->array['nome_fantasia']);
		$this->msg = 'Registro carregado com sucesso';
		return true;
	}
}

?>