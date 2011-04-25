<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

interface IValidatable {
	public function addValidation(Validation $validation);
	public function assertTrue($statement, $message);
	public function removeValidation(Validation $validation);
}