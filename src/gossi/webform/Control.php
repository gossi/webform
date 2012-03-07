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
	protected $dirname = null;
	protected $maxlength = null;
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
	public function __construct(IArea $parent, $id = null) {
		Control::$controls++;
		$this->id = is_null($id) ? 'webform-control' . Control::$controls : $id;
		$this->name = $this->id;
		$this->webform = $parent->getWebform();
		$this->webform->registerControl($this->id, $this);
		$parent->addControl($this);
	}
	
	public function addTest($statement, $message) {
		$this->validations[] = new Validation($statement, $message);
	}
	
	/*
	 * (non-PHPdoc)
	 * @see \gossi\webform\IValidatable::addValidation()
	 */
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
	 * @return \gossi\webform\Control $this
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
	
	public function appendValidators(\DOMDocument $xml) {
		$root = $xml->documentElement;
		foreach ($this->validators as $validator) {
			$root->appendChild($xml->importNode($validator->toXml()->documentElement, true));
		}
	}

	/**
	 * Creates a XML Document representing the abstract control.
	 *  
	 * @param String $type the type of the control
	 * @return \DOMDocument the XML Document
	 */
	protected function createXML($type) {
		$xml = new \DOMDocument();
		$root = $xml->createElement('control');
		$root->setAttribute('id', $this->getId());
		$root->setAttribute('label', $this->getLabel());
		$root->setAttribute('name', $this->getName());
		$root->setAttribute('description', $this->getDescription());
		$root->setAttribute('title', $this->getTitle());
		$root->setAttribute('value', $this->getValue());
		$root->setAttribute('dirname', $this->getDirname());
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
	 * Returns the receiver's default value.
	 * 
	 * @see http://developers.whatwg.org/the-input-element.html#attr-input-value W3C Specification
	 * @see setDefault
	 * @see getValue
	 * @return String the default value
	 */
	public function getDefault() {
		return $this->default;
	}
	
	/**
	 * Returns the receiver's <code>dirname</code> attribute.
	 *
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-dirname-attribute W3C Specification
	 * @see setDirname
	 * @return String The receiver's <code>dirname</code> value
	 */
	public function getDirname() {
		return $this->dirname;
	}

	/**
	 * Returns the receiver's <code>disabled</code> attribute.
	 * 
	 * @see http://developers.whatwg.org/association-of-controls-and-forms.html#attr-fe-disabled W3C Specification
	 * @see setDisabled
	 * @return boolean the <code>disabled</code> state
	 */
	public function getDisabled() {
		return $this->disabled;
	}
	
	/**
	 * Returns the receiver's <code>maxlength</code> attribute.
	 *
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-maxlength-attribute W3C Specification
	 * @see setMaxlength
	 * @return int the <code>maxlength</code> value
	 */
	public function getMaxlength() {
		return $this->maxlength;
	}
	
	/**
	 * Returns the receiver's <code>name</code> attribute.
	 *
	 * @see http://developers.whatwg.org/forms.html#attr-form-name W3C Specification
	 * @see setId
	 * @see setName
	 * @return String the <code>name</code> value
	 */
	public function getName() {
		return $this->name;
	}

	/**
	* Returns the receiver's <code>readonly</code> attribute.
	*
	* @see http://developers.whatwg.org/common-input-element-attributes.html#the-readonly-attribute W3C Specification
	* @see setReadonly
	* @param boolean $readonly true for readonly
	* @return boolean the <code>readonly</code> state
	*/
	public function getReadonly() {
		return $this->readonly;
	}

	/**
	 * Returns the receiver's value that is transmitted via HTTP.
	 * 
	 * @return String the request's value
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
	 * Returns the receiver's <code>required</code> state.
	 *
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-required-attribute W3C Specification
	 * @see setRequired
	 * @return $required the <code>required</code> state
	 */
	public function getRequired() {
		return $this->required;
	}
	
	/**
	 * Returns the receiver's value. When a value is transmitted via HTTP, that value is 
	 * returned anyway the default value.
	 * 
	 * @see getDefault
	 * @see getRequestValue
	 * @return String the receiver's value
	 */
	public function getValue() {
		$rqvalue = $this->getRequestValue();
		return !is_null($rqvalue) ? $rqvalue : $this->getDefault();
	}

	/**
	 * Returns the receiver's <code>Webform</code>.
	 * 
	 * @return \gossi\webform\Webform the associated <code>Webform</code>
	 */
	public function getWebform() {
		return $this->webform;
	}

	/**
	 * Sets the receiver's default value.
	 *
	 * @see http://developers.whatwg.org/the-input-element.html#attr-input-value W3C Specification
	 * @see getDefault
	 * @see getValue
	 * @param String $default the default value
	 * @return \gossi\webform\Control $this
	 */
	public function setDefault($default) {
		$this->default = $default;
		return $this;
	}
	
	/**
	 * Sets the receiver's <code>dirname</code> attribute.
	 *
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-dirname-attribute W3C Specification
	 * @see getDirname
	 * @param String $dirname the new dirname value
	 * @return \gossi\webform\Control $this
	 */
	public function setDirname($dirname) {
		$this->dirname = $dirname;
		return $this;
	}

	/**
	 * Sets the receiver's <code>disabled</code> attribute.
	 * 
	 * @see http://developers.whatwg.org/association-of-controls-and-forms.html#attr-fe-disabled W3C Specification
	 * @see getDisabled
	 * @param boolean $disabled the new disabled state
	 * @return \gossi\webform\Control $this
	 */
	public function setDisabled($disabled) {
		$this->disabled = $disabled;
		return $this;
	}

	/**
	 * Sets the receiver's <code>id</code> attribute. If a name is not set yet, the name 
	 * attribute will be set to the new id value, too.
	 * 
	 * @see http://developers.whatwg.org/elements.html#the-id-attribute W3C Specification
	 * @see setName
	 * @see getId
	 * @see \gossi\webform\BaseElement::setId()
	 * @return \gossi\webform\Control $this
	 */
	public function setId($id) {
		if (!$this->name) {
			$this->name = $id;
		}
		$this->unregisterControl($this->id);
		$this->registerControl($id, $this);
		parent::setId($id);
		return $this;
	}
	
	/**
	 * Sets the receiver's <code>maxlength</code> attribute.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-maxlength-attribute W3C Specification
	 * @see getMaxlength
	 * @param int $maxlength the new <code>maxlength</code> value
	 * @return \gossi\webform\Control $this
	 */
	public function setMaxlength($maxlength) {
		$this->maxlength = $maxlength;
		return $this;
	}
	
	/**
	 * Sets the receiver's <code>name</code> attribute.
	 *
	 * @see http://developers.whatwg.org/forms.html#attr-form-name W3C Specification
	 * @see setId
	 * @see getName
	 * @param String $name the new <code>name</code> value
	 * @return \gossi\webform\Control $this
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Sets the receiver's <code>readonly</code> attribute.
	 * 
	 * The <code>readonly</code> attribute is a boolean attribute that controls whether or 
	 * not the user can edit the form control. 
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-readonly-attribute W3C Specification
	 * @see getReadonly
	 * @param boolean $readonly the new <code>readonly</code> state
	 * @return \gossi\webform\Control $this
	 */
	public function setReadonly($readonly) {
		$this->readonly = $readonly;
		return $this;
	}

	/**
	 * Sets the receiver's <code>required</code> attribute.
	 * 
	 * The <code>required</code> attribute is a boolean attribute. When specified, the element 
	 * is required.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-required-attribute W3C Specification
	 * @see getRequired
	 * @param boolean $required the new <code>required</code> state
	 * @return \gossi\webform\Control $this
	 */
	public function setRequired($required) {
		$this->required = $required;
		return $this;
	}


	/*
	 * (non-PHPdoc)
	 * @see gossi\webform.IValidatable::removeValidation()
	 */
	public function removeValidation(Validation $validation) {
		if ($offset = array_search($validation, $this->validations)) {
			unset($this->validations[$offset]);
		}
		return $this;
	}

	/**
	 * Removes a validator from the receiver
	 * 
	 * @param \gossi\webform\Validator $validator
	 * @return \gossi\webform\Control $this
	 */
	public function removeValidator(Validator $validator) {
		if ($offset = array_search($validator, $this->validators)) {
			unset($this->validators[$offset]);
		}
		return $this;
	}

	/**
	 * Returns the receiver as XML.
	 * @return \DOMDocument the receiver's XML representation
	 */
	abstract public function toXML();

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