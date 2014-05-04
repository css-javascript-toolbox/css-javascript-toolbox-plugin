<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTV10CEUpgrade  {
	
	/**
	* 
	*/
	const ACCESS_POINTS_CACHE_POINTER = 'settings.CJTAccessPointsDirectorySpider.cache';
	
	/**
	* put your comment there...
	* 
	*/
	public function database() {
		// Upgrade database tables.
		cssJSToolbox::import('framework:installer:dbfile.class.php');
		CJTDBFileInstaller::getInstance(cssJSToolbox::resolvePath('includes:installer:upgrade:1.0-CE:db:mysql:structure.sql'))->exec();
		// Chaining.
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @return CJTV10CEUpgrade Return $this
	*/
	public function finalize() {
		// Temporary for a period of time we need to clean up
		// users database as the access points cache wordpress option
		// will not be used any more!
		delete_option(self::ACCESS_POINTS_CACHE_POINTER);
		// Delete all install operations for versions other than 'current' version!
		CJTModel::import('installer');
		$operationsState = get_option(CJTInstallerModel::INSTALLATION_STATE);
		// Remove all and leave only 'current' versions operations!
		update_option(CJTInstallerModel::INSTALLATION_STATE, array(CJTPlugin::DB_VERSION => $operationsState[CJTPlugin::DB_VERSION]));
		// Chaining!
		return $this;
	}
	
} // End class.