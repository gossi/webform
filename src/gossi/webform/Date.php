<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents a date control
 */
class Date extends SingleLine {
	
	public function __construct(IArea $parent) {
		parent::__construct($parent);
		$pv = new PatternValidator();
		$pv->setPattern('[0-9]{4}-[0-9]{2}-[0-9]{2}');
		$this->addValidator($pv);
	}

	public function toXml() {
		return $this->createXml('Date');
	}
}
?>