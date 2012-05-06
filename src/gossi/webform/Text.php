<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Just Text
 */
class Text extends Control {

	public function toXML() {
		return $this->createXml('Text');
	}
}
?>