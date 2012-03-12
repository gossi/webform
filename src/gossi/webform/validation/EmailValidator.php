<?php
namespace gossi\webform\validation;

/**
 * A validator to parse email.
 * 
 * @see http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#valid-e-mail-address
 */
class EmailValidator extends PatternValidator {

	public function __construct() {
		$this->setPattern("^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$");
	}

	public function toXML() {
		$xml = new \DOMDocument('1.0');
		$root = $xml->createElement('validator');
		$root->setAttribute('type', 'Email');
		$xml->appendChild($root);

		return $xml;
	}
}
?>