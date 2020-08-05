<?php
use SubmissionLogger\Database;
use SubmissionLogger\SubmissionLogger;

include '../core/SubmissionLogger.php';

$sl = new SubmissionLogger;

$sl->prepare('password', __DIR__ . '/logs/db.sqlite', __DIR__ . '/views/');

var_dump($sl);