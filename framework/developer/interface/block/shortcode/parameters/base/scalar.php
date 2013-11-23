<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_Scalar
extends CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_Abstract {

	/**
	* put your comment there...
	* 
	*/
	protected abstract function getShortcodeValue();
	
	/**
	* put your comment there...
	* 
	* @param mixed $string
	*/
	public function loadString($string) {
		return $this->setValue($string);
	}

	/**
	* put your comment there...
	* 
	*/
	public function shortcode()	{
		// Initialize.
		$shortcode = '';
		$param = $this->getTypeObject()->getDefinition();
		// Concat all.
		$shortcode = $param->getName() . '=' . $this->getShortcodeValue();
		// Returns!
		return $shortcode;
	}

} // End class.