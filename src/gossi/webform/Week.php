<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents a date control
 */
class Week extends SingleLine {

	public function toXML() {
		return $this->createXml('Week');
	}
}
?>