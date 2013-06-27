<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Types_List
extends CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_List {	

	/**
	* put your comment there...
	* 
	* @param mixed $string
	*/
	public function loadString($string) {
		// Initialize.
		$indexToFetch = 0;
		$params = $this->getTypeObject()->getParams();
		// Convert CJT JSON Array and Object to JSON object.
		$string = str_replace(array('{A', 'A}'), array('[', ']'), $string);
		$values = json_decode($string, true);
		// Load string for all parameters that has value passed.
		foreach ($params as $param) {
			$key = (string) $indexToFetch;
			if (isset($values[$key])) {
				$value = is_scalar($values[$key]) ? $values[$key] : json_encode($values[$key]);
				$param->loadString($value);
			}
			// Move to next index.
			$indexToFetch++;
		}
		return $this;
	}

} // End class.
