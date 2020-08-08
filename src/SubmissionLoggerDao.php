<?php

namespace SubmissionLogger;

class SubmissionLoggerDao {
	private $database;

	public function __construct()
	{
		$database = new Database;

		$this->database = $database->getInstance();
	}

	public function index()
	{
		$logs = [];

		$query = 'SELECT data, date FROM sl_logs ORDER BY datetime(date) DESC';		
		$stmt = $this->database->prepare($query);		
		$result = $stmt->execute();

		while($log = $result->fetchArray(SQLITE3_ASSOC)) {
			array_push($logs, [
				'data' => unserialize($log['data']),
				'date' => $log['date']
			]);
		}

		return $logs;
	}

	private function totalOfPages($perPage)
	{
		$query = 'SELECT count(*) FROM sl_logs';
		$stmt = $this->database->prepare($query);
		$result = $stmt->execute();

		$total = $result->fetchArray(SQLITE3_NUM)[0];

		return ceil($total / $perPage);
	}

	public function paginate($perPage = 10)
	{
		$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$offset = ($currentPage - 1) * $perPage;
		$totalOfPages = $this->totalOfPages($perPage);

		$logs = [];
		
		$query = 'SELECT data, date FROM sl_logs ORDER BY datetime(date) DESC LIMIT :offset, :perPage';
		$stmt = $this->database->prepare($query);
		$stmt->bindValue(':offset', $offset);
		$stmt->bindValue(':perPage', $perPage);
		$result = $stmt->execute();

		while($log = $result->fetchArray(SQLITE3_ASSOC)) {
			array_push($logs, [
				'data' => unserialize($log['data']),
				'date' => $log['date']
			]);
		}

		return [
			'logs' => $logs,
			'pagination' => View::pagination($currentPage, $totalOfPages)
		];
	}

	public function store($data)
	{
		$date = date('Y-m-d H:i:s');

		$query = 'INSERT INTO sl_logs (data, date) VALUES (:data, :date)';

		$stmt = $this->database->prepare($query);
		$stmt->bindValue(':data', $data, SQLITE3_TEXT);
		$stmt->bindValue(':date', $date, SQLITE3_TEXT);
		
		$result = $stmt->execute();

		return $result;
	}
}