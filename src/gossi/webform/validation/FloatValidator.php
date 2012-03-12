<?php
namespace gossi\webform\validation;

class FloatValidator extends Validator {

	public function validate($string) {
		if (!is_numeric($string)) {
			throw new \Exception(sprintf($this->webform->getI18n('error/invalid'), $this->control->getLabel()));
		}
	}

	public function toXML() {
		$xml = new \DOMDocument('1.0');
		$root = $xml->createElement('validator');
		$root->setAttribute('type', 'Float');
		$xml->appendChild($root);

		return $xml;
	}
}
?>