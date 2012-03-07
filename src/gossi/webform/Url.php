<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents a control with an url validator
 */
class Url extends SingleLine {
	
	public function __construct(IArea $parent) {
		parent::__construct($parent);
		$this->addValidator(new UrlValidator());
	}

	public function toXML() {
		return $this->createXml('Url');
	}
}
?>