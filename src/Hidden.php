<?php
namespace gossi\webform;

class Hidden extends Control {

	public function toXML() {
		return $this->createXML('Hidden');
	}
}
?>