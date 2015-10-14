<?php
/**
* 
*/

/**
* 
*/
class CJTServicesStorage {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $storageVarName;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $value;

	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $default
	*/
	public function __construct($name, $default = null) {
		# Storage Wordpress option name
		$this->storageVarName = strtolower( $name );
		# Read
		$this->value = get_option( $this->storageVarName, $default );
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getValue() {
		return $this->value;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function & setValue($value) {
		# Set value
		$this->value =& $value;
		# chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & update() {
		# Update DB Value
		update_option( $this->storageVarName, $this->value );
		# Chain
		return $this;
	}
	
}