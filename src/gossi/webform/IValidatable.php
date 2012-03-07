<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

interface IValidatable {
	/**
	 * muh muh
	 * @param Validation $validation
	 */
	public function addValidation(Validation $validation);
	public function addTest($statement, $message);
	public function removeValidation(Validation $validation);
}