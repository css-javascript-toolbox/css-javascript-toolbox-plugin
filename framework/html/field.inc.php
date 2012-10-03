<?php
/**
* 
*/

/**
* 
*/
abstract class CJTHTMLField {
	
	 /**
	 * put your comment there...
	 *                                                      
	 * @var mixed
	 */
	protected $id;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $name;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $value;
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	* @return CJTStatesField
	*/
	public function __construct($name, $value, $id = null, $classesList = '') {
		// Initialize object.
		$this->name = $name;
		$this->value = $value;
		$this->id = $id ? $id : $this->name;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getId() {
		return $this->id;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		return $this->name;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getValue() {
		return $this->value;
	}
	
} // End class.
