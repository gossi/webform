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

	protected $id = null;
	protected $classes = array();
	protected $label = null;
	
	public function __construct($config = array()) {
		$this->config($config, array('id', 'label', 'classes'));
	}
	
	protected function config($config, $fields) {
		foreach ($fields as $field) {
			$fn = 'set'.$field;
			$fn{3} = ucfirst($fn{3});
			
			if (isset($config[$field])) {
				$this->$fn($config[$field]);
			}
		}
	}

	/**
	 * Adds a CSS classname on the receiver
	 * 
	 * @param String $class the new class name
	 * @return \gossi\webform\BaseElement $this
	 */
	public function addClass($class) {
		if (!in_array($class, $this->classes)) {
			$this->classes[] = $class;
		}
		return $this;
	}

	/**
	 * Adds CSS classnames on the receiver. This method either accepts an array of strings
	 * or a whitespace delimited string.
	 * 
	 * @param String[]|String $classes
	 * @return \gossi\webform\BaseElement $this
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
	 * Returns the receiver's <code>id</code> attribute.
	 * 
	 * @see setId
	 * @see http://developers.whatwg.org/elements.html#the-id-attribute
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
	 * @return \gossi\webform\BaseElement $this
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
	 * @return \gossi\webform\BaseElement $this
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
	 * @param String[]|String $classes the new CSS classnames, as array or space delimited string
	 * @return \gossi\webform\BaseElement $this
	 */
	public function setClasses($classes) {
		if (!is_array($classes)) {
			$classes = empty($classes) ? array() : explode(' ', $classes);
		}

		$this->classes = $classes;
		return $this;
	}

	/**
	 * Sets the receiver's <code>id</code> attribute.
	 * 
	 * @see getId
	 * @see http://developers.whatwg.org/elements.html#the-id-attribute
	 * @param String $id the new id attribute
	 * @return \gossi\webform\BaseElement $this
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * Sets the label text for the control
	 *
	 * @param String $label the label text
	 * @return \gossi\webform\BaseElement $this
	 */
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}
}
?>