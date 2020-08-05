<?php
namespace SubmissionLogger;

class Database {
	private static $instance;

	public static function get($path)
	{
		if(!isset(self::$instance)) {
			self::$instance = new \SQLite3($path);
		}

		return self::$instance;
	}

	public static function start($path, $password)
	{
		self::$instance = new \SQLite3($path);

		$tableAuthQuery = 'CREATE TABLE IF NOT EXISTS sl_auth (key TEXT)';

		self::$instance->exec($tableAuthQuery);

		$tableLogsQuery = 'CREATE TABLE IF NOT EXISTS sl_logs (data TEXT, date TEXT)';

		self::$instance->exec($tableLogsQuery);

		$insertPasswordInAuthTable = self::$instance->prepare('INSERT INTO sl_auth (key) VALUES (?)');
		$insertPasswordInAuthTable->bindValue(1, $password, SQLITE3_TEXT);

		$insertPasswordInAuthTable->execute();

		return self::$instance;	
	}
}