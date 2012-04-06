<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

use gossi\webform\validation\AbstractTest;
use gossi\webform\validation\IValidatable;

/**
 * THE Webform.
 */
class Webform extends BaseElement implements IArea, IComposite, IValidatable {

	const POST = 'post';
	const GET = 'get';

	const DESC_LABEL = 'desc-label';
	const DESC_BETWEEN = 'desc-between';
	const DESC_END = 'desc-end';
	
	const LAYOUT_TABLE = 'table';
	const LAYOUT_VERTICAL = 'vertical';
	
	private static $webforms = 1;

	private $target;
	private $method = Webform::POST;
	private $desc = Webform::DESC_LABEL;
	private $areas = array();
	private $language = null;
	private $layout;
	private $submitted;
	private $i18n = null;
	private $i18nFile = null;
	private $errors = null;
	private $allControls = array();
	private $columns = null;
	private $controls = array();
	private $tests = array();

	protected $template = null;

	public function __construct($config = array()) {
		parent::__construct($config);
		$this->config($config, array('language', 'columns', 'target', 'method'));

		Webform::$webforms++;

		if (is_null($this->language)) {
			$this->language = 'en';
			$this->loadLanguage();
		}

		if (is_null($this->id)) {
			$this->id = 'webform-' . Webform::$webforms;
		}
		
		$this->submitted = new Hidden($this);
	}

	public function addArea(Area $area) {
		if (!array_key_exists($area->getId(), $this->areas)) {
			$this->areas[$area->getId()] = $area;
		}
		return $this;
	}

	public function addControl(Control $control) {
		if (!in_array($control, $this->controls)) {
			$this->controls[] = $control;
		}
	}	
	
	public function addError($message) {
		$this->errors[] = $message;
	}

	public function addTest(AbstractTest $test) {
		if (!in_array($test, $this->tests)) {
			$this->tests[] = $test;
		}
		return $this;
	}

	public function getArea($id) {
		if (array_key_exists($id, $this->areas)) {
			return $this->areas[$id];
		}
		return null;
	}
	
	/*
	 * (non-PHPdoc)
	 * @see \gossi\webform\IComposite::getColumns()
	 */
	public function getColumns() {
		return $this->columns;
	}

	public function getControl($id) {
		if (array_key_exists($id, $this->allControls)) {
			return $this->allControls[$id];
		}
		return null;
	}
	
	public function getErrors() {
		return $this->errors;
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
			$this->i18nFile = sprintf('%s/i18n/%s.xml', __DIR__, $this->language);
		}
		return $this->i18nFile;
	}
	
	public function getLanguage() {
		return $this->language;
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

	public function hasErrors() {
		return count($this->errors);
	}
	
	public function isSubmitted() {
		return !is_null($this->submitted->getRequestValue());
	}
	
	public function isValid() {
		if ($this->isSubmitted()) {
			try {
				$this->validate();
				return true;
			} catch(WebformErrors $e) {
				return false;
			}
		}
		return false;
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
			throw new \Exception('Control with given id ('+$id+') already exists in this Webform');
		}
		$this->allControls[$id] = $control;
	}
	
	public function removeArea(Area $area) {
		if ($offset = array_search($area, $this->areas)) {
			unset($this->areas[$offset]);
		}
		return $this;
	}

	public function removeControl(Control $control) {
		if ($offset = array_search($control, $this->controls)) {
			unset($this->controls[$offset]);
		}
		return $this;
	}	

	public function removeTest(AbstractTest $test) {
		if ($offset = array_search($test, $this->tests)) {
			unset($this->tests[$offset]);
		}
		return $this;
	}
	
	/*
	 * (non-PHPdoc)
	 * @see \gossi\webform\IComposite::setColumns()
	 */
	public function setColumns($columns) {
	
		// enable column layout
		if (is_null($this->columns) && !is_null($columns)) {
			$this->addClass('webform-layout-column');
		}
	
		// disable column layout
		if (!is_null($this->columns) && is_null($columns)) {
			$this->removeClass('webform-layout-column');
		}
	
		$this->columns = $columns;
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
	
	public function setLanguage($language) {
		$this->language = $language;
		return $this;
	}

	public function setLayout($layout) {
		$this->removeClasses(array('webform-layout-table', 'webform-layout-vertical'));
		$this->addClass('webform-layout-' . $layout);
		$this->layout = $layout;
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

	public function toHTML() {
		$stylesheet = new \DOMDocument();
		$stylesheet->load($this->getTemplate('html'));
	
		$processor = new \XSLTProcessor();
		$processor->importStyleSheet($stylesheet);
		return preg_replace('#xmlns="([^"]+)"#i', '', $processor->transformToXML($this->toXML()));
	}
	
	public function toXML() {
		$xml = new \DOMDocument();
		$root = $xml->createElement('webform');
		$root->setAttribute('id', $this->id);
		$root->setAttribute('target', $this->target);
		$root->setAttribute('method', $this->method);
		$root->setAttribute('description-position', $this->desc);
		$root->setAttribute('classes', implode(' ', $this->classes));

		if ($this->hasErrors()) {
			$errs = $xml->createElement('errors');
			$errs->setAttribute('occur', $this->getI18n('/webform/error/occur'));
			foreach ($this->getErrors() as $error) {
				$err = $xml->createElement('error', $error);
				$errs->appendChild($err);
			}
			$root->appendChild($errs);
		}

		foreach ($this->areas as $area) {
			$root->appendChild($xml->importNode($area->toXML()->documentElement, true));
		}

		foreach ($this->controls as $control) {
			$root->appendChild($xml->importNode($control->toXML()->documentElement, true));
		}
		
		foreach ($this->tests as $test) {
			$root->appendChild($xml->importNode($test->toXML()->documentElement, true));
		}

		$xml->appendChild($root);
		return $xml;
	}

	public function unregisterControl($id) {
		if (array_key_exists($id, $this->allControls)) {
			unset($this->allControls[$id]);
		}
	}

	/**
	 *
	 * @throws \gossi\webform\WebformErrors
	 */
	public function validate() {
		$this->errors = array();
		foreach ($this->areas as $area) {
			try {
				$area->validate();
			} catch (WebformErrors $ex) {
				$this->errors = array_merge($this->errors, $ex->getErrors());
			}
		}

		foreach ($this->controls as $control) {
			try {
				$control->validate();
			} catch (WebformErrors $ex) {
				$this->errors = array_merge($this->errors, $ex->getErrors());
			}
		}

		foreach ($this->tests as $test) {
			try {
				$test->validate();
			} catch (\Exception $e) {
				$this->addError($e->getMessage());
			}
		}

		if ($this->hasErrors()) {
			$e = new WebformErrors();
			$e->addErrors($this->errors);
			throw $e;
		}
	}
}
?>