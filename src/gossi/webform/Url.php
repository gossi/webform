<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

use gossi\webform\validation\UrlValidator;

/**
 * Represents a control with an url validator
 */
class Url extends SingleLine {
	
	public function __construct(IArea $parent, $config = array()) {
		parent::__construct($parent, $config);
		$this->addValidator(new UrlValidator());
	}

	public function toXML() {
		return $this->createXml('Url');
	}
}
?>