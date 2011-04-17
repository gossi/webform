<?php
namespace gossi\webform;

class FloatValidator extends Validator {

	private $equal = '';

	public function __construct($message = '') {
		$this->equal = $equal;
	}

	public function validate($string) {
		if ($string != $equal) {
			throw new WebformException(sprintf($this->webform->getI18n('error/mismatch'), $this->control->getLabel()));
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