<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class ControlFactory {

	/**
	 * Creates a control
	 * 
	 * @param String $type the new type of the control
	 * @param \gossi\webform\IArea $parent
	 * @return \gossi\webform\Control
	 */
	public static function createControl($type, $parent) {
		$className = $type;
		return new $className($parent);
	}
}
?>