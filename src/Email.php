<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents a control with an email validator
 */
class Email extends Control {
	
	public function __construct(IArea $parent) {
		parent::__construct($parent);
		$this->addClass('webform-email-control');
		$this->addValidator(new EmailValidator());
	}

	public function toXml() {
		return $this->createXml('Email');
	}
}
?>