<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTInstaller extends CJTHookableClass {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize hookable!
		parent::__construct();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getInstance() {
		return new CJTInstaller();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function install() {
		// Install DB structure.
		$this->installDB();
		// Chaining!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function installDB() {
		$driver = cssJSToolbox::getInstance()->getDBDriver();
		$dbStruct = file_get_contents('db/mysql/structure.sql');
		// Execute db creation query!
		$driver->exec($driver);
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function installDBSamples() {
		
		return $this;	
	}
	
} // End class.