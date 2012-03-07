<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents a control with an email validator
 */
class Email extends SingleLine {
	
	public function __construct(IArea $parent) {
		parent::__construct($parent);
		$this->addValidator(new EmailValidator());
	}

	public function toXml() {
		return $this->createXml('Email');
	}
}
?>