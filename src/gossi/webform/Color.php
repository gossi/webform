<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents a color control. User agents open the os color picker.
 */
class Color extends SingleLine {

	public function toXML() {
		return $this->createXml('Color');
	}
}
?>