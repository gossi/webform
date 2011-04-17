<?php
namespace gossi\webform;

class FloatValidator extends Validator {

	public function validate($string) {
		if (!is_numeric($string)) {
			throw new WebformException(sprintf($this->webform->getI18n('error/invalid'), $this->control->getLabel()));
		}
	}

	public function parse(DOMNode $node) {

	}

	public function toXml() {
		$xml = new DOMDocument('1.0');
		$root = $xml->createElement('validator');
		$root->setAttribute('type', 'Float');
		$xml->appendChild($root);

		return $xml;
	}
}
?>