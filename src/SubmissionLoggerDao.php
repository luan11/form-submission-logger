<?php
/**
 * SubmissionLogger data access object class.
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
 * SubmissionLogger data access object class.
 * @package SubmissionLogger
 * @author Luan Novais <oi@luandev.ml>
 */
class SubmissionLoggerDao {
	public $version = '1.1.0';
	private $database, $databaseType;

	public function __construct()
	{
		$database = new Database;

		$this->database = $database->getInstance();
		$this->databaseType = $database->type;
	}

	public function index()
	{
		$logs = [];

		if($this->databaseType !== 'json') {
			$query = $this->databaseType === 'sqlite' ? 'SELECT data, date FROM sl_logs ORDER BY datetime(date) DESC' : 'SELECT data, date FROM sl_logs ORDER BY date DESC';
			$stmt = $this->database->prepare($query);

			$result = $stmt->execute();

			if($this->databaseType === 'mysql') {
				$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			}

			if($this->databaseType === 'sqlite') {
				while($log = $result->fetchArray(SQLITE3_ASSOC)) {
					array_push($logs, [
						'data' => unserialize($log['data']),
						'date' => $log['date']
					]);
				}
			} else {
				for($i = 0; $i < count($result); $i++) {
					array_push($logs, [
						'data' => unserialize($result[$i]['data']),
						'date' => $result[$i]['date']
					]);
				}
			}
		} else {
			$result = $this->database->select('sl_logs', 'DESC');

			if($result) {
				for($i = 0; $i < count($result); $i++) {
					array_push($logs, [
						'data' => unserialize($result[$i]['data']),
						'date' => $result[$i]['date']
					]);
				}
			} else {
				$result = [];
			}
		}

		return $logs;
	}

	private function totalOfPages($perPage)
	{
		if($this->databaseType !== 'json') {
			$query = 'SELECT count(*) FROM sl_logs';
			$stmt = $this->database->prepare($query);
			$result = $stmt->execute();

			$total = $this->databaseType === 'sqlite' ? $result->fetchArray(SQLITE3_NUM)[0] : $stmt->fetch(\PDO::FETCH_NUM)[0];
		} else {
			$allLogs = $this->database->select('sl_logs');

			if($allLogs) {
				$total = count($allLogs);
			} else {
				$total = 0;
			}
		}

		return ceil($total / $perPage);
	}

	public function paginate($perPage = 10)
	{
		$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$offset = ($currentPage - 1) * $perPage;
		$totalOfPages = $this->totalOfPages($perPage);

		$logs = [];
		
		if($this->databaseType !== 'json') {
			$query = $this->databaseType === 'sqlite' ? 'SELECT data, date FROM sl_logs ORDER BY datetime(date) DESC LIMIT :offset, :perPage' : 'SELECT data, date FROM sl_logs ORDER BY date DESC LIMIT :offset, :perPage';
			$stmt = $this->database->prepare($query);
			
			if($this->databaseType === 'sqlite') {
				$stmt->bindParam(':offset', $offset, SQLITE3_NUM);
				$stmt->bindParam(':perPage', $perPage, SQLITE3_NUM);
			} else {
				$stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
				$stmt->bindParam(':perPage', $perPage, \PDO::PARAM_INT);
			}

			$result = $stmt->execute();

			if($this->databaseType === 'mysql') {
				$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			}

			if($this->databaseType === 'sqlite') {
				while($log = $result->fetchArray(SQLITE3_ASSOC)) {
					array_push($logs, [
						'data' => unserialize($log['data']),
						'date' => $log['date']
					]);
				}
			} else {
				for($i = 0; $i < count($result); $i++) {
					array_push($logs, [
						'data' => unserialize($result[$i]['data']),
						'date' => $result[$i]['date']
					]);
				}
			}
		} else {
			$result = $this->database->select('sl_logs', 'DESC', $offset, $perPage);

			if($result) {
				for($i = 0; $i < count($result); $i++) {
					array_push($logs, [
						'data' => unserialize($result[$i]['data']),
						'date' => $result[$i]['date']
					]);
				}
			} else {
				$result = [];
			}
		}

		return [
			'logs' => $logs,
			'pagination' => View::pagination($currentPage, $totalOfPages)
		];
	}

	public function store($data)
	{
		$date = date('Y-m-d H:i:s');

		if($this->databaseType !== 'json') {
			$query = 'INSERT INTO sl_logs (data, date) VALUES (:data, :date)';

			$stmt = $this->database->prepare($query);

			if($this->databaseType === 'sqlite') {
				$stmt->bindParam(':data', $data, SQLITE3_TEXT);
				$stmt->bindParam(':date', $date, SQLITE3_TEXT);
			} else {
				$stmt->bindParam(':data', $data, \PDO::PARAM_STR);
				$stmt->bindParam(':date', $date, \PDO::PARAM_STR);
			}
			
			$result = $stmt->execute();
		} else {
			$result = $this->database->insert('sl_logs', [
				'data' => $data,
				'date' => $date
			]);
		}

		return $result;
	}

	public function getAuth()
	{
		if($this->databaseType !== 'json') {
			$query = 'SELECT `key` FROM sl_auth';

			$stmt = $this->database->prepare($query);

			$result = $stmt->execute();

			if($this->databaseType === 'sqlite') {
				$result = $result->fetchArray(SQLITE3_ASSOC);
			} else {
				$result = $stmt->fetch(\PDO::FETCH_ASSOC);
			}
		} else {
			$result = $this->database->select('sl_auth');

			if($result) {
				$result = $result[0];
			}
		}
		
		return $result;
	}

	public function setAuth($hashedPassword)
	{
		if($this->databaseType !== 'json') {
			$query = 'INSERT INTO sl_auth (`key`) VALUES (:password)';

			$stmt = $this->database->prepare($query);

			if($this->databaseType === 'sqlite') {
				$stmt->bindParam(':password', $hashedPassword, SQLITE3_TEXT);
			} else {
				$stmt->bindParam(':password', $hashedPassword, \PDO::PARAM_STR, 60);
			}

			$result = $stmt->execute();
		} else {
			$result = $this->database->insert('sl_auth', [
				'key' => $hashedPassword
			]);
		}

		return $result;
	}
}