<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class SingleLine extends Control {

	public function toXml() {
		return $this->createXml('SingleLine');
	}
}
?>