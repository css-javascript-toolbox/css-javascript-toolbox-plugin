<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Parameters_Types_Structure
	extends CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_List {

	/**
	* put your comment there...
	* 
	*/
	public function getValue($useRealNames = null) {
		// Get associative array!
		$assocArray = parent::getValue($useRealNames);
		// Cast to stdClass object!
		return (object) $assocArray;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setValue($value) {
		// Pass to child params.
		foreach ($this->params as $name => $param) {
			// Get child parameter value.
			$paramValue = isset($value[$name]) ? $value[$name] : null;
			//  Set child parameter value.
			$param->setValue($paramValue);
			if (!$param->validate()) {
				echo cssJSToolbox::getText('Invalid structure parameter passed!');
			}
		}
		return $this;
	}

} // End class.
