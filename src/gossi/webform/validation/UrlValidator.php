<?php
namespace gossi\webform\validation;

/**
 * A validator to parse url.
 * 
 * @see https://github.com/aFarkas/webshim/blob/master/src/shims/form-shim-extend.js#L163
 */
class UrlValidator extends PatternValidator {

	public function __construct() {
		$this->setPattern("^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\\x00A0-\\xD7FF\\xF900-\\xFDCF\\xFDF0-\\xFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\\x00A0-\\xD7FF\\xF900-\\xFDCF\\xFDF0-\\xFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\\x00A0-\\xD7FF\\xF900-\\xFDCF\\xFDF0-\\xFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\\x00A0-\\xD7FF\\xF900-\\xFDCF\\xFDF0-\\xFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\\x00A0-\\xD7FF\\xF900-\\xFDCF\\xFDF0-\\xFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\\x00A0-\\xD7FF\\xF900-\\xFDCF\\xFDF0-\\xFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\\x00A0-\\xD7FF\\xF900-\\xFDCF\\xFDF0-\\xFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\\x00A0-\\xD7FF\\xF900-\\xFDCF\\xFDF0-\\xFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\\x00A0-\\xD7FF\\xF900-\\xFDCF\\xFDF0-\\xFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\\xE000-\\xF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\\x00A0-\\xD7FF\\xF900-\\xFDCF\\xFDF0-\\xFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$");
		$this->setModifiers('i');
	}

	public function toXML() {
		$xml = new \DOMDocument('1.0');
		$root = $xml->createElement('validator');
		$root->setAttribute('type', 'Url');
		$xml->appendChild($root);

		return $xml;
	}
}
?>