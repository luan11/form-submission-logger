<?php
namespace SubmissionLogger;

class SubmissionLoggerDao {
	private $database;

	public function __construct()
	{
		$database = new Database();

		$this->database = $database->getInstance();
	}

	public function index()
	{
		$query = 'SELECT data, date FROM sl_logs';
		
		$stmt = $this->database->prepare($query);
		
		$result = $stmt->execute();

		return $result->fetchArray(SQLITE3_ASSOC);
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