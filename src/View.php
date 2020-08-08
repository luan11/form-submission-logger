<?php

namespace SubmissionLogger;

class View {
	const VIEWS_DIR = __DIR__ . '/views/';
	const VIEWS_SUFFIX = '.view.php';

	public static function show($name, $data = [])
	{
		extract($data);

		include self::VIEWS_DIR . $name . self::VIEWS_SUFFIX;
	}

	public static function pagination($currentPage, $total)
	{
		$list = '<ul class="pagination">%s</ul>';
		$lisItems = '';

		if($currentPage > 1) {
			$lisItems .= '<li class="page-item"> <a class="page-link" href="?page='. ($currentPage - 1) .'" aria-label="Previous"> <span aria-hidden="true">&laquo;</span> </a> </li>';
		}

		for($i = 1; $i <= $total; $i++) {
			if($i === $currentPage) {
				$lisItems .= '<li class="page-item active"> <span class="page-link"> '. $i .' </span> </li>';
			} else {
				$lisItems .= '<li class="page-item"><a class="page-link" href="?page='. $i .'">'. $i .'</a></li>';
			}
		}

		if($currentPage < $total) {
			$lisItems .= '<li class="page-item"> <a class="page-link" href="?page='. ($currentPage + 1) .'" aria-label="Next"> <span aria-hidden="true">&raquo;</span> </a> </li>';
		}

		return sprintf($list, $lisItems);
	}
}