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
	public $version = '1.1.1';
	private $dao, $authenticated, $unregistered, $passwordRegister, $passwordCheck, $logout, $messages = [];

	public function __construct()
	{
		$this->passwordRegister = isset($_POST['password_register']) ? filter_var($_POST['password_register']) : false;
		$this->passwordCheck = isset($_POST['password_check']) ? filter_var($_POST['password_check']) : false;
		$this->logout = isset($_POST['_logout']) ? filter_var($_POST['_logout']) : false;
	}

	private function setMessage($content, $type = 'info')
	{
		array_push($this->messages, [$content, $type]);
	}

	private function setAuthPassword($password)
	{
		if(strlen($password) < 12) {		
			return;
		}

		$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

		if(!isset($this->dao)) {
			$this->dao = new SubmissionLoggerDao;
		}

		$this->dao->setAuth($hashedPassword);

		header('Refresh: 0');
		exit;
	}

	private function authenticate()
	{
		if(!isset($this->dao)) {
			$this->dao = new SubmissionLoggerDao;
		}

		$auth = $this->dao->getAuth();

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
					} else {
						$this->setMessage('Wrong password, try again.', 'danger');

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
		exit;
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
			if(!isset($this->dao)) {
				$this->dao = new SubmissionLoggerDao;
			}
			
			$logs = $this->dao->paginate();

			View::show('index', $logs);
		}

		if(!$this->authenticated && !$this->unregistered) {
			View::show('auth', ['messages' => $this->messages]);
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