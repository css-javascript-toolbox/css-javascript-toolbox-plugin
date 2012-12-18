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
	* @param mixed $name
	*/
	public function getDefinition($name);
	
	/**
	* put your comment there...
	* 
	* @param mixed $observer
	*/
	public static function getInstance($name, $target, $definition, $includes);
	
	/**
	* put your comment there...
	* 
	*/
	public function getTarget();
	
	/**
	* put your comment there...
	* 
	*/
	public function trigger();
	
} // End interface.
