<?php
namespace SubmissionLogger;

include 'Database.php';

class SubmissionLogger {
	const VIEW_AUTH_DEFAULT_NAME = 'auth';
	const VIEW_INDEX_DEFAULT_NAME = 'index';

	private $logsPath, $viewsPath, $viewAuth_name, $viewIndex_name, $password, $database, $authenticated;

	public function prepare($password, $logsPath, $viewsPath, $viewAuth_name = self::VIEW_AUTH_DEFAULT_NAME, $viewIndex_name = self::VIEW_INDEX_DEFAULT_NAME)
	{
		$this->logsPath = $logsPath;
		$this->viewsPath = $viewsPath;
		$this->viewAuth_name = $viewAuth_name . '.view.php';
		$this->viewIndex_name = $viewIndex_name . '.view.php';
		$this->password = password_hash($password, PASSWORD_BCRYPT);

		if(file_exists($this->logsPath)) {
			$this->database = Database::get($this->logsPath);
		} else {
			$this->database = Database::start($this->logsPath, $this->password);
		}
	}

	public function authenticate($authKey)
	{
		
	}

	public static function store($data)
	{
		
	}
}