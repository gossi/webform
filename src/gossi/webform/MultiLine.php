<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class MultiLine extends Control {

	private $rows = 3;

	public function setRows($rows) {
		$this->rows = $rows;
	}

	public function toXml() {
		$xml = $this->createXml('MultiLine');
		$xml->documentElement->setAttribute('rows', $this->rows);

		return $xml;
	}
}
?>