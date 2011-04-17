<?php
namespace gossi\webform;

class ValidatorFactory {

	/**
	 *
	 * @return
	 */
	public static function createValidator($type) {
		$className = $type.'Validator';

		return new $className();
	}
}
?>
