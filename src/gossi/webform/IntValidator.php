<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class IntValidator extends PatternValidator {

	public function __construct() {
		$this->setPattern("\D");
	}

	public function parse(\DOMNode $node) {

	}

	public function toXml() {
		$xml = new \DOMDocument();
		$root = $xml->createElement('validator');
		$root->setAttribute('type', 'Int');
		$xml->appendChild($root);

		return $xml;
	}
}
?>