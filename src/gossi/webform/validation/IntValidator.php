<?php
namespace gossi\webform\validation;

class IntValidator extends PatternValidator {

	public function __construct() {
		$this->setPattern("\D");
	}

	public function toXML() {
		$xml = new \DOMDocument();
		$root = $xml->createElement('validator');
		$root->setAttribute('type', 'Int');
		$xml->appendChild($root);

		return $xml;
	}
}
?>