<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * A pattern validator.
 * 
 * Internally preg_match is used with the pattern and modifier parameters. The slash (/) is 
 * used as delimiter:
 * <code>preg_match('/'.$pattern.'/'.$modifiers)</code>
 */
class PatternValidator extends Validator {

	private $pattern;
	private $modifiers;
	
	public function setPattern($pattern) {
		$this->pattern = $pattern;
	}
	
	public function setModifiers($modifiers) {
		$this->modifiers = $modifiers;
	}
	
	public function validate($string) {
		if (!preg_match('/'.$this->pattern.'/'.$this->modifiers, $string)) {
			throw new WebformException(sprintf($this->webform->getI18n('error/invalid'), $this->control->getLabel()));
		}
	}

	public function parse(\DOMNode $node) {
		$attribs = $node->attributes;
		
		if ($pattern = $attribs->getNamedItem('pattern')) {
			$this->pattern = $pattern->value;
		}
		
		if ($modifiers = $attribs->getNamedItem('modifiers')) {
			$this->modifiers = $modifiers->value;
		}
	}

	public function toXml() {
		$xml = new \DOMDocument('1.0');
		$root = $xml->createElement('validator');
		$root->setAttribute('type', 'Pattern');
		$root->setAttribute('pattern', $this->pattern);
		$root->setAttribute('modifiers', $this->modifiers);
		$xml->appendChild($root);

		return $xml;
	}
}
?>