<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Types_Array
extends CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_Scalar {	

	/**
	* put your comment there...
	* 
	* @param mixed $string
	* @return CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_Scalar
	*/
	public function loadString($string) {
		// Convert CJT JSON Array and Object to JSON object.
		$string = str_replace(array('{A', 'A}'), array('[', ']'), $string);
		// Get PHP var for JSON text!
		return $this->setValue(json_decode($string, true));
	}

	/**
	* put your comment there...
	* 
	*/
	protected function getShortcodeValue() {
		return "'" . json_encode($this->getValue()) . "'";
	}

	/**
	* put your comment there...
	* 
	*/
	public function getValues() {
		return $this->getTypeObject()->getValues();
	}

} // End class.
