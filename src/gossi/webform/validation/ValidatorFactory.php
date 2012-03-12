<?php
namespace gossi\webform\validation;

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
