<?php
/**
 * SubmissionLogger Database handler class.
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
 * SubmissionLogger Database handler class.
 * @package SubmissionLogger
 * @author Luan Novais <oi@luandev.ml>
 */
class Database {
	const DATABASE_TYPES = ['sqlite', 'mysql', 'json'];
	const DATABASE_DIR = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR;

	public $version = '2.0.0', $type;
	private $instance, $filename, $build;

	public function __construct()
	{
		if(in_array(DATABASE_TYPE, self::DATABASE_TYPES)) {
			$this->type = DATABASE_TYPE;

			if($this->type !== 'mysql') {
				$this->filename = 'db_sl.' . $this->type;
			}

			if($this->type !== 'json') {
				if($this->type === 'sqlite') {
					$this->build = [
						'create_auth_table' => 'CREATE TABLE IF NOT EXISTS `sl_auth` (`key` TEXT)',
						'create_logs_table' => 'CREATE TABLE IF NOT EXISTS `sl_logs` (`data` TEXT, date TEXT)'
					];
				}

				if($this->type === 'mysql') {
					$this->build = [
						'create_auth_table' => 'CREATE TABLE IF NOT EXISTS `sl_auth` (`key` VARCHAR(60) NOT NULL)',
						'create_logs_table' => 'CREATE TABLE IF NOT EXISTS `sl_logs` (`data` LONGTEXT NOT NULL, `date` DATETIME)'
					];
				}
			} else {
				$this->build = [
					'sl_auth' => [],
					'sl_logs' => []
				];
			}

			switch ($this->type) {
				case 'sqlite':
					$this->makeSqliteInstance();
				break;

				case 'mysql':
					$this->makeMysqlInstance();
				break;

				case 'json':

				break;
			}
		} else {
			exit('It\'s necessary to define a valid database type in file config.php');
		}
	}

	private function makeSqliteInstance()
	{
		$this->instance = new \SQLite3(self::DATABASE_DIR . $this->filename);

		$instance = $this->instance;

		$instance->exec($this->build['create_auth_table']);
		$instance->exec($this->build['create_logs_table']);
	}

	private function makeMysqlInstance()
	{
		$this->instance = new \PDO('mysql:host='. MYSQL_DATABASE_HOST .';dbname='. MYSQL_DATABASE_NAME .';charset=utf8', MYSQL_DATABASE_USER, MYSQL_DATABASE_PASSWORD);

		$instance = $this->instance;

		$instance->exec($this->build['create_auth_table']);
		$instance->exec($this->build['create_logs_table']);
	}

	private function makeJsonInstance()
	{
		
	}

	public function getInstance()
	{
		return $this->instance;
	}
}