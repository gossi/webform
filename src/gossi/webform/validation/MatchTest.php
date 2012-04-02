<?php
namespace gossi\webform\validation;

class MatchTest extends AbstractTest {

	private $statement;

	/**
	 * Creates a new validation test
	 * 
	 * @param boolean $statement this statement will be tested
	 * @param String $message the error message when the statement is false
	 * @param \gossi\webform\Control[] $controls the affected controls
	 */
	public function __construct($message, $controls) {
		$this->message = $message;
		$this->controls = $controls;
	}
	
	public function validate() {
		if (count($this->controls) > 0) {
			$val = $this->controls[0]->getValue();
			
			foreach ($this->controls as $control) {
				if ($val !== $control->getValue()) {
					$this->addErrorToControls($this->message);
					throw new \Exception($this->message);
				}
			}
		}
	}
	
	public function toXML() {
		return $this->createXML('MatchTest');
	}
}