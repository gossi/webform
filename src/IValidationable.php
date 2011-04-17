<?php
namespace gossi\webform;

interface IValidationable {
	public function addValidation(Validation $validation);
	public function assertTrue($statement, $message);
	public function removeValidation(Validation $validation);
}