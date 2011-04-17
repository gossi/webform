<?php
namespace gossi\webform;

abstract class Control extends Element implements IValidationable {

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

	public function __construct(IArea $parent) {
		$this->id = 'webform-control' . ++Control::$controls;
		$this->name = $this->id;
		$this->webform = $parent->getWebform();
		$this->webform->registerControl($this->id, $this);
		$parent->addControl($this);
	}

	protected function createXML($type) {
		$xml = new DOMDocument();
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

	public function getDefault() {
		return $this->default;
	}

	public function getRequestValue() {
		$method = $this->webform->getMethod();

		switch ($method) {
			case Webform::GET:
				return isset($_GET[$this->name]) ? trim($_GET[$this->name]) : null;

			case Webform::POST:
				return isset($_POST[$this->name]) ? trim($_POST[$this->name]) : null;
		}
	}

	public function getValue() {
		return $this->getRequestValue() != null ? $this->getRequestValue() : $this->default;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 *
	 * @return Webform
	 */
	public function getWebform() {
		return $this->webform;
	}

	/**
	 * Sets the name attribute of a controls &lt;input&gt; tag
	 *
	 * @param String $name the value for name
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}



	/**
	 * Sets a default value for the &lt;input&gt;
	 *
	 * @param String $default the default value
	 */
	public function setDefault($default) {
		$this->default = $default;
		return $this;
	}

	/**
	 * Sets en- or disabled state for this &lt;input&gt;
	 */
	public function setDisabled($disabled) {
		$this->disabled = $disabled;
		return $this;
	}

	public function setId($id) {
		if (!$this->name) {
			$this->name = $id;
		}
		$this->webform->updateControlRegistration($this->id, $id, $this);
		parent::setId($id);
		return $this;
	}

	public function setReadonly($readonly) {
		$this->readonly = $readonly;
		return $this;
	}

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

	public function removeValidator(Validator $validator) {
		if ($offset = array_search($validator, $this->validators)) {
			unset($this->validators[$offset]);
		}
		return $this;
	}

	public abstract function toXML();

	public function appendValidators(DOMDocument $xml) {
		$root = $xml->documentElement;
		foreach ($this->validators as $validator) {
			$root->appendChild($xml->importNode($validator->toXml()->documentElement, true));
		}
	}

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