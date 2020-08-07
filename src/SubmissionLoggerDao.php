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

		$query = 'SELECT data, date FROM sl_logs ORDER BY date(date) ASC';
		
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

	public function paginate($perPage = 20)
	{
		
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