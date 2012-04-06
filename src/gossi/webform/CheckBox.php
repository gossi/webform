<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents a Checkbox control.
 */
class CheckBox extends Checker {

	public function isChecked() {
		$tempChecked = $this->checked;
		$value = $this->getRequestValue() == null ? $this->value : $this->getRequestValue();

		$r = null;
		switch ($this->getWebform()->getMethod()) {
			case Webform::GET:
				$r = &$_GET;
				break;

			case Webform::POST:
				$r = &$_POST;
				break;
		}

		// see wether this CheckBox is checked by a passed formular

		// means array
		if (substr($this->name, -2) == '[]') {
			$name = substr($this->name, 0, -2);

			if (isset($r[$name]) && in_array($value, $r[$name])) {
				$this->checked = true;
			}
		}

		// anyway natural
		else if (isset($r[$this->name]) && $r[$this->name] == $value) {
			$this->checked = true;
		}
		
		// no request, use temp value
		else {
			$this->checked = $tempChecked;
		}
		return $this->checked;
	}

	public function toXML() {
		$xml = $this->createXML('CheckBox');
		$root = $xml->documentElement;
		$root->setAttribute('checked', $this->isChecked() ? 'yes' : 'no');
		$root->setAttribute('orientation', $this->getOrientation());

		return $xml;
	}
}
?>
