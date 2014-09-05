<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_Version_Version {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $version;
	
	/**
	* put your comment there...
	* 
	* @param mixed $version
	* @return CJT_Framework_Version_Version
	*/
	public function __construct($version) {
		// Get version components.
		$this->version = explode('.', $version);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getMajor() {
		return (int) $this->version[0];
	}

} // End class.
