<?php
/**
 * Basic configurations for SubmissionLogger Database
 * 
 * This file have following configurations:
 * * Database type
 * * MySQL configurations
 * 
 * @package SubmissionLogger
 */

/**
 * This option accepts these values: sqlite, mysql, json
 */
define('DATABASE_TYPE', 'mysql');

/**
 * MySQL Database
 */
define('MYSQL_DATABASE_HOST', 'localhost');
define('MYSQL_DATABASE_NAME', 'db_sl');
define('MYSQL_DATABASE_USER', 'root');
define('MYSQL_DATABASE_PASSWORD', '');