<?php
namespace gossi\webform;

class Validation {

	private $statement;
	private $message;

	public function __construct($statement = true, $message = '') {
		$this->statement = $statement;
		$this->message = $message;
	}

	public function getMessage() {
		return $this->message;
	}

	public function getStatement() {
		return $this->statement;
	}
}