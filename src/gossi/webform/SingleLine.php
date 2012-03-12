<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

use gossi\webform\validation\PatternValidator;

class SingleLine extends Control {

	protected $autocomplete = null;
	protected $max;
	protected $min;
	protected $multiple;
	protected $pattern;
	protected $patternValidator;
	protected $placeholder;
	protected $step;
	protected $suggestions = array();
	
	public function __construct(IArea $parent, $config = array()) {
		parent::__construct($parent, $config);
		$this->config($config, array('autocomplete', 'max', 'min', 'multiple', 'pattern', 'placeholder', 'step', 'suggestions'));
	}
	
	/**
	 * Adds an item to the suggestion's list
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-list-attribute W3C Specification
	 * @see removeSuggestion
	 * @see setSuggestions
	 * @see getSuggestions
	 * @param mixed $item
	 * @return \gossi\webform\SingleLine $this
	 */
	public function addSuggestion($item) {
		if (!in_array($item, $this->suggestions)) {
			$this->suggestions[] = $item;
		}
		return $this;
	}
	
	protected function createXml($type) {
		$xml = parent::createXml($type);
		$root = $xml->documentElement;
		$root->setAttribute('autocomplete', $this->getAutocomplete() ? 'on' : 'off');
		$root->setAttribute('max', $this->getMax());
		$root->setAttribute('min', $this->getMin());
		$root->setAttribute('multiple', $this->getMultiple() ? 'yes' : 'no');
		$root->setAttribute('pattern', $this->getPattern());
		$root->setAttribute('placeholder', $this->getPlaceholder());
		$root->setAttribute('step', $this->getStep());
	
		if (count($this->getSuggestions())) {
			$suggestions = $xml->createElement('suggestions');
				
			foreach ($this->getSuggestions() as $item) {
				$option = $xml->createElement('option');
				$option->setAttribute('value', $item);
				$suggestions->appendChild($option);
			}
				
			$root->appendChild($suggestions);
		}
	
		return $xml;
	}
	
	/**
	 * Returns the receiver's <code>autocomplete</code> attribute.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-autocomplete-attribute W3C Specification
	 * @see setAutocomplete
	 * 
	 * @return mixed The <code>autocomplete</code> value: <ul>
	 * <li><code>true</code> - the <i>on</i> state</li>
	 * <li><code>false</code> - the <i>off</i> state</li>
	 * <li><code>null</code> - the <i>default</i> state</li>
	 * </ul>
	 */
	public function getAutocomplete() {
		return $this->autocomplete;
	}

	/**
	 * Returns the receiver's <code>max</code> attribute.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#attr-input-max W3C Specification
	 * @see setMax
	 * 
	 * @return mixed the max value
	 */
	public function getMax() {
		return $this->max;
	}
	
	/**
	 * Returns the receiver's <code>min</code> attribute.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#attr-input-min W3C Specification
	 * @see setMin
	 *
	 * @return mixed the min value
	 */
	public function getMin() {
		return $this->min;
	}
	
	/**
	 * Returns the receiver's <code>multiple</code> attribute.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-multiple-attribute W3C Specification
	 * @see setMultiple
	 * 
	 * @return boolean the multiple value 
	 */
	public function getMultiple() {
		return $this->multiple;
	}
	
	/**
	 * Returns the receiver's <code>pattern</code> attribute.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-pattern-attribute W3C Specification
	 * @see setPattern
	 * 
	 * @return String the pattern value
	 */
	public function getPattern() {
		return $this->pattern;
	}

	/**
	 * Returns the receiver's <code>placeholder</code> attribute.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-placeholder-attribute W3C Specification
	 * @see setPlaceholder
	 *
	 * @return String the placeholder value
	 */
	public function getPlaceholder() {
		return $this->placeholder;
	}
	
	/**
	 * Returns the receiver's <code>step</code> attribute.
	 *
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-step-attribute W3C Specification
	 * @see setStep
	 * 
	 * @return mixed the step value
	 */
	public function getStep() {
		return $this->step;
	}
	
	/**
	 * Returns the receiver's suggestions.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-list-attribute W3C Specification
	 * @see removeSuggestion
	 * @see setSuggestions
	 * @see getSuggestions
	 * 
	 * @return mixed[] the suggestions
	 */
	public function getSuggestions() {
		return $this->suggestions;
	}	
	
	/**
	 * Removes an item from the suggestion's list.
	 *
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-list-attribute W3C Specification
	 * @see addSuggestion
	 * @see setSuggestions
	 * @see getSuggestions
	 * 
	 * @param mixed $item
	 * @return \gossi\webform\SingleLine $this
	 */
	public function removeSuggestion($item) {
		if ($offset = array_search($item, $this->suggestions)) {
			unset($this->suggestions[$offset]);
		}
		return $this;
	}
	
