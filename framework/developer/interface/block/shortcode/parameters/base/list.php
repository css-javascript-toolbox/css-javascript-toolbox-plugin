<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_List
extends CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_Abstract {
	/**
	* put your comment there...
	* 
	*/
	public function shortcode() {
		// Get value!
		$value = $this->getValue();
		$name = $this->getDefinition()->getName();
		// Return json!
		return "{$name}='" . json_encode($value) . "'";
	}

	/**
	* put your comment there...
	* 
	*/
	public function getParams() {
		return $this->getTypeObject()->getParams();
	}
	
} // End class.