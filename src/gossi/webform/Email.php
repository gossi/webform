<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

use gossi\webform\validation\EmailValidator;

/**
 * Represents a control with an email validator
 */
class Email extends SingleLine {
	
	public function __construct(IArea $parent, $config = array()) {
		parent::__construct($parent, $config);
		$this->addValidator(new EmailValidator());
	}

	public function toXML() {
		return $this->createXml('Email');
	}
}
?>