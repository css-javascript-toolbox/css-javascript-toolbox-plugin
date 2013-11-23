<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Types_Raw
extends CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_Scalar {

	/**
	* put your comment there...
	* 
	*/
	public function getShortcodeValue() {
		return $this->getValue();
	}
	
} // End class.
