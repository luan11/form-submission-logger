<?php

namespace SubmissionLogger;

class SubmissionLogger {
	private $database, $authenticated, $unregistered, $passwordRegister, $passwordCheck, $logout;

	public function __construct()
	{
		$this->passwordRegister = isset($_POST['password_register']) ? filter_var($_POST['password_register']) : false;
		$this->passwordCheck = isset($_POST['password_check']) ? filter_var($_POST['password_check']) : false;
		$this->logout = isset($_POST['_logout']) ? filter_var($_POST['_logout']) : false;
	}

	private function setAuthPassword($password)
	{
		if(strlen($password) < 12) {		
			return;
		}

		$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

		$query = 'INSERT INTO sl_auth (key) VALUES (:password)';

		if(!isset($this->database)) {
			$database = new Database();
			$this->database = $database->getInstance();
		}

		$stmt = $this->database->prepare($query);
		$stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
		$stmt->execute();

		header('Refresh: 0');
	}

	private function authenticate()
	{
		$query = 'SELECT key FROM sl_auth';

		if(!isset($this->database)) {
			$database = new Database();
			$this->database = $database->getInstance();
		}

		$stmt = $this->database->prepare($query);

		$result = $stmt->execute();

		$resultFetched = $result->fetchArray(SQLITE3_ASSOC);

		if(session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if(isset($_SESSION['auth']) && $_SESSION['auth'] === true && $resultFetched !== false) {
			$this->authenticated = true;
		} else {
			if($resultFetched === false) {
				$this->authenticated = false;
				$this->unregistered = true;
			} else {
				if($this->passwordCheck) {
					$registeredPassword = $resultFetched['key'];

					if(password_verify($this->passwordCheck, $registeredPassword)) {
						$this->authenticated = true;
						
						$_SESSION['auth'] = $this->authenticated;

						header('Refresh: 0');
					} else {
						$this->authenticated = false;
					}					
				} else {
					$this->authenticated = false;
				}
			}
		}
	}

	private function deauthenticate()
	{
		if(session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		session_unset();
		session_destroy();

		$this->authenticated = false;

		header('Refresh: 0');
	}

	public static function store($data)
	{
		$serializedData = serialize($data);

		$submissionLoggerDao = new SubmissionLoggerDao();
		
		return $submissionLoggerDao->store($serializedData);
	}

	public function start()
	{
		$this->authenticate();

		if($this->authenticated) {
			$submissionLoggerDao = new SubmissionLoggerDao;
			
			$logs = $submissionLoggerDao->paginate();

			View::show('index', $logs);
		}

		if(!$this->authenticated && !$this->unregistered) {
			View::show('auth');
		}

		if(!$this->authenticated && $this->unregistered) {
			View::show('register');
		}

		if($this->passwordRegister) {
			$this->setAuthPassword($this->passwordRegister);
		}

		if($this->logout) {
			$this->deauthenticate();
		}
	}
}