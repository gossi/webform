<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents a number control. Typically represented by a spinner.
 */
class Number extends SingleLine {

	public function toXML() {
		return $this->createXml('Number');
	}
}
?>