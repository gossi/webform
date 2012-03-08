<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents a search control.
 * 
 * Note: At the moment, css styling is tricky...
 */
class Search extends SingleLine {

	public function toXML() {
		return $this->createXml('Search');
	}
}
?>