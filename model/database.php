<?php

class config extends dba
{
const  dominio = 'http://dev.platform/folha-pagamento/';
const  path    = '/var/www/html/plataforma/folha-pagamento/';
}

class dba 
{
	public $mysqli_connection;

	public function __construct() {
		$mysqli_connection = new MySQLi('127.0.0.1', 'root', 'Everty_14', 'folhapagto');
		if($mysqli_connection->connect_error){
		  echo "Desconectado! Erro: " . $mysqli_connection->connect_error;
		}
		$this->mysqli_connection = $mysqli_connection;
	}

	public function quote($data, $m_bIsText = true, $m_bAllowNull = false)
	{
		//die("({$data}) ({$m_bIsText}) ({$m_bAllowNull})");

		if(is_numeric($data) && $m_bAllowNull && $data == 0)
			return "NULL";

		// Empty data and NULL is allowed
		if($m_bAllowNull && $data == '')
			return "NULL";

		// String: Add quotation marks and escape it
		if($m_bIsText)
			return sprintf("'%s'", $this->escape($data));

		// Probably number, only escapes is required (avoid SQL injection)
		return $this->escape($data);
	}
	public function escape($str, $html=true)
	{
		$str = @trim($str);

		if($html)
			return mysqli_real_escape_string($this->getDB->mysqli_connection, strip_tags($str));
		else
			return mysqli_real_escape_string($this->mysqli_connection, $str);
	}
}

?>