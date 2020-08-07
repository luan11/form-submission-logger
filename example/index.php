<?php

use SubmissionLogger\SubmissionLogger;

include '../vendor/autoload.php';

$store = SubmissionLogger::store([
	'foo' => 'bar'
]);

var_dump($store);
