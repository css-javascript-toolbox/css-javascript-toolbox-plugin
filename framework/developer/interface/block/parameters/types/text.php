<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Parameters_Types_Text
extends CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_Scalar {

	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setValue($value) {
		// Cast to double!
		$this->value = (string) $value;
		// Chaining.
		return $this;
	}

} // End class.
