<?php
namespace gossi\webform;

class Parser {

	protected $root;
	protected $webform = null;

	public function __construct(DOMNode $node) {
		$this->root = $node;
	}

	public function parse(Webform $webform = null) {
		$name = $this->root->nodeName;
		$attribs = $this->root->attributes;

		if ($name != 'webform') {
			return null;
		}

		// associate webform or create a new one
		if ($webform !== null) {
			$this->webform = $webform;
		} else {
			$this->webform = $this->getWebform();
		}

		$this->webform->setMethod($attribs->getNamedItem('method')->value);
		$this->webform->setTarget($attribs->getNamedItem('target')->value);

		$this->parseChilds($this->root);

		return $this->webform;
	}

	public function parseNode(DOMNode $node, $parent = null) {
		if ($this->webform === null) {
			return;
		}

		$name = $node->nodeName;
		$attribs = $node->attributes;

		switch ($name) {
			case 'area':
				$label = $this->getText($attribs->getNamedItem('label')->value);
				$area = new Area($this->webform, $label);

				if ($attribs->getNamedItem('id')->value != null) {
					$area->setId($attribs->getNamedItem('id')->value);
				}

				if ($parent == null) {
					$this->webform->addArea($area);
				} else {
					$parent->addArea($area);
				}

				$this->parseChilds($node, $area);
				break;

			case 'control':
				$type = $attribs->getNamedItem('type')->value;
				$control = ControlFactory::createControl($type, $this->webform);
				$control->setLabel(($attrib = $attribs->getNamedItem('label')) !== null ? $this->getText($attrib->value) : '');
				$control->setName(($attrib = $attribs->getNamedItem('name')) !== null ? $attrib->value : '');
				$control->setDescription(($attrib = $attribs->getNamedItem('description')) !== null ? $this->getText($attrib->value) : '');
				$control->setDefault(($attrib = $attribs->getNamedItem('default')) !== null ? $this->getText($attrib->value) : '');
				$control->setTitle(($attrib = $attribs->getNamedItem('title')) !== null ? $this->getText($attrib->value) : '');
				$control->setRequired(($attrib = $attribs->getNamedItem('required')) !== null ? ($attrib->value == 'yes' ? true : false) : '');

				if ($attribs->getNamedItem('id')->value != null) {
					$control->setId($attribs->getNamedItem('id')->value);
				}

				/* Special cases */
				switch ($type) {
					case 'MultiLine':
						$control->setRows($attribs->getNamedItem('rows')->value);
						break;

					case 'Group':
						$control->setDirection($attribs->getNamedItem('direction')->value);
						$this->parseChilds($node, $control);
						break;

					case 'Radio':
					case 'CheckBox':
						$control->setChecked($attribs->getNamedItem('checked')->value == 'yes');
						break;

					case 'ComboBox':
						$options = $node->childNodes;
						for ($i = 0; $i < $options->length; $i++) {
							$option = $options->item($i);
							if ($option->nodeType == XML_ELEMENT_NODE) {
								$optionAttribs = $option->attributes;

								$label = $this->getText($optionAttribs->getNamedItem('label')->value);
								$value = $optionAttribs->getNamedItem('value')->value;
								$checked = $optionAttribs->getNamedItem('checked')->value == 'yes';

								$control->addOption($value, $label, $checked);
							}
						}
						break;
				}
				$parent->addControl($control);
				$this->parseValidator($node, $control);
				break;

		}
	}

	private function parseChilds(DOMNode $parentNode, $parent = null) {
		$childs = $parentNode->childNodes;
		if ($childs->length) {
			for ($i = 0; $i < $childs->length; $i++) {
				$this->parseNode($childs->item($i), $parent);
			}
		}
	}

	private function parseValidator(DOMNode $parentNode, Control $control) {
		$validators = $parentNode->getElementsByTagName('validator');
		for ($i = 0; $i < $validators->length; $i++) {
			$node = $validators->item($i);
			$type = $node->attributes->getNamedItem('type')->value;
			$validator = ValidatorFactory::createValidator($type);
			$validator->parse($node);
			$control->addValidator($validator);
		}
	}

	protected function getWebform() {
		return new Webform();
	}

	protected function getText($value) {
		return $value;
	}

}
?>