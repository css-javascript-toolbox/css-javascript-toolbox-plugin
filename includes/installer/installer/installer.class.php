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
	public function database() {
		// Install Database structure!
		cssJSToolbox::import('framework:installer:dbfile.class.php');
		CJTDBFileInstaller::getInstance(cssJSToolbox::resolvePath('includes:installer:installer:db:mysql:structure.sql'))->exec();
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function finalize() {
		// Update version number.
		update_option(CJTPlugin::DB_VERSION_OPTION_NAME, CJTPlugin::DB_VERSION);
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getInstance() {
		return new CJTInstaller();
	}

} // End class.