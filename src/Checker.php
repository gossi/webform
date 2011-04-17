<?php
namespace gossi\webform;

abstract class Checker extends Control {

	const LEFT = 'left';
	const RIGHT = 'right';

	protected $checked = false;
	private $orientation = Radio::RIGHT;

	public function getOrientation() {
		return $this->orientation;
	}

	abstract public function isChecked();

	public function setChecked($checked) {
		$this->checked = $checked;
		return $this;
	}

	public function setOrientation($orientation) {
		$this->orientation = $orientation;
		return $this;
	}
}