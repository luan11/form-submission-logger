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

use SQLite3;

/**
 * SubmissionLogger Database handler class.
 * @package SubmissionLogger
 * @author Luan Novais <oi@luandev.ml>
 */
class Database {
	const DATABASE_DIR = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR;
	const DATABASE_FILENAME = 'db_sl.sqlite';
	const QUERY_CREATE_AUTH_TABLE = 'CREATE TABLE IF NOT EXISTS sl_auth (key TEXT)';
	const QUERY_CREATE_LOGS_TABLE = 'CREATE TABLE IF NOT EXISTS sl_logs (data TEXT, date TEXT)';

	public $version = '1.0.0';
	private $instance;

	public function __construct()
	{
		$this->instance = new SQLite3(self::DATABASE_DIR . self::DATABASE_FILENAME);

		$instance = $this->instance;

		$instance->exec(self::QUERY_CREATE_AUTH_TABLE);
		$instance->exec(self::QUERY_CREATE_LOGS_TABLE);
	}

	public function getInstance()
	{
		return $this->instance;
	}
}