<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class IntValidator extends Validator {

	public function validate($string) {
		if (preg_match('/\D/', $string)) {
			throw new WebformException(sprintf($this->webform->getI18n('error/invalid'), $this->control->getLabel()));
		}
	}

	public function parse(\DOMNode $node) {

	}

	public function toXml() {
		$xml = new \DOMDocument('1.0');
		$root = $xml->createElement('validator');
		$root->setAttribute('type', 'Int');
		$xml->appendChild($root);

		return $xml;
	}
}
?>