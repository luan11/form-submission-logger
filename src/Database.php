<?php
namespace SubmissionLogger;

use SQLite3;

class Database {
	const DATABASE_DIR = __DIR__ . '/database/';
	const DATABASE_FILENAME = 'db_sl.sqlite';
	const QUERY_CREATE_AUTH_TABLE = 'CREATE TABLE IF NOT EXISTS sl_auth (key TEXT)';
	const QUERY_CREATE_LOGS_TABLE = 'CREATE TABLE IF NOT EXISTS sl_logs (data TEXT, date TEXT)';

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