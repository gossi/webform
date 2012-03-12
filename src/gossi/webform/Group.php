<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class Group extends Control implements IArea {

	const HORIZONTAL = 'horizontal';
	const VERTICAL = 'vertical';

	private $orientation = Group::VERTICAL;
	private $controls = array();

	public function addControl(Control $control) {
		if (!in_array($control, $this->controls)) {
			$this->controls[] = $control;
		}
		return $this;
	}

	/**
	 * Loop the controls, return the first found value. Returns <code>null</code>
	 * if there is no value found.
	 *
	 * Mostly the case, when you grouping radio buttons and get the selected value.
	 *
	 * @see Control::getValue()
	 */
	public function getValue() {
		$val = null;
		$len = count($this->controls);
		for ($i = 0; is_null($val) && $i < $len; ++$i) {
			$val = $this->controls[$i]->getValue();
		}
		return $val;
	}

	/**
	 * Loop the controls, returns all values in an array.
	 *
	 * Mostly the case, when grouping checkboxes and get the selected values.
	 */
	public function getValues() {
		$vals = array();
		foreach ($this->controls as $control) {
			if ($control instanceof Checker && $control->isChecked()) {
				$vals[] = $control->getValue();
			}
		}
		return $vals;
	}

	public function removeControl(Control $control) {
		$offset = array_search($control, $this->controls);
		if ($offset) {
			unset($this->controls[$offset]);
		}
		return $this;
	}

	public function setOrientation($orientation) {
		// remove classes
		$this->removeClasses(array('webform-group-horizontal', 'webform-group-vertical'));
		
		// add class
		$this->addClass('webform-group-'.$orientation);
		
		// save it
		$this->orientation = $orientation;
		
		return $this;
	}

	public function toXML() {
		$xml = $this->createXML('Group');
		$root = $xml->documentElement;
		$root->setAttribute('orientation', $this->orientation);

		foreach($this->controls as $control) {
			$import = $xml->importNode($control->toXML()->documentElement, true);
			$root->appendChild($import);
		}

		$xml->appendChild($root);

		return $xml;
	}

	public function validate() {
		$errors = null;
		try {
			parent::validate();
		} catch(WebformErrors $errs) {
			$errors = $errs;
		}

		if ($this->required && !count($this->getValues())) {
			if (is_null($errors)) {
				$errors = new WebformErrors();
			}
			$e = sprintf($this->getWebform()->getI18n('error/required'), $this->label);
			$this->addError($e);
			$errors->addError($e);
		}
		
		// throw errors if present
		if (!is_null($errors)) {
			throw $errors;
		}
	}
}
?>