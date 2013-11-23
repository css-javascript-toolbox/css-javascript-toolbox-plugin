<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Types_Number
extends CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_Scalar {
	
	/**
	* put your comment there...
	* 
	*/
	protected function getShortcodeValue() {
		return $this->getValue();
	}

} // End class.
