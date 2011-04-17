<?php
namespace gossi\webform;

class ControlFactory {

	/**
	 *
	 * @return \net\keeko\utils\webform\Control
	 */
	public static function createControl($type, $form) {
		$className = $type;
		return new $className($form);
	}
}
?>