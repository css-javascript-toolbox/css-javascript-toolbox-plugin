<?php
/**
* 
*/

/**
* 
* @version 6
*/
class CJTExceptionBase extends Exception {
	
	/**
	* put your comment there...
	* 
	*/
  public function __toString() {
  	return parent::__toString();
	}
	
} // End class.

class CJTPropertyNotFoundException extends CJTExceptionBase {
	
	/**
	* 
	*/
	const PROPERTY_NOT_FOUND_MESSAGE = 'Property not found';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $property_name = null;
	
	/**
	* put your comment there...
	* 
	*/
  public function __construct($name) {
  	parent::__construct(self::PROPERTY_NOT_FOUND_MESSAGE);
  	$this->property_name = $name;
	}
	
} // End class.