<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents a tel control. User Agents may open an adressbook.
 */
class Tel extends SingleLine {

	public function toXML() {
		return $this->createXml('Tel');
	}
}
?>