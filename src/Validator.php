<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

abstract class Validator {

	protected $control;
	protected $webform;

	public function getControl() {
		return $this->control;
	}

	public function setControl(Control $control) {
		$this->control = $control;
		$this->webform = $control->getWebform();
	}

	abstract public function parse(\DOMNode $node);
	abstract public function validate($string);
	abstract public function toXml();
}
?>