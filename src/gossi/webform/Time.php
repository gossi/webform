<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

use gossi\webform\validation\PatternValidator;

/**
 * Represents a date control
 */
class Time extends SingleLine {

	public function __construct(IArea $parent, $config = array()) {
		parent::__construct($parent, $config);
		$pv = new PatternValidator();
		$pv->setPattern('[0-9]{2}:[0-9]{2}');
		$this->addValidator($pv);
	}
	
	public function toXML() {
		return $this->createXml('Time');
	}
}
?>