	/**
	 * Sets the receiver's <code>autocomplete</code> attribute.
	 * 
	 * User agents sometimes have features for helping users fill forms in, for 
	 * example prefilling the user's address based on earlier user input.
	 * 
	 * The autocomplete attribute is an enumerated attribute. The attribute has three 
	 * states. The on keyword maps to the <i>on</i> state, and the off keyword maps to the 
	 * <i>off</i> state. The attribute may also be omitted. The <i>missing value default</i> 
	 * is the <i>default</i> state.
	 * 
	 * The <u>off</u> state indicates either that the control's input data is particularly 
	 * sensitive (for example the activation code for a nuclear weapon); or that it is a 
	 * value that will never be reused (for example a one-time-key for a bank login) and the 
	 * user will therefore have to explicitly enter the data each time, instead of being 
	 * able to rely on the UA to prefill the value for him; or that the document provides 
	 * its own autocomplete mechanism and does not want the user agent to provide 
	 * autocompletion values.
	 * 
	 * Conversely, the <u>on</u> state indicates that the value is not particularly 
	 * sensitive and the user can expect to be able to rely on his user agent to remember 
	 * values he has entered for that control.
	 * 
	 * The <u>default</u> state indicates that the user agent is to use the autocomplete 
	 * attribute on the element's form owner instead. (By default, the autocomplete
	 * attribute of form elements is in the on state.)
	 *
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-autocomplete-attribute W3C Specification
	 * @see getAutocomplete
	 * 
	 * @param mixed $autocomplete The new <code>autocomplete</code> value: <ul>
	 * <li><code>true</code> - the <i>on</i> state</li>
	 * <li><code>false</code> - the <i>off</i> state</li>
	 * <li><code>null</code> - the <i>default</i> state</li>
	 * </ul>
	 * @return \gossi\webform\SingleLine $this
	 */
	public function setAutocomplete($autocomplete) {
		$this->autocomplete = $autocomplete;
		return $this;	
	}
	
	/**
	 * Sets the receiver's <code>max</code> attribute.
	 * 
	 * The <code>max</code> attribute defines the upper range for values of the receiver.
	 * The <code>max</code> value must not be less than the <code>min</code> value.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#attr-input-max W3C Specification
	 * @see getMax
	 * 
	 * @param mixed $max the new <code>max</code> value
	 * @return \gossi\webform\SingleLine $this
	 */
	public function setMax($max) {
		$this->max = $max;
		return $this;
	}
	
	/**
	 * Sets the receiver's <code>min</code> attribute.
	 * 
	 * The <code>min</code> attribute defines the lower range for values of the receiver.
	 * The <code>min</code> value must not be more than the <code>max</code> value.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#attr-input-min W3C Specification
	 * @see getMin
	 * 
	 * @param mixed $min the new <code>min</code> value
	 * @return \gossi\webform\SingleLine $this
	 */
	public function setMin($min) {
		$this->min = $min;
		return $this;
	}
	
	/**
	 * Sets the receiver's <code>multiple</code> attribute.
	 *
	 * The <code>multiple</code> attribute is a boolean attribute that indicates whether 
	 * the user is to be allowed to specify more than one value.
	 * 
	 * <p class="example">The following extract shows how an e-mail client's "Cc" field 
	 * could accept multiple e-mail addresses.</p>
	 * 
	 * <code class="example">&lt;label&gt;Cc: &lt;input type="email" multiple 
	 * name="cc"&gt; &lt;/label&gt;</code>
	 * 
	 * <p class="example">The following extract shows how an e-mail client's "Attachments" 
	 * field could accept multiple files for upload.</p>
	 * 
	 * <code class="example">&lt;label&gt;Attachments: &lt;input type="file" multiple 
	 * name="att"&gt;&lt;/label&gt;</code>
	 *
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-multiple-attribute W3C Specification
	 * @see getMultiple
	 * 
	 * @param boolean $multiple the new <code>multiple</code> value
	 * @return \gossi\webform\SingleLine $this
	 */
	public function setMultiple($multiple) {
		$this->multiple = $multiple;
		return $this;
	}
	
	/**
	 * Sets the receiver's <code>pattern</code> attribute.
	 * 
	 * The <code>pattern</code> attribute specifies a regular expression against which the 
	 * control's value, or, when the multiple attribute applies and is set, the control's 
	 * values, are to be checked.
	 * 
	 * If specified, the attribute's value must match the JavaScript and PHP Pattern 
	 * production.
	 * 
	 * When an input element has a pattern attribute specified, authors should include a 
	 * title attribute to give a description of the pattern. User agents may use the 
	 * contents of this attribute, if it is present, when informing the user that the 
	 * pattern is not matched, or at any other suitable time, such as in a tooltip or read 
	 * out by assistive technology when the control gains focus.
	 * 
	 * <p class="example">For example, the following snippet:</p>
	 * 
	 * <code class="example">
	 * &lt;label&gt;Part number:
	 *    &lt;input pattern="[0-9][A-Z]{3}" name="part"
	 *    title="A part number is a digit followed by three uppercase letters."&gt;
	 * &lt;/label&gt;
	 * </code>
	 * 
	 * <p class="example">...could cause the UA to display an alert such as:</p>
	 * 
	 * <code class="example">A part number is a digit followed by three uppercase letters.
	 * You cannot submit this form when the field is incorrect.</code>
	 * 
	 * When a control has a pattern attribute, the title attribute, if used, must describe 
	 * the pattern. Additional information could also be included, so long as it assists the 
	 * user in filling in the control. Otherwise, assistive technology would be impaired.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-pattern-attribute W3C Specification
	 * @see getPattern
	 * 
	 * @param String $pattern the new <code>pattern</code> value
	 * @return \gossi\webform\SingleLine $this
	 */
	public function setPattern($pattern) {
		$this->pattern = $pattern;
		if (is_null($this->patternValidator)) {
			$this->patternValidator = new PatternValidator();
			$this->addValidator($this->patternValidator);
		}
		$this->patternValidator->setPattern($pattern);
		return $this;
	}
	
