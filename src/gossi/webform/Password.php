<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class Password extends Control {

	public function toXml() {
		return $this->createXml('Password');
	}
}
?>