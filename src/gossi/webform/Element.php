<?php
/**
 * @package gossi\webform
 */
namespace gossi\webform;

class Element extends BaseElement {

	/**
	 * @var String
	 */
	protected $description;

	/**
	 * @var String
	 */
	protected $title;

	/**
	 * Returns the description
	 * 
	 * @return String the description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Returns the title
	 * 
	 * @return String the title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets a description text
	 *
	 * @param String $description the description text
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * Sets a title for the &lt;label&gt; tag
	 *
	 * @param String $title the title text
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}
}
?>