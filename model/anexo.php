<?php
require_once('app.php');

class anexo extends app
{
	public $file;
	public $path;
	public $name;
	public $nameDir;
	public $msg;

	private $dir = 'files/';
	private $typesSolic = array('application/pdf','text/pdf', 'image/jpeg', 'image/png', 'image/pjpeg', 'application/word', 'application/msword', 'plain/text');
	private $permissionSize = 1024 * 1000;

	const FILE_SOLICITACAO = 'solic';
	const NAME_ARQ = 'arquivo_';

	public function insert()
	{
		if (!$this->file) {
			$this->msg = 'Arquivo não enviado';
			return false;
		}

		// O nome original do arquivo no computador do usuário
		foreach ($this->file['name'] as $key => $nome) {
			$arq[$key]['name'] = $nome;
		}	

		// O tipo mime do arquivo. Um exemplo pode ser "image/gif"
		foreach ($this->file['type'] as $key => $type) {
			$arq[$key]['type'] = $type;
		}
		// O tamanho, em bytes, do arquivo
		foreach ($this->file['size'] as $key => $size) {
			$arq[$key]['size'] = $size;
		}
		// O nome temporário do arquivo, como foi guardado no servidor
		foreach ($this->file['tmp_name'] as $key => $tmp_name) {
			$arq[$key]['tmp_name'] = $tmp_name;
		}
		// O código de erro associado a este upload de arquivo
		foreach ($this->file['error'] as $key => $error) {
			$arq[$key]['error'] = $error;
		}

		foreach ($arq as $indexing => $file) {
			
			if ($file['error'] > 0) {	
				$this->msg = 'Arquivo inválido';
				return false;   
			}
			if ($this->typeFile == $this::FILE_SOLICITACAO && !in_array($file['type'], $this->typesSolic)) {
	      		$this->msg = 'Tipo de arquivo inválido';
	      		return false;
		    }
		    if (!$file['size'] > $this->permissionSize) {
		      	$this->msg = 'O tamanho do arquivo enviado é maior que o limite!';
		    	return false;
		    }
		
	    	$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

		    if ($this->typeFile == $this::FILE_SOLICITACAO && ($ext == 'bat' || $ext == 'exe') ) {
	      		$this->msg = 'Tipo de arquivo inválido';
	      		return false;
		    }

		    if (empty($this->name)) {
		    	$this->name = $this->renameFile(str_replace('.'.$ext, '', $file['name']));
		    }

		    $name = $this::NAME_ARQ.$indexing.'.'.$ext;

		    if (!empty($this->dir)) {
				$dir = app::path.$this->dir.$this->path.'/'.$this->name;

				if (!file_exists($dir)) {
					mkdir($dir, 0777, true);	
				}

				$upload = move_uploaded_file($file['tmp_name'], $dir .'/'. $name);
		    	
		    	if (!$upload) {
					$this->msg = 'ocorreu um erro ao inserir arquivo'; 
					return false;
				}
			}
		}

		return true;
	}

		private function renameFile($str) {
		    $str = preg_replace('/[áàãâä]/ui', 'a', $str);
		    $str = preg_replace('/[éèêë]/ui', 'e', $str);
		    $str = preg_replace('/[íìîï]/ui', 'i', $str);
		    $str = preg_replace('/[óòõôö]/ui', 'o', $str);
		    $str = preg_replace('/[úùûü]/ui', 'u', $str);
		    $str = preg_replace('/[ç]/ui', 'c', $str);
		    //$str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
		    $str = preg_replace('/[^a-z0-9]/i', '_', $str);
		    $str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
	    	return $str;
		}
	}
