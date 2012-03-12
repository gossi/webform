<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

interface IComposite extends IWebform {
	public function addArea (Area $area);
	public function removeArea (Area $area);
	
	/**
	 * Enables the column layout. Defines how the children <code>Area</code>s are
	 * layed out.
	 * 
	 * The following values are allowed: <ul>
	 * <li><code>null</code> - disables the column layout</li>
	 * <li><code>0</code> - sets endless columns</li>
	 * <li><code>&gt; 0</code> - defines the number of columns</li>
	 * </ul>
	 * 
	 * @param mixed $columns
	 */
	public function setColumns($columns);
	
	/**
	 * Returns the number of columns.
	 */
	public function getColumns();
}