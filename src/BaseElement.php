<?php
namespace gossi\webform;

class BaseElement {

	protected $id;
	protected $classes = array();
	protected $label;

	public function addClass($class) {
		if (!in_array($class, $this->classes)) {
			$this->classes[] = $class;
		}
		return $this;
	}

	public function addClasses($classes) {
		if (!is_array($classes)) {
			$classes = empty($classes) ? array() : explode(' ', $classes);
		}

		foreach ($classes as $class) {
			$this->addClass($class);
		}
		return $this;
	}

	public function getClasses() {
		return $this->classes;
	}

	public function getId() {
		return $this->id;
	}

	public function getLabel() {
		return $this->label;
	}

	public function removeClass($class) {
		if ($offset = array_search($class, $this->classes)) {
			unset($this->classes[$offset]);
		}
		return $this;
	}

	public function removeClasses($classes) {
		if (!is_array($classes)) {
			$classes = explode(' ', $classes);
		}

		foreach ($classes as $class) {
			$this->removeClass($class);
		}
		return $this;
	}

	public function setClasses($classes) {
		$this->classes = $classes;
		return $this;
	}

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