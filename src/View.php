<?php
/**
 * SubmissionLogger View handler class.
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
 * SubmissionLogger View handler class.
 * @package SubmissionLogger
 * @author Luan Novais <oi@luandev.ml>
 */
class View {
	const VIEWS_DIR = __DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
	const VIEWS_SUFFIX = '.view.php';

	public $version = '1.0.0';

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