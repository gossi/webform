<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class WebformErrors extends \Exception implements \Iterator {

	private $position = 0;
	private $errors = array();

	public function __construct($message = null, $code = 0) {
		parent::__construct($message, $code);
	}

	public function __toString() {
		$e = '';
		foreach ($this->errors as $error) {
			$e .= sprintf('<li>%s</li>', $error);
		}
		return __CLASS__ . ': <ul>'.$e.'</ul>';
	}

	public function addError($message) {
		$this->errors[] = $message;
		return $this;
	}

	public function addErrors($errors) {
		foreach ($errors as $error) {
			$this->errors[] = $error;
		}
		return $this;
	}

	public function current() {
		return $this->errors[$this->position];
	}

	public function getErrors() {
		return $this->errors;
	}

	public function key() {
		return $this->position;
	}

	public function next() {
		++$this->position;
	}

	public function rewind() {
		$this->position = 0;
	}

	public function size() {
		return count($this->errors);
	}

	public function toXML() {
		$xml = new \DOMDocument();
		$root = $xml->createElement('errors');

		foreach ($this->errors as $error) {
			$e = $xml->createElement('error', $error);
			$root->appendChild($e);
		}

		$xml->appendChild($root);

		return $xml;
	}

	public function valid() {
		return isset($this->errors[$this->position]);
	}
}
?>