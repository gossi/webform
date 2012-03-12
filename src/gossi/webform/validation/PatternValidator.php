<?php
namespace gossi\webform\validation;

/**
 * A pattern validator.
 * 
 * Internally preg_match is used with the pattern and modifier parameters. The slash (/) is 
 * used as delimiter:
 * <code>preg_match('/'.$pattern.'/'.$modifiers)</code>
 */
class PatternValidator extends Validator {

	private $pattern = '';
	private $modifiers = '';
	
	public function setPattern($pattern) {
		$this->pattern = $pattern;
	}
	
	public function setModifiers($modifiers) {
		$this->modifiers = $modifiers;
	}
	
	public function validate($string) {
		if (strlen($string)) {
			$modifiers = str_replace('uu', 'u', $this->modifiers.'u');
			if (!preg_match('/'.$this->pattern.'/'.$modifiers, $string)) {
				throw new \Exception(sprintf($this->webform->getI18n('error/invalid'), $this->control->getLabel()));
			}
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

	public function toXML() {
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