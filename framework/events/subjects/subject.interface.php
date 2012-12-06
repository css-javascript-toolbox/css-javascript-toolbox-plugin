<?php
/**
* 
*/

/**
* 
*/
interface CJTEEISubject {
	
	/**
	* put your comment there...
	* 
	*/
	public function callIndirect($params);
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getDefinition($name);
	
	/**
	* put your comment there...
	* 
	* @param mixed $observer
	*/
	public static function getInstance($definition, $includes);
	
	/**
	* put your comment there...
	* 
	*/
	public function trigger();
	
} // End interface.
