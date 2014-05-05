<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTV15Upgrade {
	
	/**
	* put your comment there...
	* 
	*/
	public function database() {
		// This operation runs only if required.
		// @see 15 view operations file.
		cssJSToolbox::import('framework:installer:dbfile.class.php');
		CJTDBFileInstaller::getInstance(cssJSToolbox::resolvePath('includes:installer:upgrade:1.1-CE:db:mysql:structure.sql'))->exec();
		// Chaining.
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function finalize() {
		// Upgrade database internal version number using
		// installer class.
		cssJSToolbox::import('includes:installer:installer:installer.class.php');
		CJTInstaller::getInstance()->finalize();
		// Chaining.
		return $this;
	}

} // End class.