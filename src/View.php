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
}