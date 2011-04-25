<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * The BaseElement containing information about the CSS Classes, Label and ID.
 * This class is meant to be subclassed.
 */
class BaseElement {

	protected $id;
	protected $classes = array();
	protected $label;

	/**
	 * Adds a CSS classname on the receiver
	 * 
	 * @param String $class the new class name
	 */
	public function addClass($class) {
		if (!in_array($class, $this->classes)) {
			$this->classes[] = $class;
		}
		return $this;
	}

	/**
	 * Adds CSS classnames on the receiver
	 * 
	 * @param String[] $classes
	 */
	public function addClasses($classes) {
		if (!is_array($classes)) {
			$classes = empty($classes) ? array() : explode(' ', $classes);
		}

		foreach ($classes as $class) {
			$this->addClass($class);
		}
		return $this;
	}

	/**
	 * Returns the CSS classnames from the receiver
	 * 
	 * @return String[] 
	 */
	public function getClasses() {
		return $this->classes;
	}

	/**
	 * Returns the receiver's id.
	 * 
	 * @return String
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Returns the receiver's label.
	 * 
	 * @return String
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * Removes a CSS classname from the receiver
	 * 
	 * @param String $class the CSS classname
	 */
	public function removeClass($class) {
		if ($offset = array_search($class, $this->classes)) {
			unset($this->classes[$offset]);
		}
		return $this;
	}

	/**
	 * Remoces CSS classnames from the receiver
	 * 
	 * @param String[] $classes the CSS classnames
	 */
	public function removeClasses($classes) {
		if (!is_array($classes)) {
			$classes = explode(' ', $classes);
		}

		foreach ($classes as $class) {
			$this->removeClass($class);
		}
		return $this;
	}

	/**
	 * Sets the receiver's CSS classnames
	 * 
	 * @param String[] $classes the new CSS classnames
	 */
	public function setClasses($classes) {
		$this->classes = $classes;
		return $this;
	}

	/**
	 * Sets the receiver's id.
	 * 
	 * @param String $id the new id.
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * Sets the label text for the control
	 *
	 * @param String $label the label text
	 */
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}
}
?>