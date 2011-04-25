<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class WebformException extends Exception {

	private $errors = array();

	public function __construct($message = '', $code = 0) {
		parent::__construct($message, $code);
		if ($message != '') {
			$this->addError($message);
		}
	}

	public function addError($message) {
		$this->errors[] = $message;
	}

	public function addErrors($messages) {
		foreach ($messages as $message) {
			$this->addError($message);
		}
	}

	public function getErrors() {
		return $this->errors;
	}

	public function size() {
		return count($this->errors);
	}

	public function __toString() {
		$e = '';
		foreach ($this->errors as $error) {
			$e .= sprintf('<li>%s</li>', $error);
		}
		return __CLASS__ . ': <ul>'.$e.'</ul>';
	}

	public function toXml() {
		$xml = new \DOMDocument();
		$root = $xml->createElement('errors');

		foreach ($this->errors as $error) {
			$e = $xml->createElement('error', $error);
			$root->appendChild($e);
		}

		$xml->appendChild($root);

		return $xml;
	}
}
?>