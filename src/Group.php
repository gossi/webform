<?php
namespace gossi\webform;

class Group extends Control implements IArea {

	const HORIZONTAL = 'horizontal';
	const VERTICAL = 'vertical';

	private $direction = Group::VERTICAL;
	private $controls = array();

	public function addControl(Control $control) {
		if (!in_array($control, $this->controls)) {
			$this->controls[] = $control;
		}
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
			$val = $this->controls[$i]->getRequestValue();
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
				$vals[] = $control->getDefault();
			}
		}
		return $vals;
	}

	public function removeControl(Control $control) {
		$offset = array_search($control, $this->controls);
		if ($offset) {
			unset($this->controls[$offset]);
		}
	}

	public function setDirection($direction) {
		$this->direction = $direction;
	}

	public function toXML() {
		$xml = $this->createXML('Group');
		$root = $xml->documentElement;
		$root->setAttribute('direction', $this->direction);

		foreach($this->controls as $control) {
			$import = $xml->importNode($control->toXml()->documentElement, true);
			$root->appendChild($import);
		}

		$xml->appendChild($root);

		return $xml;
	}

	public function validate() {
		$errors = new Errors();

		if ($this->required && !count($this->getValues())) {
			$errors->addError(sprintf($this->getWebform()->getI18n('error/required'), $this->label));
		}

		foreach ($this->validators as $validator) {
			try {
				$validator->validate($val);
			} catch (WebformException $e) {
				$errors->addError($e->getMessage());
			}
		}

		foreach ($this->validations as $validation) {
			if (!$validation->getStatement()) {
				$errors->addError($validation->getMessage());
			}
		}

		if ($errors->size()) {
			$this->error = true;
			$this->addClass('webform-control-error');
			throw $errors;
		}
	}
}
?>