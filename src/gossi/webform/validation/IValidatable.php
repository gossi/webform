<?php
namespace gossi\webform\validation;

interface IValidatable {
	public function addTest(Test $test);
	public function removeTest(Test $test);
	public function addError($message);
	public function hasErrors();
	public function getErrors();
}