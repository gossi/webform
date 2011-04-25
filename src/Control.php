<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;


abstract class Control extends Element implements IValidatable {

	private static $controls = 1;

	// config
	protected $name;
	protected $default = null;
	protected $validators = array();
	protected $validations = array();
	protected $error = false;

	// options
	protected $required = false;
	protected $disabled = false;
	protected $readonly = false;

	private $webform;

	/**
	 * Creates a new Control
	 * 
	 * @param IArea $parent
	 */
	public function __construct(IArea $parent) {
		$this->id = 'webform-control' . ++Control::$controls;
		$this->name = $this->id;
		$this->webform = $parent->getWebform();
		$this->webform->registerControl($this->id, $this);
		$parent->addControl($this);
	}

	protected function createXML($type) {
		$xml = new \DOMDocument();
		$root = $xml->createElement('control');
		$root->setAttribute('id', $this->getId());
		$root->setAttribute('label', $this->getLabel());
		$root->setAttribute('name', $this->getName());
		$root->setAttribute('description', $this->getDescription());
		$root->setAttribute('title', $this->getTitle());
		$root->setAttribute('value', $this->getValue());
		$root->setAttribute('error', $this->error ? 'yes' : 'no');
		$root->setAttribute('required', $this->required ? 'yes' : 'no');
		$root->setAttribute('disabled', $this->disabled ? 'yes' : 'no');
		$root->setAttribute('readonly', $this->readonly ? 'yes' : 'no');
		$root->setAttribute('classes', implode(' ', $this->getClasses()));
		$root->setAttribute('type', $type);

		$xml->appendChild($root);
		$this->appendValidators($xml);

		return $xml;
	}

	/**
	 * Returns the receiver's default value
	 * 
	 * @return String
	 */
	public function getDefault() {
		return $this->default;
	}

	/**
	 * Returns the receiver's value that is transmitted via http
	 * 
	 * @return String
	 */
	public function getRequestValue() {
		$method = $this->webform->getMethod();

		switch ($method) {
			case Webform::GET:
				return isset($_GET[$this->name]) ? trim($_GET[$this->name]) : null;

			case Webform::POST:
				return isset($_POST[$this->name]) ? trim($_POST[$this->name]) : null;
		}
	}

	/**
	 * Returns the receiver's value. When a value is transmitted via http, that value is 
	 * returned anyway the default value
	 * 
	 * @see #getDefault
	 * @see #getRequestValue
	 * @return String
	 */
	public function getValue() {
		return $this->getRequestValue() != null ? $this->getRequestValue() : $this->default;
	}

	/**
	 * Returns the receiver's name. Typically this is used as &lt;input name=""&gt;
	 * 
	 * @return String
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns the receiver's Webform
	 * 
	 * @return Webform
	 */
	public function getWebform() {
		return $this->webform;
	}

	/**
	 * Sets the receiver's name attribute of the &lt;input&gt; tag
	 *
	 * @param String $name the value for name
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}



	/**
	 * Sets the receiver's default value.
	 *
	 * @param String $default the default value
	 */
	public function setDefault($default) {
		$this->default = $default;
		return $this;
	}

	/**
	 * Sets the receiver's disabled state.
	 * 
	 * @param boolean $disabled true for disabled
	 */
	public function setDisabled($disabled) {
		$this->disabled = $disabled;
		return $this;
	}

	/*
	 * 
	 * @see gossi\webform.BaseElement::setId()
	 */
	public function setId($id) {
		if (!$this->name) {
			$this->name = $id;
		}
		$this->webform->updateControlRegistration($this->id, $id, $this);
		parent::setId($id);
		return $this;
	}

	/**
	 * Sets the receiver's readonly state.
	 * 
	 * @param boolean $readonly true for readonly
	 */
	public function setReadonly($readonly) {
		$this->readonly = $readonly;
		return $this;
	}

	/**
	 * Sets the receiver as a required field of the form
	 * 
	 * @param boolean $required true for required
	 */
	public function setRequired($required) {
		$this->required = $required;
		return $this;
	}

	public function addValidation(Validation $validation) {
		if (!in_array($validation, $this->validations)) {
			$this->validations[] = $validation;
		}
		return $this;
	}

	/**
	 * Adds a validator to the receiver.
	 * 
	 * @param Validator $validator the new validator
	 */
	public function addValidator(Validator $validator) {
		if (!in_array($validator, $this->validators)) {
			if ($validator->getControl() != $this) {
				$validator = clone $validator;
				$validator->setControl($this);
			}
			$this->validators[] = $validator;
		}
		return $this;
	}

	public function assertTrue($statement, $message) {
		$this->validations[] = new Validation($statement, $message);
	}

	public function removeValidation(Validation $validation) {
		if ($offset = array_search($validation, $this->validations)) {
			unset($this->validations[$offset]);
		}
		return $this;
	}

	/**
	 * Removes a validator from the receiver
	 * 
	 * @param Validator $validator
	 */
	public function removeValidator(Validator $validator) {
		if ($offset = array_search($validator, $this->validators)) {
			unset($this->validators[$offset]);
		}
		return $this;
	}

	/**
	 * Returns the receiver as XML.
	 */
	abstract public function toXML();

	public function appendValidators(\DOMDocument $xml) {
		$root = $xml->documentElement;
		foreach ($this->validators as $validator) {
			$root->appendChild($xml->importNode($validator->toXml()->documentElement, true));
		}
	}

	/**
	 * Validates the receiver
	 * 
	 * @throws Errors
	 */
	public function validate() {
		$val = $this->getRequestValue();
		$errors = new Errors();

		if ($this->required && empty($val)) {
			$errors->addError(sprintf($this->webform->getI18n('error/required'), $this->label));
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