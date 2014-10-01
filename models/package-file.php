<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTPackageFileModel extends CJTHookableClass {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $state = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $property
	*/
	public function getState($property) {
		return isset($this->state[$property]) ? $this->state[$property] : null;
	}

	/**
	* put your comment there...
	* 
	* @param CJTPackageFile
	*/
	public function install($package) {
		// Get packages definition xml factory.
		$factory = new CJT_Models_Package_Xml_Factory('models/package/xml/definition/package');
		// Process the full document starting by the ROOT package tag down to the latest descendant.
		$packageObject = $factory->create(null, 'package', $package->document()); // OBJECT CONSTR&UCTED HERE!
		// Share vars to nodes.
		$register = $packageObject->register();
		$register->offsetSet('packageParser', $package);
		// Process package object.
		$packageObject->transit()
		// Go down the tree.
									->processInners();
		// Return package Id
		return $register['packageId'];
	}

	/**
	* put your comment there...
	* 
	* @param mixed $packageFile
	*/
	public function parse($name, $file) {
		// Initialize.
		$package = FALSE;
		// Use a tmp dir with the same name as package without the extension.
		$packageFileComponent = explode('.', $name);
		$destinationDir = get_temp_dir() . $packageFileComponent[0];
		// Import WP_FileSystem and unzip_file functions required to unzip the file.
		require_once ABSPATH . 'wp-admin/includes/file.php';
		// Initialize Wordpress file systems.
		WP_Filesystem();
		// Extract package archive in temporary location.
		if (($result = unzip_file($file, $destinationDir)) === true) {
			// Import package definition class.
			cssJSToolbox::import('framework:packages:package.class.php');
			$package = new CJTPackageFile($destinationDir);
		}
		return $package;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $property
	* @param mixed $state
	*/
	public function setState($property, $state) {
		// Set State!
		$this->state[$property] = $state;
		// Chaining.
		return $this;
	}
} // End class.

// Hookable!
CJTPackageFileModel::define('CJTPackageFileModel', array('hookType' => CJTWordpressEvents::HOOK_FILTER));