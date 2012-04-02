<?php
namespace gossi\webform\validation;

use gossi\webform\Control;

abstract class AbstractTest {

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
	
	/**
	 * Creates a XML Document representing the abstract control.
	 *
	 * @param String $type the type of the control
	 * @return \DOMDocument the XML Document
	 */
	protected function createXML($type) {
		$xml = new \DOMDocument('1.0', 'utf8');
		$root = $xml->createElement('test');
		$root->setAttribute('message', $this->getMessage());
		$root->setAttribute('type', $type);
	
		$xml->appendChild($root);
	
		// controls
		foreach ($this->controls as $control) {
			$c = $xml->createElement('control');
			$c->setAttribute('id', $control->getId());
			$root->appendChild($c);
		}
	
		return $xml;
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
	
	abstract public function toXML();
	
	abstract public function validate();
}