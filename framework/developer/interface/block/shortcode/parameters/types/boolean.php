<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Types_Boolean 
extends CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_Scalar {
	
	/**
	* put your comment there...
	* 
	*/
	protected function getShortcodeValue() {
		// Read value.
		$value = $this->getValue();
		// Return Shortcode parameter value.
		return  $value ? 'true' : 'false';
	}

} // End class.
