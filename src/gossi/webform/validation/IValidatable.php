<?php
namespace gossi\webform\validation;

interface IValidatable {
	public function addTest(AbstractTest $test);
	public function removeTest(AbstractTest $test);
	public function addError($message);
	public function hasErrors();
	public function getErrors();
}