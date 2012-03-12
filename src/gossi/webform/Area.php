<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

/**
 * Represents an area of the webform that can contain multiple controls.
 */
class Area extends Element implements IArea, IComposite {

	private static $area = 1;

	private $areas = array();
	private $controls = array();
	private $columns = null;
	private $parent;

	private $webform;

	/**
	 * Creates a new area.
	 * 
	 * @param IComposite $parent the parent container
	 * @param String $label a label for the area
	 */
	public function __construct(IComposite $parent, $config = array()) {
		parent::__construct($config);
		$this->config($config, array('columns'));
		
		Area::$area++;
		$this->parent = $parent;
		$this->webform = $parent->getWebform();
		
		
		if (is_null($this->id)) {
			$this->id = 'webform-area' . Area::$area;
		}
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
	
	/*
	 * (non-PHPdoc)
	 * @see \gossi\webform\IComposite::getColumns()
	 */
	public function getColumns() {
		return $this->columns;
	}

	/**
	 * Returns the receiver's controls
	 * 
	 * @return Control[]
	 */
	public function getControls() {
		return $this->controls;
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
	
	/**
	 * Sets a webform the receiver belongs to
	 * 
	 * @param Webform $webform
	 */
	public function setWebform(Webform $webform) {
		$this->webform = $webform;
	}

	/**
	 * Returns the receiver as XML.
	 * 
	 * @return DOMDocument
	 */
	public function toXML() {
		$xml = new \DOMDocument();
		$root = $xml->createElement('area');
		$root->setAttribute('id', $this->getId());
		$root->setAttribute('label', $this->getLabel());
		$root->setAttribute('description', $this->getDescription());
		$root->setAttribute('title', $this->getTitle());
		$root->setAttribute('classes', implode(' ', $this->getClasses()));

		if ($this->parent->getColumns() > 0) {
			$root->setAttribute('width', floor(100 / $this->parent->getColumns()) - 0.5);
		}
		
		if ($this->columns > 0) {
			$root->setAttribute('columns', $this->getColumns());
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

	/**
	 * Validates the receiver
	 * 
	 * @throws \gossi\webform\WebformErrors
	 */
	public function validate() {
		$e = new WebformErrors();
		foreach ($this->areas as $area) {
			try {
				$area->validate();
			} catch (WebformErrors $ex) {
				$e->addErrors($ex);
			}
		}

		foreach ($this->controls as $control) {
			try {
				$control->validate();
			} catch (WebformErrors $ex) {
				$e->addErrors($ex);
			}
		}

		if ($e->size()) {
			throw $e;
		}
	}
}
?>