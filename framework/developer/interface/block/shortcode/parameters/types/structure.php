<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Types_Structure
extends CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_List {
	
	/**
	* put your comment there...
	* 
	* @param mixed $string
	*/
	public function loadString($string) {
		// Convert CJT JSON Array and Object to JSON object.
		$params = $this->getTypeObject()->getParams();
		$string = str_replace(array('{A', 'A}'), array('[', ']'), $string);
		$values = json_decode($string, true);
		// Load string for all parameters that has value passed.
		foreach ($params as $name => $param) {
			if (isset($values[$name])) {
				$value = is_scalar($values[$name]) ? $values[$name] : json_encode($values[$name]);
				$param->loadString($value);
			}
		}
		return $this;
	}

} // End class.
