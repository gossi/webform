<?php
namespace gossi\webform;

use gossi\webform\validation\Validator;
use gossi\webform\validation\Test;
use gossi\webform\validation\IValidatable;

abstract class Control extends Element implements IValidatable {

	private static $controls = 1;

	// config
	protected $name = null;
	protected $value = null;
	protected $dirname = null;
	protected $maxlength = null;
	protected $validators = array();
	protected $tests = array();
	protected $errors = array();

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
	public function __construct(IArea $parent, $config = array()) {
		parent::__construct($config);
		
		$this->config($config, array('default', 'dirname', 'disabled', 'maxlength', 'name', 'readonly', 'required'));
		
		Control::$controls++;
		
		if (is_null($this->id)) {
			$this->id = 'webform-control' . Control::$controls;
		}
		
		if (is_null($this->name)) {
			$this->name = $this->id;
		}

		$this->webform = $parent->getWebform();
		$this->webform->registerControl($this->id, $this);
		$parent->addControl($this);
	}
	
	public function addError($message) {
		$this->addClass('ui-invalid');
		$this->errors[] = $message;
	}
	
	public function addTest(Test $test) {
		if (!in_array($test, $this->tests)) {
			$test->addControl($this);
			$this->tests[] = $test;
		}
		return $this;
	}
	
