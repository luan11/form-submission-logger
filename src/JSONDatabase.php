<?php
/**
 * SubmissionLogger use JSON as database class.
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
 * SubmissionLogger use JSON as database class.
 * @package SubmissionLogger
 * @author Luan Novais <oi@luandev.ml>
 */
class JSONDatabase {
	public $version = '1.0.0', $size;
	private $src, $order, $data;

	public function __construct($src)
	{
		$this->src = $src;

		if(!file_exists($this->src)) {
			file_put_contents($this->src, '[]');

			$this->size = filesize($src);

			$this->data = [];
		} else {
			$file = file_get_contents($this->src);

			$this->size = filesize($src);

			if($file !== false) {
				$this->data = json_decode($file, true);

				if(!$this->data) {
					$this->data = [];
				}
			} else {
				die('Something is wrong, try again.');
			}
		}
	}

	private function updateFile()
	{
		return file_put_contents($this->src, json_encode($this->data));
	}

	public function createTable($name, $notExists = true)
	{
		if($notExists && array_key_exists($name, $this->data)) {
			return;
		}

		$this->data[$name] = [];

		return $this->updateFile();
	}

	public function select($table, $order = 'ASC', $min = null, $max = null)
	{
		if(!array_key_exists($table, $this->data)) {
			return false;
		}

		$this->order = $order;

		switch ($this->order) {
			case 'DESC':
				$data = $this->data;

				$data[$table] = array_reverse($data[$table]);

				$this->data = $data;
			break;
		}

		$data = [];

		if(!is_null($min) && !is_null($max)) {
			$data = array_slice($this->data[$table], $min, $max);
		} else {
			$data = $this->data[$table];
		}

		return count($data) > 0 ? $data : false;
	}

	public function insert($table, $colsAndValues)
	{
		$data = $this->data;

		array_push($data[$table], $colsAndValues);

		$this->data = $data;

		return $this->updateFile();
	}
}