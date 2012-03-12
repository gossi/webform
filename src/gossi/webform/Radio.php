<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class Radio extends Checker {

	public function __construct(IArea $parent, $config = array()) {
		parent::__construct($parent, $config);
		$this->setName($parent->getId());
	}

	public function isChecked() {
		$tempChecked = $this->checked;
		$r = null;
		switch ($this->getWebform()->getMethod()) {
			case Webform::GET:
				$r = &$_GET;
				break;

			case Webform::POST:
				$r = &$_POST;
				break;
		}

		if (isset($r[$this->name]) && $r[$this->name] == $this->default) {
			$this->checked = true;
		} else {
			$this->checked = $tempChecked;
		} 
		return $this->checked;
	}

	public function toXML() {
		$xml = $this->createXML('Radio');
		$root = $xml->documentElement;
		$root->setAttribute('checked', $this->isChecked() ? 'yes' : 'no');
		$root->setAttribute('orientation', $this->getOrientation());

		return $xml;
	}
}
?>