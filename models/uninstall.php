<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* Provide uninstall functnios
* required for cleaning up the installation
* process!
* 
* @author CJT-Team
*/
class CJTUninstallModel {
	
	/**
	* put your comment there...
	* 
	*/
	public function database() {
		// Import dependencies.
		cssJsToolbox::import('framework:installer:dbfile.class.php');
		// Load Uninstallation SQL Statements!
		CJTDBFileInstaller::getInstance(cssJsToolbox::resolvePath('models:uninstall:db:mysql:uninstall.sql'))
		// Execute All,
		->exec();
		// Chaining!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function expressUninstall() {
		// Clean up database
		$this->database();
		// Chaining!
		return $this;
	}
	
} // End class.