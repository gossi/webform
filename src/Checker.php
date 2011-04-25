<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Abstract representation of a checkable control.
 */
abstract class Checker extends Control {

	const LEFT = 'left';
	const RIGHT = 'right';

	protected $checked = false;
	private $orientation = Checker::RIGHT;

	/**
	 * Returns the receiver's layout orientation
	 * 
	 * @see Checker::LEFT
	 * @see Checker::RIGHT
	 */
	public function getOrientation() {
		return $this->orientation;
	}

	/**
	 * Returns the receiver's checked state.
	 * 
	 * @return boolean true for checked else false.
	 */
	abstract public function isChecked();

	/**
	 * Sets the receiver's checked state
	 * 
	 * @param boolean $checked true for checked else false.
	 */
	public function setChecked($checked) {
		$this->checked = $checked;
		return $this;
	}

	/**
	 * Sets the receiver's layout orientation
	 * 
	 * @param String $orientation
	 * @see Checker::LEFT
	 * @see Checker::RIGHT
	 */
	public function setOrientation($orientation) {
		$this->orientation = $orientation;
		return $this;
	}
}