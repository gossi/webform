<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class ControlFactory {

	/**
	 *
	 * @return \gossi\webform\Control
	 */
	public static function createControl($type, $parent) {
		$className = $type;
		return new $className($parent);
	}
}
?>