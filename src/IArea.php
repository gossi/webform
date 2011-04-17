<?php
namespace gossi\webform;

interface IArea extends IWebform {
	public function addControl(Control $control);
	public function removeControl(Control $control);
}