	/**
	 * Adds a validator to the receiver.
	 *
	 * @param \gossi\webform\validation\Validator $validator the new validator
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
	
// 	public function appendValidators(\DOMDocument $xml) {
// 		$root = $xml->documentElement;
// 		foreach ($this->validators as $validator) {
// 			$root->appendChild($xml->importNode($validator->toXml()->documentElement, true));
// 		}
// 	}

	/**
	 * Creates a XML Document representing the abstract control.
	 *  
	 * @param String $type the type of the control
	 * @return \DOMDocument the XML Document
	 */
	protected function createXML($type) {
		$xml = new \DOMDocument('1.0', 'utf8');
		$root = $xml->createElement('control');
		$root->setAttribute('id', $this->getId());
		$root->setAttribute('label', $this->getLabel());
		$root->setAttribute('name', $this->getName());
		$root->setAttribute('description', $this->getDescription());
		$root->setAttribute('title', $this->getTitle());
		$root->setAttribute('value', mb_convert_encoding($this->getValue(), 'utf-8'));
		$root->setAttribute('dirname', $this->getDirname());
		$root->setAttribute('required', $this->required ? 'yes' : 'no');
		$root->setAttribute('disabled', $this->disabled ? 'yes' : 'no');
		$root->setAttribute('readonly', $this->readonly ? 'yes' : 'no');
		$root->setAttribute('classes', implode(' ', $this->getClasses()));
		$root->setAttribute('type', $type);

		$xml->appendChild($root);
		
		// validators
		foreach ($this->validators as $validator) {
			$root->appendChild($xml->importNode($validator->toXml()->documentElement, true));
		}
		
		// errors
		foreach ($this->errors as $error) {
			$root->appendChild($xml->createElement('error', $error));
		}

		return $xml;
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
	
	public function getErrors() {
		return $this->errors;
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
	 * @return boolean the <code>required</code> state
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
		return !is_null($rqvalue) ? $rqvalue : $this->value;
	}

	/**
	 * Returns the receiver's <code>Webform</code>.
	 * 
	 * @return \gossi\webform\Webform the associated <code>Webform</code>
	 */
	public function getWebform() {
		return $this->webform;
	}
	
	public function hasErrors() {
		return count($this->errors);
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
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
	
	/**
	 * Sets the receiver's <code>dirname</code> attribute.
	 * 
	 * The <code>dirname</code> attribute, when it applies, is a form control 
	 * <code>dirname</code> attribute.
	 * 
	 * <p class="example">In this example, a form contains a text field and a submission 
	 * button:</p>
	 * 
	 * <code class="example">&lt;form action="addcomment.cgi" method=post&gt;
	 * &lt;p&gt;&lt;label&gt;Comment: &lt;input type=text name="comment" dirname="comment.dir" required&gt;&lt;/label&gt;&lt;/p&gt;
	 * &lt;p&gt;&lt;button name="mode" type=submit value="add"&gt;Post Comment&lt;/button&gt;&lt;/p&gt;
	 * &lt;/form&gt;</code>
	 * 
	 * <p class="example">When the user submits the form, the user agent includes three
	 * fields, one called "comment", one called "comment.dir", and one
	 * called "mode"; so if the user types "Hello", the submission body
	 * might be something like:</p>
	 * 
	 * <code class="example">comment=Hello&amp;<strong>comment.dir=ltr</strong>&amp;mode=add</code>
	 * 
	 * <p class="example">If the user manually switches to a right-to-left writing
	 * direction and enters "<span dir="rtl" lang="ar" title="">&#1605;&#1585;&#1581;&#1576;&#1611;&#1575;</span>", the
	 * submission body might be something like:</p>
	 * 
	 * <code class="example">comment=%D9%85%D8%B1%D8%AD%D8%A8%D9%8B%D8%A7&amp;<strong>comment.dir=rtl</strong>&amp;mode=add</code>
	 *
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-dirname-attribute W3C Specification
	 * @see http://developers.whatwg.org/association-of-controls-and-forms.html#form-control-dirname-attribute form control dirname attribute
	 * @see getDirname
	 * 
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
	 * A form control is disabled if its <code>disabled</code> attribute is set, or if it is 
	 * a descendant of a fieldset element whose disabled attribute is set and is not a 
	 * descendant of that fieldset element's first legend element child, if any.
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
	 * 
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
	 * The <code>maxlength</code> attribute is a form control maxlength attribute.
	 * 
	 * If the <code>input</code> element has a maximum allowed value length, then the 
	 * code-point length of the value of the element's <code>value</code> attribute must be 
	 * equal to or less than the element's maximum allowed value length.
	 * 
	 * <p class="example">The following extract shows how a messaging client's text entry
	 * could be arbitrarily restricted to a fixed number of characters,
	 * thus forcing any conversation through this medium to be terse and
	 * discouraging intelligent discourse.</p>
	 * 
	 * <code class="example">&lt;label&gt;What are you doing? &lt;input name="status" maxlength="140"&gt;&lt;/label&gt;</code>
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-maxlength-attribute W3C Specification
	 * @see getMaxlength
	 * 
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
	 * The <code>name</code> attribute represents the form's name within the forms 
	 * collection. The value must not be the empty string, and the value must be unique 
	 * amongst the form elements in the forms collection that it is in, if any.
	 *
	 * @see http://developers.whatwg.org/forms.html#attr-form-name W3C Specification
	 * @see setId
	 * @see getName
	 * 
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
	public function removeTest(Test $test) {
		if ($offset = array_search($test, $this->tests)) {
			unset($this->tests[$offset]);
		}
		return $this;
	}

	/**
	 * Removes a validator from the receiver
	 * 
	 * @param \gossi\webform\validation\Validator $validator
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
		// reset validation
		$this->errors = array();
		$val = $this->getRequestValue();

		// check required
		if ($this->required && empty($val)) {
			$this->addError(sprintf($this->webform->getI18n('error/required'), $this->label));
		}

		// validate validators
		foreach ($this->validators as $validator) {
			try {
				$validator->validate($val);
			} catch (\Exception $e) {
				$this->addError($e->getMessage());
			}
		}

		// validate additional tests
		foreach ($this->tests as $test) {
			try {
				$test->validate($val);
			} catch (\Exception $e) {
				$this->addError($e->getMessage());
			}
		}

		// throw errors if present
		if ($this->hasErrors()) {
			$errors = new WebformErrors();
			$errors->addErrors($this->errors);
			throw $errors;
		}
	}
}
?>