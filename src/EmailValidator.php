<?php
namespace gossi\webform;

class EmailValidator extends Validator {

	public function validate($string) {
		if (!preg_match('/(([a-z0-9_\-\.])+@([a-z0-9\-àáâãäåāăąæçćĉċčďđèéêëēĕėęěŋðĝğġģĥħìíîïĩīĭįıĵķĸĺļľłñńņňòóôõöøōŏőœŕŗřśŝşšţťŧþùúûüũūŭůűųŵýÿŷźżž]\.?)+(\.[a-z]{2,4})+)/i', $string)) {
			throw new WebformException(sprintf($this->webform->getI18n('error/invalid'), $this->control->getLabel()));
		}
	}

	public function parse(DOMNode $node) {

	}

	public function toXml() {
		$xml = new DOMDocument('1.0');
		$root = $xml->createElement('validator');
		$root->setAttribute('type', 'Email');
		$xml->appendChild($root);

		return $xml;
	}
}
?>