	/**
	 * Sets the receiver's <code>placeholder</code> attribute.
	 * 
	 * The <code>placeholder</code> attribute represents a short hint (a word or short 
	 * phrase) intended to aid the user with data entry. A hint could be a sample value 
	 * or a brief description of the expected format. The attribute, if specified, must 
	 * have a value that contains no U+000A LINE FEED (LF) or U+000D CARRIAGE RETURN (CR) 
	 * characters.
	 * 
	 * For a longer hint or other advisory text, the <code>title</code> attribute is more 
	 * appropriate.
	 * 
	 * The <code>placeholder</code> attribute should not be used as an alternative to a 
	 * <code>label</code>.
	 *  
	 * <p class="example">Here is an example of a mail configuration user interface that
	 * uses the <code>placeholder</code> attribute:</p>
	 * 
	 * <code class="example">&lt;fieldset&gt;
	 * &lt;legend&gt;Mail Account&lt;/legend&gt;
	 * &lt;p&gt;&lt;label&gt;Name: &lt;input type="text" name="fullname" placeholder="John Ratzenberger"&gt;&lt;/label&gt;&lt;/p&gt;
	 * &lt;p&gt;&lt;label&gt;Address: &lt;input type="email" name="address" placeholder="john@example.net"&gt;&lt;/label&gt;&lt;/p&gt;
	 * &lt;p&gt;&lt;label&gt;Password: &lt;input type="password" name="password"&gt;&lt;/label&gt;&lt;/p&gt;&lt;p&gt;&lt;label&gt;Description: &lt;input type="text" name="desc" placeholder="My Email Account"&gt;&lt;/label&gt;&lt;/p&gt;
	 * &lt;/fieldset&gt;</code>
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-placeholder-attribute W3C Specification
	 * @see getPlaceholder
	 * 
	 * @param String $placeholder the new <code>placeholder</code> value
	 * @return \gossi\webform\SingleLine $this
	 */
	public function setPlaceholder($placeholder) {
		$this->placeholder = $placeholder;
		return $this;
	}
	
	/**
	 * Sets the receiver's <code>step</code> attribute.
	 * 
	 * The <code>step</code> attribute indicates the granularity that is expected (and 
	 * required) of the value, by limiting the allowed values.
	 * 
	 * The <code>step</code> attribute, if specified, must either have a value that is a 
	 * valid floating point number that parses to a number that is greater than zero, or
	 * must have a value that is an ASCII case-insensitive match for the string "any".
	 * 
	 * <p class="example">The following range control only accepts values in the range 0..1, 
	 * and allows 256 steps in that range:</p>
	 * 
	 * <code class="example">&lt;input name="opacity" type="range" min="0" max="1" 
	 * step="0.00392156863"&gt;</code>
	 * 
	 * <p class="example">The following control allows any time in the day to be selected,
	 * with any accuracy (e.g. thousandth-of-a-second accuracy or more):</p>
	 * 
	 * <code class="example">&lt;input name="favtime" type="time" step="any"&gt;</code>
	 * 
	 * Normally, time controls are limited to an accuracy of one minute.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-step-attribute W3C Specification
	 * @see getStep
	 * 
	 * @param mixed $step the new <code>step</code> value
	 * @return \gossi\webform\SingleLine $this
	 */
	public function setStep($step) {
		$this->step = $step;
		return $this;
	}
	
	/**
	 * Sets suggestions for the receiver. A datalist will be appended to the DOM and linked
	 * to the receiver.
	 * 
	 * @see http://developers.whatwg.org/common-input-element-attributes.html#the-list-attribute W3C Specification
	 * @see addSuggestion
	 * @see removeSuggestion
	 * @see getSuggestions
	 * 
	 * @param mixed[] $suggestions array of strings with suggested items
	 * @return \gossi\webform\SingleLine $this
	 */
	public function setSuggestions($suggestions) {
		$this->suggestions = $suggestions;
		return $this;
	}
	
	/*
	 * (non-PHPdoc)
	 * @see gossi\webform.Control::toXML()
	 */
	public function toXML() {
		return $this->createXml('SingleLine');
	}
}