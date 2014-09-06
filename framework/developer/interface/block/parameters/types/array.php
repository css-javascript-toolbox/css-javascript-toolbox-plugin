<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Parameters_Types_Array extends
CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_Scalar {
	
	/**
	* put your comment there...
	* 
	*/
	public function getValue($useRealNames = null) {
		// Initialize.
		$value = array();
		// Get final value!
		foreach ($this->getValues() as $param) {
			$value[] = $param->getValue();
		}
		return $value;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getValues() {
		return $this->value;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function setDefault() {
		// First decode JSON to PHP array and then set the default.
		return $this->setValue(json_decode($this->getDefinition()->getDefaultValue()));
	}

	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setValue($values) {
		// E_ALL complain!
		if (!$values) {
			$values = array();
		}
		// Initialize empty array.
		$this->value = array();
		// Get prototype parameter.
		$parameters = $this->getDefinition()->getChilds();
		// Prototype parameter defined as the first parameter.
		$prototype = reset($parameters);
		foreach ($values as $value) {
			// Create parameter prototype.
			$param = $this->value[] = $this->getFactory()->create($prototype->getType(), $prototype);
			// Set the value.
			$param->setValue($value);
			// Set parent.
			$param->setParent($this);
		}
		// Chaining.
		return $this;
	}
	
} // End class.
