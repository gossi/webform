<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class Option extends BaseElement {

	private static $options = 1;

	private $value;
	private $checked;

	public function __construct() {
		$this->id = 'webform-option' . ++Option::$options;
	}

	public function setChecked($checked) {
		$this->checked = $checked;
	}

	public function setValue($val) {
		$this->value = $val;
	}

	public function setClassNames($className) {
		$this->cssClassNames = $className;
	}

	public function toXml() {
		$xml = new \DOMDocument();
		$root = $xml->createElement('option');
		$root->setAttribute('id', $this->getId());
		$root->setAttribute('label', $this->getLabel());
		$root->setAttribute('value', $this->value);
		$root->setAttribute('classes', implode(' ', $this->getClasses()));
		$root->setAttribute('checked', $this->checked ? 'yes' : 'no');

		$xml->appendChild($root);

		return $xml;
	}
}
?>