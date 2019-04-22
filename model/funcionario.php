<?php
require_once('app.php');

class funcionario extends app
{
	public $id;
	public $codigo;
	public $nome;
	public $cpf;
	public $empresa_id;
	public $data_cadastro;

	public function montaSelectFuncionario()
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT id,nome FROM funcionarios ORDER BY nome");

		if($result = $conn->query($query))
		{
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			echo sprintf("<option %s value='%d'>%s</option>\n", $selected == $row['id'] ? "selected" : "",
			$row['id'], $row['nome']);
		}
	}

	public function montaSelectFuncionarioSolicitacao($selected=0)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT id, codigo, nome FROM funcionarios WHERE empresa_id = '".$_SESSION['id_empresa']."'");

		if($result = $conn->query($query))
		{
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			echo sprintf("<option %s value='%d'>%s</option>\n", $selected == $row['id'] ? "selected" : "",
			$row['id'], $row['codigo']. " - ". utf8_decode($row['nome']));
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
		if (empty($this->empresa_id)) {
			$this->msg = 'Informar a Empresa.';
			return false;
		}

		if (empty($this->codigo)) {
			$this->msg = 'Informar o Código.';
			return false;
		}

		if (empty($this->nome)) {
			$this->msg = 'Informar o Nome.';
			return false;
		}

		if (empty($this->cpf)) {
			$this->msg = 'Informar o CPF.';
			return false;
		}

		if (!$this->validaCPF($this->cpf)) {
			$this->msg = "CPF é inválido, favor verificar.";
			return false;
		}

		if (!$this->checkFuncionarioExiste()) {
			return false;
		}

		if (!$this->checkCodigoExiste()) {
			return false;
		}
		
		return true;
	}

	public function update()
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf(" UPDATE funcionarios SET codigo = '%s', nome = '%s', cpf = '%s', empresa_id = '%d' WHERE id = %d", 
			$this->codigo, utf8_encode($this->nome), $this->cpf, $this->empresa_id, $this->id);	
	
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
		$query = sprintf(" INSERT INTO funcionarios (codigo, nome, cpf, empresa_id, data_cadastro)
		VALUES ('%s','%s','%s',%d,'%s')", 
			$this->codigo, utf8_encode($this->nome), $this->cpf, $this->empresa_id, date('Y-m-d h:i:s'));	
		
		if (!$conn->query($query)) {
			$this->msg = "Ocorreu um erro, contate o administrador do sistema!";
			return false;	
		}

		$this->msg = "Registros inseridos com sucesso!";
		return true;
	}

	public function lista()
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT A.id, A.codigo, A.nome, A.cpf, A.data_cadastro, B.razao_social as nome_empresa FROM funcionarios A INNER JOIN empresa B ON A.empresa_id = B.id");
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro no carregamento do funcionário";	
			return false;	
		}
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$timestamp = strtotime($row['data_cadastro']);
			$row['data_cadastro'] = date("d/m/Y", $timestamp);
			$this->array[] = $row;
		}
	}

	private function checkFuncionarioExiste()
	{
		$conn = $this->getDB->mysqli_connection;		
		$query = sprintf("SELECT cpf FROM funcionarios WHERE cpf = '%s' AND id <> %d", $this->cpf, $this->id);	
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro durante a verificação do funcionário.";
			return false;	
		}
		
		if (!empty($result->fetch_array(MYSQLI_ASSOC))) {
			$this->msg = "Funcionário já existente.";
			return false;			
		}
		return true;
	}

	private function checkCodigoExiste()
	{
		$conn = $this->getDB->mysqli_connection;		
		$query = sprintf("SELECT * FROM funcionarios WHERE codigo = '%s' AND empresa_id = %d AND id <> %d", $this->codigo, $this->empresa_id, $this->id);	
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro durante a verificação do funcionário.";
			return false;	
		}
		
		if (!empty($result->fetch_array(MYSQLI_ASSOC))) {
			$this->msg = "Funcionário já existente para essa empresa.";
			return false;			
		}
		return true;
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
		$query = sprintf("DELETE FROM funcionarios WHERE id = %d ", $id);
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Não foi possível excluir o funcionário.";	
			return false;	
		}
		$this->msg = 'Registro excluido com sucesso';
		return true;	
	}

	private function checkVinculo()
	{
		$conn = $this->getDB->mysqli_connection;		
		$query = sprintf("SELECT * FROM solicitacaofuncionario WHERE funcionario_id = '%d'", $this->id);
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro durante a verificação do vinculo do funcionário.";
			return false;	
		}
		
		if (!empty($result->fetch_array(MYSQLI_ASSOC))) {
			$this->msg = "O funcionário tem um vinculo externo, exclusão não permitida.";
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
		$query = sprintf("SELECT id, codigo, nome, cpf, empresa_id FROM funcionarios WHERE  id =  %d ", $id);
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro no carregamento do funcionário";	
			return false;	
		}
		$this->array = $result->fetch_array(MYSQLI_ASSOC);
		$this->array['nome'] = utf8_decode($this->array['nome']);
		$this->msg = 'Registro carregado com sucesso';
		return true;
	}
}

?>