<?php
namespace gossi\webform;

class Reset extends Control {

	public function toXml() {
		return $this->createXml('Reset');
	}
}
?>