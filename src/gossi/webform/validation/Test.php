<?php
namespace gossi\webform\validation;

class Test extends AbstractTest {

	private $statement;

	/**
	 * Creates a new validation test
	 * 
	 * @param boolean $statement this statement will be tested
	 * @param String $message the error message when the statement is false
	 * @param \gossi\webform\Control[] $controls the affected controls
	 */
	public function __construct($statement, $message, $controls = array()) {
		$this->statement = $statement;
		$this->message = $message;
		$this->controls = $controls;
	}

	public function getStatement() {
		return $this->statement;
	}
	
	public function toXML() {
		$xml = $this->createXML('Test');
		$xml->documentElement->setAttribute('statement', $this->getStatement());
		
		return $xml;
	}
	
	public function validate() {
		if (!$this->statement) {
			$this->addErrorToControls($this->message);
			throw new \Exception($this->message);
		}
	}
}