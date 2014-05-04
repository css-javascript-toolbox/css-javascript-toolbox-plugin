<?php
/**
* 
*/

/**
* 
*/
interface CJT_Framework_Developer_Interface_Block_Parameters_Types_Interface_Type {

	/**
	* put your comment there...
	* 
	*/
	public function getValue($useRealNames = null);
	
	/**
	* put your comment there...
	* 
	* @param mixed $parent
	*/
	public function setParent($parent);
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setValue($value);
	
	/**
	* put your comment there...
	* 
	*/
	public function validate();
	
} // End class.