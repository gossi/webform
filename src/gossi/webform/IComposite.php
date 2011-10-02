<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

interface IComposite extends IWebform {
	public function addArea (Area $area);
	public function removeArea (Area $area);
}