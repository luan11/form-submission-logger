<?php
/**
 * SubmissionLogger - Logs insertion and preview class.
 * PHP Version >=5.3.0
 * @package SubmissionLogger
 * @link https://github.com/luan11/form-submission-logger The SubmissionLogger GitHub project
 * @author Luan Novais <oi@luandev.ml>
 * @copyright 2020 Luan Novais
 * @license MIT
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace SubmissionLogger;

/**
 * SubmissionLogger - Logs insertion and preview class.
 * @package SubmissionLogger
 * @author Luan Novais <oi@luandev.ml>
 */
class SubmissionLogger {
	public $version = '1.1.0';
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

		$dao = new SubmissionLoggerDao;

		$dao->setAuth($hashedPassword);

		header('Refresh: 0');
	}

	private function authenticate()
	{
		$dao = new SubmissionLoggerDao;

		$auth = $dao->getAuth();

		if(session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if(isset($_SESSION['auth']) && $_SESSION['auth'] === true && $auth !== false) {
			$this->authenticated = true;
		} else {
			if($auth === false) {
				$this->authenticated = false;
				$this->unregistered = true;
			} else {
				if($this->passwordCheck) {
					$registeredPassword = $auth['key'];

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

		$dao = new SubmissionLoggerDao();
		
		return $dao->store($serializedData);
	}

	public function start()
	{
		$this->authenticate();

		if($this->authenticated) {
			$dao = new SubmissionLoggerDao;
			
			$logs = $dao->paginate();

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