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

	public function paginate($perPage = 10)
	{
		$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

		$offset = ($currentPage - 1) * $perPage;

		$queryTotal = 'SELECT count(*) FROM sl_logs';
		
		$stmtTotal = $this->database->prepare($queryTotal);		
		$resultTotal = $stmtTotal->execute();
		$total = $resultTotal->fetchArray(SQLITE3_NUM)[0];

		$totalOfPages = ceil($total / $perPage);

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

		$paginationOpen = '<ul class="pagination">';
		$paginationClose = '</ul>';
		$paginationItems = '';

		if($currentPage > 1) {
			$paginationItems .= '<li class="page-item"> <a class="page-link" href="?page='. ($currentPage - 1) .'" aria-label="Previous"> <span aria-hidden="true">&laquo;</span> </a> </li>';
		}

		for($i = 1; $i <= $totalOfPages; $i++) {
			if($i === $currentPage) {
				$paginationItems .= '<li class="page-item active"> <span class="page-link"> '. $i .' </span> </li>';
			} else {
				$paginationItems .= '<li class="page-item"><a class="page-link" href="?page='. $i .'">'. $i .'</a></li>';
			}
		}

		if($currentPage < $totalOfPages) {
			$paginationItems .= '<li class="page-item"> <a class="page-link" href="?page='. ($currentPage + 1) .'" aria-label="Next"> <span aria-hidden="true">&raquo;</span> </a> </li>';
		}

		return [
			'logs' => $logs,
			'pagination' => $paginationOpen . $paginationItems . $paginationClose
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