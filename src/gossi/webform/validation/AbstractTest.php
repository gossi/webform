<?php
namespace gossi\webform\validation;

use gossi\webform\Control;

abstract class Test {

	protected $message;
	protected $controls = array();
	
	public function __construct() {
	}
	
	public function addControl(Control $control) {
		if (!in_array($control, $this->controls)) {
			$this->controls[] = $control;
		}
		return $this;
	}
	
	protected function addErrorToControls($message) {
		foreach ($this->controls as $control) {
			$control->addError($message);
		}
	}

	public function getMessage() {
		return $this->message;
	}

	public function getStatement() {
		return $this->statement;
	}
	
	public function getControls() {
		return $this->controls;
	}
	
	public function removeControl(Control $control) {
		if ($offset = array_search($control, $this->controls)) {
			unset($this->controls[$offset]);
		}
		return $this;
	}
	
	public function setControls($controls) {
		$this->controls = $controls;
		return $this;
	}

	public function setMessage($message) {
		$this->message = $message;
		return $this;
	}
	
	abstract public function validate();
}