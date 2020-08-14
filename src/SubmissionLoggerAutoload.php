<?php
/**
 * SubmissionLogger SPL autoloader.
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

/**
 * SubmissionLogger SPL autoloader.
 *
 * @param string $classname The name of the class to load
 * @return void
 */
function SubmissionLoggerAutoload($classname) {
	$realClassname = explode('\\', $classname)[1];
	$filename = __DIR__ . DIRECTORY_SEPARATOR . $realClassname . '.php';

	if(is_readable($filename)) {
		require $filename;
	}
}

require 'config.php';

spl_autoload_register('SubmissionLoggerAutoload', true, true);