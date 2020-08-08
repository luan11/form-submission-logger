<?php

use SubmissionLogger\SubmissionLogger;

date_default_timezone_set('America/Sao_Paulo');

include '../src/SubmissionLoggerAutoload.php';

$store = SubmissionLogger::store([
	'foo' => 'bar'
]);

var_dump($store);
