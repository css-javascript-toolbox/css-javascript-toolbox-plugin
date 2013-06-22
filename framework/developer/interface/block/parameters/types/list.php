<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Parameters_Types_List
	extends CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_List {	

	/**
	* put your comment there...
	* 
	*/
	public function getValue() {
		// Get associative array!
		$assocArray = parent::getValue();
		// Get indexed array!
		return array_values($assocArray);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setValue($value) {
		// Start from the begining.
		reset($value);
		// Pass to child params.
		foreach ($this->params as $param) {
			// Get child parameter value.
			$paramValue = current($value);
			//  Set child parameter value.
			$param->setValue($paramValue);
			if (!$param->validate()) {
				echo cssJSToolbox::getText('Invalid structure parameter passed!');
			}
			// Move forward!
			next($value);
		}
		return $this;
	}

} // End class.
