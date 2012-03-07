<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class Password extends SingleLine {

	public function toXml() {
		return $this->createXml('Password');
	}
}
?>