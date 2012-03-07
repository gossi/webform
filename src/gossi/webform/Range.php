<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents a range control. Typically represented with a slider.
 */
class Range extends SingleLine {

	public function toXML() {
		return $this->createXml('Range');
	}
}
?>