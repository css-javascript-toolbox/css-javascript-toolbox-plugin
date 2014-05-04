<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Objects_Block
extends CJT_Models_Package_Xml_Definition_Abstract {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $rules = array('links' => false);

	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		// Do transit.
		parent::transit();
		// Use blockId key instead of 'id' key.
		$register = $this->register();
		$register['blockId'] = $register['id'];
		unset($register['id']);
		// Chaining.
		return $this;
	}
} // End class