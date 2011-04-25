<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class Hidden extends Control {

	public function toXml() {
		return $this->createXml('Hidden');
	}
}
?>