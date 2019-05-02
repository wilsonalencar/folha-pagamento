<?php
require_once('app.php');

class evento extends app
{
	public $id;
	public $descricao;
	public $status;

	/*public function montaSelectEvento($selected=0)
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf("SELECT id,descricao FROM eventos WHERE status = '%s' ORDER BY descricao", $this::STATUS_SISTEMA_ATIVO);

		if($result = $conn->query($query))
		{
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			echo sprintf("<option %s value='%d'>%s</option>\n", $selected == $row['id'] ? "selected" : "",
			$row['id'], utf8_decode($row['descricao']));
		}
	}*/

	public function montaSelectEvento($id=0)
	{
		$conn = $this->getDB->mysqli_connection;
		$arr = array();
	
		if (!empty($id)) {
			$original = 'SELECT evento_id FROM eventosempresa where empresa_id = "'.$id.'"';
			if($result_original = $conn->query($original))
			{
				while ($evento = $result_original->fetch_array(MYSQLI_ASSOC)) {
					$arr[$evento['evento_id']] = 1;
				}
			}
		}

		$query = sprintf("SELECT id,descricao FROM eventos WHERE status = '%s' ORDER BY descricao", $this::STATUS_SISTEMA_ATIVO);
		if($result = $conn->query($query))
		{
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			echo sprintf("<option %s value='%d'>%s</option>\n", array_key_exists($row['id'], $arr) ? "selected" : "",
			$row['id'], utf8_decode($row['descricao']));
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
		if (empty($this->descricao)) {
			$this->msg = 'Informar a Descrição.';
			return false;
		}

		if (!$this->checkEvento()) {
			return false;
		}
		
		return true;
	}

	public function update()
	{
		$conn = $this->getDB->mysqli_connection;
		$query = sprintf(" UPDATE eventos SET descricao = '%s', status = '%s' WHERE id = %d", 
			utf8_encode($this->descricao), $this->status, $this->id);	
	
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
		$query = sprintf(" INSERT INTO eventos (descricao, status)
		VALUES ('%s','%s')", 
			utf8_encode($this->descricao), $this->status);	
		
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
		$query = sprintf("SELECT id, descricao, status FROM eventos");
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro no carregamento do cliente";	
			return false;	
		}
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$row['status'] = $this->formatStatus($row['status']);
			$this->array[] = $row;
		}
	}

	private function checkEvento()
	{
		$conn = $this->getDB->mysqli_connection;		
		$query = sprintf("SELECT descricao FROM eventos WHERE descricao = '%s' AND id <> %d", $this->descricao, $this->id);	
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro durante a verificação do evento.";
			return false;	
		}
		
		if (!empty($result->fetch_array(MYSQLI_ASSOC))) {
			$this->msg = "Evento já existente.";
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
		$query = sprintf("DELETE FROM eventos WHERE id = %d ", $id);
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Não foi possível excluir o evento.";	
			return false;	
		}
		$this->msg = 'Registro excluido com sucesso';
		return true;	
	}

	private function checkVinculo()
	{
		$conn = $this->getDB->mysqli_connection;		
		$query = sprintf("SELECT * FROM eventosempresa WHERE evento_id = %d", $this->id);	
		$sql = sprintf("SELECT * FROM solicitacao WHERE evento_id = %d", $this->id);	
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro durante a verificação do vinculo do evento.";
			return false;	
		}
		
		if (!empty($result->fetch_array(MYSQLI_ASSOC))) {
			$this->msg = "O evento tem um vinculo externo com a tabela eventos empresas, exclusão não permitida.";
			return false;			
		}

		if (!$result_sql = $conn->query($sql)) {
			$this->msg = "Ocorreu um erro durante a verificação do vinculo do evento.";
			return false;	
		}
		
		if (!empty($result_sql->fetch_array(MYSQLI_ASSOC))) {
			$this->msg = "O evento tem um vinculo externo com a tabela solicitação, exclusão não permitida.";
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
		$query = "SELECT id, descricao, status FROM eventos WHERE id =". $id;
		
		if (!$result = $conn->query($query)) {
			$this->msg = "Ocorreu um erro no carregamento do evento";	
			return false;	
		}
		$this->array = $result->fetch_array(MYSQLI_ASSOC);
		$this->array['descricao'] = utf8_decode($this->array['descricao']);
		$this->msg = 'Registro carregado com sucesso';
		return true;
	}
}

?>