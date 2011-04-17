<?php
namespace gossi\webform;

class Area extends Element implements IArea, IComposite {

	private static $area = 1;

	private $areas = array();
	private $controls = array();

	private $webform;

	public function __construct(IComposite $parent, $label = '') {
		$this->webform = $parent->getWebform();
		$this->id = 'webform-area' . ++Area::$area;
		$this->label = $label;
		$parent->addArea($this);
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

	public function getControls() {
		return $this->controls;
	}

	public function getId() {
		return $this->id;
	}

	public function getWebform() {
		return $this->webform;
	}

	public function removeArea(Area $area) {
		if (array_key_exists($area->getId(), $this->areas)) {
			unset($this->areas[$area->getId()]);
		}
	}

	public function removeControl(Control $control) {
		$offset = array_search($control, $this->controls);
		if ($offset) {
			unset($this->controls[$offset]);
		}
	}

	public function setWebform(Webform $webform) {
		$this->webform = $webform;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function toXML() {
		$xml = new DOMDocument();
		$root = $xml->createElement('area');
		$root->setAttribute('id', $this->getId());
		$root->setAttribute('label', $this->getLabel());
		$root->setAttribute('description', $this->getDescription());
		$root->setAttribute('title', $this->getTitle());
		$root->setAttribute('classes', implode(' ', $this->getClasses()));

		foreach ($this->areas as $area) {
			$root->appendChild($xml->importNode($area->toXML()->documentElement, true));
		}

		foreach ($this->controls as $control) {
			$root->appendChild($xml->importNode($control->toXML()->documentElement, true));
		}

		$xml->appendChild($root);
		return $xml;
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

		if ($e->size()) {
			throw $e;
		}
	}
}
?>