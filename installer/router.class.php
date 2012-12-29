<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTInstallerRouter {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $installedDbVersion;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		$this->readInstalledVersion();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getInstalledDbVersion() {
		return $this->installedDbVersion;
	}
	
	/**
	* 
	*/
	public static function getInstance() {
		return new CJTInstallerRouter();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function readInstalledVersion() {
		$this->installedDbVersion = get_option(CJTPlugin::DB_VERSION_OPTION_NAME);
	}
	
	/**
	* put your comment there...
	* 	
	*/
	public function route() {
		// As long as the stored DB version is not our 
		if ($this->installedDbVersion != CJTPlugin::DB_VERSION) {
			require 'installer/installer.class.php';
			$installer = CJTInstaller::getInstance()
															->install();
			// Upgrade if there is another version installed!
			if ($this->installedDbVersion != '') {
				// Find upgrader!
				$upgraderPath = "upgrade/{$this->installedDbVersion}/upgrade.class.php";
				if (!file_exists(dirname(__FILE__) . "/{$upgraderPath}")) {
					throw new Exception("Could not upgrade CJT DB Version {$this->installedDbVersion}!! Upgrader not found!!");
				}
				require $upgraderPath;
				// find upgrader class .
				$version = str_replace('.', '', $this->installedDbVersion);
				$upgraderClass = "CJTV{$version}Upgrade";
				// Upgraing!
				$upgrader = call_user_func(array($upgraderClass, 'getInstance'))
															 ->upgrade();
			}
			else { // Install sample data only witgh fresh installation!
				$installer->installDBSamples();
			}
			// Upgrade DB Version!
			$this->writeInstallatedVersion();
		}
		return true;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function writeInstallatedVersion() {
		update_option(CJTPlugin::DB_VERSION_OPTION_NAME, CJTPlugin::DB_VERSION);
	}
	
} // End class.
