<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_Scalar
	extends CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_Abstract {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $value = null;

	/**
	* put your comment there...
	* 
	*/
	public function getValue($useRealNames = null) {
		return $this->value;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function setDefault() {
		return $this->setValue($this->getDefinition()->getDefaultValue());
	}

} // End class.
