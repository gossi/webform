<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * THE Webform.
 */
class Webform implements IArea, IComposite, IValidatable {

	const POST = 'post';
	const GET = 'get';

	const DESC_LABEL = 'desc-label';
	const DESC_BETWEEN = 'desc-between';
	const DESC_END = 'desc-end';

	private $target;
	private $method = Webform::POST;
	private $desc = Webform::DESC_LABEL;
	private $areas = array();
	private $lang;
	private $i18n = null;
	private $i18nFile = null;
	private $errors = null;
	private $allControls = array();
	private $controls = array();
	private $validations = array();

	protected $template = null;

	public function __construct($lang = 'en') {
		$this->lang = $lang;
		$this->loadLanguage();
	}

	public function addControl(Control $control) {
		if (!in_array($control, $this->controls)) {
			$this->controls[] = $control;
		}
	}

	public function addArea(Area $area) {
		if (!array_key_exists($area->getId(), $this->areas)) {
			$this->areas[$area->getId()] = $area;
		}
	}

	public function addValidation(Validation $validation) {
		if (!in_array($validation, $this->validations)) {
			$this->validations[] = $validation;
		}
		return $this;
	}

	public function assertTrue($statement, $message) {
		$this->validations[] = new Validation($statement, $message);
	}

	public function getArea($id) {
		if (array_key_exists($id, $this->areas)) {
			return $this->areas[$id];
		}
		return null;
	}

	public function getControl($id) {
		if (array_key_exists($id, $this->allControls)) {
			return $this->allControls[$id];
		}
		return null;
	}

	public function getI18n($path) {
		if (!is_null($this->i18n)) {
			$xpath = new \DOMXPath($this->i18n);
			$entries = $xpath->query($path, $this->i18n->documentElement);
			if ($entries->length) {
				return $entries->item(0)->nodeValue;
			}
		}
	}

	public function getI18nFile() {
		if (is_null($this->i18nFile)) {
			$this->i18nFile = sprintf('%s/i18n/%s.xml', dirname(__FILE__), $this->lang);
		}
		return $this->i18nFile;
	}

	public function getMethod() {
		return $this->method;
	}

	public function getTarget() {
		return $this->target;
	}

	public function getTemplate($type = 'webform') {
		// get default template
		if ($this->template === null) {
			return dirname(__FILE__).'/templates/'.$type.'.xsl';
		} else {
			return $this->template;
		}
	}

	public function getWebform() {
		return $this;
	}

	private function loadLanguage() {
		$langFile = $this->getI18nFile();
		if (file_exists($langFile)) {
			$this->i18n = new \DOMDocument();
			$this->i18n->load($langFile);
		}
	}

	public static function parseXML($filePath) {
		if (file_exists($filePath)) {
			$doc = new \DOMDocument();
			$doc->load($filePath);
			return Webform::parseXMLDoc($doc);
		}
	}

	public static function parseXMLDoc(\DOMDocument $doc) {
		return Webform::parseXMLNode($doc->documentElement);
	}

	/**
	 * Parsing a given tree by passing the root node.
	 *
	 * @return Webform
	 */
	public static function parseXMLNode(\DOMNode $node) {
		$parser = new Parser($node);
		return $parser->parse();
	}

	public function registerControl($id, Control $control) {
		if (array_key_exists($id, $this->allControls)) {
			throw new WebformException('Control with given id ('+$id+') already exists in this Webform');
		}
		$this->allControls[$id] = $control;
	}

	public function removeControl(Control $control) {
		$offset = array_search($control, $this->controls);
		if ($offset) {
			unset($this->controls[$offset]);
		}
	}

	public function removeArea(Area $area) {
		if ($offset = array_search($area, $this->areas)) {
			unset($this->areas[$offset]);
		}
	}

	public function removeValidation(Validation $validation) {
		if ($offset = array_search($validation, $this->validations)) {
			unset($this->validations[$offset]);
		}
		return $this;
	}

	public function setDescriptionPosition($position) {
		$this->desc = $position;
		return $this;
	}

	public function setErrors(Errors $e) {
		$this->errors = $e;
		return $this;
	}

	public function setMethod($method) {
		$this->method = $method;
		return $this;
	}

	public function setTarget($target) {
		$this->target = $target;
		return $this;
	}

	public function setTemplate($template) {
		$this->template = $template;
		return $this;
	}

	public function toXML() {
		$xml = new \DOMDocument();
		$root = $xml->createElement('webform');
		$root->setAttribute('target', $this->target);
		$root->setAttribute('method', $this->method);
		$root->setAttribute('description-position', $this->desc);

		if (!is_null($this->errors)) {
			$errs = $xml->importNode($this->errors->toXML()->documentElement, true);
			$errs->setAttribute('occur', $this->getI18n('error/occur'));
			$root->appendChild($errs);
		}

		foreach ($this->areas as $area) {
			$root->appendChild($xml->importNode($area->toXML()->documentElement, true));
		}

		foreach ($this->controls as $control) {
			$root->appendChild($xml->importNode($control->toXML()->documentElement, true));
		}

		$xml->appendChild($root);
		return $xml;
	}

	public function updateControlRegistration($oldId, $newId, $control) {
		if (!array_key_exists($oldId, $this->allControls)) {
			throw new WebformException('Control with given id ('+$oldId+') does not exists in this Webform');
		}
		unset($this->allControls[$oldId]);
		$this->allControls[$newId] = $control;
	}

	/**
	 *
	 * @throws WebformException
	 */
	public function validate() {
		$e = new Errors();
		foreach ($this->areas as $area) {
			try {
				$area->validate();
			} catch (Errors $ex) {
				$e->addErrors($ex);
			}
		}

		foreach ($this->controls as $control) {
			try {
				$control->validate();
			} catch (Errors $ex) {
				$e->addErrors($ex);
			}
		}

		foreach ($this->validations as $validation) {
			if (!$validation->getStatement()) {
				$e->addError($validation->getMessage());
			}
		}

		if ($e->size()) {
			throw $e;
		}
	}
}
?>