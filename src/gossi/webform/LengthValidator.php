<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class LengthValidator extends Validator {

	private $min = null;
	private $max = null;

	public function validate($string) {
		if ($this->min !== null && strlen($string) < $this->min) {
			throw new WebformException(sprintf($this->webform->getI18n('error/tooshort'), $this->control->getLabel()));
		}

		if ($this->max !== null && strlen($string) > $this->max) {
			throw new WebformException(sprintf($this->webform->getI18n('error/toolong'), $this->control->getLabel()));
		}
	}

	public function parse(\DOMNode $node) {
		$attribs = $node->attributes;

		if ($min = $attribs->getNamedItem('min')) {
			$this->min = $min->value;
		}

		if ($max = $attribs->getNamedItem('max')) {
			$this->max = $max->value;
		}
	}

	public function setMin($min) {
		$this->min = $min;
	}

	public function setMax($max) {
		$this->max = $max;
	}

	public function toXml() {
		$xml = new \DOMDocument('1.0');
		$root = $xml->createElement('validator');
		$root->setAttribute('type', 'Length');
		$root->setAttribute('min', $this->min);
		$root->setAttribute('max', $this->max);
		$xml->appendChild($root);

		return $xml;
	}
}
?>