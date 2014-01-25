<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_Wordpress_Currentversion {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $version;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Read current version.
		$this->version = get_bloginfo('version');
	}

	/**
	* put your comment there...
	* 
	*/
	public function getVersion() {
		return $this->version;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $version
	*/
	public function isLess($version) {
		return (version_compare($this->getVersion(), $version) == -1);
	}

} // End class.
