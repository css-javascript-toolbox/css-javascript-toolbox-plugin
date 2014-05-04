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
		// Clean up file system!
		$this->fileSystem();
		// Chaining!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function fileSystem() {
		global $wp_filesystem;
		// Getting directory list!
		$wpContentDir = 'wp-content';
		$fSConfig = cssJSToolbox::$config->fileSystem;
		// Directories to create!
		$directories = array(
			"{$wpContentDir}/{$fSConfig->contentDir}",
			"{$wpContentDir}/{$fSConfig->contentDir}/{$fSConfig->templatesDir}",
		);
		// Delete all directories!
		foreach ($directories as $dir) {
			$wp_filesystem->delete(ABSPATH . "/{$dir}", true);
		}
		// Chaining!
		return $this;
	}
	
} // End class.