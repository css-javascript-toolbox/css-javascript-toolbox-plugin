<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTInstallerModel {
	
	/**
	* 
	*/
	const OPERATION_STATE_INSTALLED = 'installed';
	
	/**
	* 
	*/
	const INSTALLATION_STATE = 'state.CJTInstallerModel.operations';
	
	/**
	* 
	*/
	const NOTICED_DISMISSED_OPTION_NAME = 'settings.CJTInstallerModel.noticeDismissed';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $input;
	
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
		$this->installedDbVersion = get_option(CJTPlugin::DB_VERSION_OPTION_NAME);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $dismiss
	*/
	public function dismissNotice($dismiss = null) {
		// Read current value!
		$currentValue = get_user_option(self::NOTICED_DISMISSED_OPTION_NAME);
		if ($dismiss !== null) {
			// Dismiss can' be reverted!
			update_user_option(get_current_user_id(), self::NOTICED_DISMISSED_OPTION_NAME, true);
		}
		return $currentValue;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getInstalledDbVersion() {
		return $this->installedDbVersion;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getInternalVersionName() {
		return str_replace(array('.', '-'), '', $this->installedDbVersion);
	}

	/**
	* Get installer operations for current CJT version!
	* 
	* @return array Operations list metadata.
	*/
	public function getOperations() {
		// Read all install/upgrade operations for all versions!
		$operations = get_option(self::INSTALLATION_STATE);
		$operations = is_array($operations) ? $operations : array();
		// Check if cached: Use only installer cache for 'current' CJT version.
		if (!isset($operations[CJTPlugin::DB_VERSION])) {
			// Import installer reflection!
			cssJSToolbox::import('framework:installer:reflection.class.php');
			// Get Installer operations.
			cssJSToolbox::import('includes:installer:installer:installer.class.php');
			$operations[CJTPlugin::DB_VERSION]['operations']['install'] = CJTInstallerReflection::getInstance('CJTInstaller', 'CJTInstaller')->getOperations();
			if ($this->isUpgrade()) {
				// Get upgrade operations , Also cache upgrader info for later use!
				$operations[CJTPlugin::DB_VERSION]['upgrader'] = $upgrader = $this->getUpgrader();
				// Check if upgrader exists!
				if (!file_exists(cssJSToolbox::resolvePath($upgrader['file']))) {
					throw new Exception("Could not find upgrade/downgrade agent for installer '{$this->installedDbVersion}'! Incompatible version numbers! Upgrader/Downgrwader is no being supported by current versions!!");
				}
				// Import upgrader + reflect its operations!
				cssJSToolbox::import($upgrader['file']);
				$operations[CJTPlugin::DB_VERSION]['operations']['upgrade'] = CJTInstallerReflection::getInstance($upgrader['class'], 'CJTUpgradeNonTabledVersions')->getOperations();				
			}
			update_option(self::INSTALLATION_STATE, $operations);				
		}
		return $operations[CJTPlugin::DB_VERSION];
	}

	/**
	* put your comment there...
	* 
	*/
	public function getUpgrader() {
		// Upgrader file.
		$upgrader['file'] = "includes:installer:upgrade:{$this->getInstalledDbVersion()}:upgrade.class.php";
		// Upgrader class name.
		$upgrader['class'] = "CJTV{$this->getInternalVersionName()}Upgrade";
		return $upgrader;
	}
	
	/**
	* Allow executing of a single installation operation!
	* Both Install and Upgrade operations can be executed throught here
	* 
	* 
	* @return void
	*/
	public function install() {
		// Initialize.
		$result = FALSE;
		// Read input!
		$rOperation = $this->input['operation'];
		$type = $rOperation['type'];
		// Get allowed operations with their state!
		$operations = (array) get_option(self::INSTALLATION_STATE);
		$vOperations =& $operations[CJTPlugin::DB_VERSION];
		// Invalid operation!
		if (!isset($vOperations['operations'][$type][$rOperation['name']])) {
			throw new Exception('Invalid operation');
		}
		else {
			// Install only if not installed!
			$operation =& $vOperations['operations'][$type][$rOperation['name']];
			if ((!isset($operation['state'])) || ($operation['state'] != self::OPERATION_STATE_INSTALLED)) {
				// Import installer and get installer object!
				switch ($type) {
					case 'install':
						cssJSToolbox::import('includes:installer:installer:installer.class.php');
						$installer = CJTInstaller::getInstance();
					break;
					case 'upgrade':
						$upgrader = $vOperations['upgrader'];
						cssJSToolbox::import($upgrader['file']);
						$installer = new $upgrader['class']();
					break;
				}
				// Execute the requested operation, save state only when succesed!
				if ($installer->{$rOperation['name']}()) {
					$operation['state'] = self::OPERATION_STATE_INSTALLED;
					// Update operations cache to reflect the new state!
					update_option(self::INSTALLATION_STATE, $operations);
					// Say OK!
					$result = array('state' => self::OPERATION_STATE_INSTALLED);
				}
			}
		}
		return $result;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function isCommonRelease() {
		// Check weather the current installed version is in the same
		// release with the running version.
		// Get instaleled version components.
		$ivComponent = explode('.', str_replace('-', '.', $this->getInstalledDbVersion()));
		$nvComponent = explode('.', str_replace('-', '.', CJTPlugin::DB_VERSION));
		// Use PE-Edition sign if not sign used!
		if (!isset($ivComponent[2])) {
			$ivComponent[2] = 'PE';
		}
		if (!isset($nvComponent[2])) {
			$nvComponent[2] = 'PE';
		}
		// TRUE if same, FALSE otherwise.
		return (($ivComponent[0] == $nvComponent[0]) && ($ivComponent[2] == $nvComponent[2]));
	}

	/**
	* put your comment there...
	* 
	*/
	public function isUpgrade() {
		// If the version is not the same and not equal to current version then its upgrading!
		$isUpgrade = (($this->installedDbVersion != CJTPlugin::DB_VERSION) && ($this->installedDbVersion != ''));
		return $isUpgrade;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $input
	*/
	public function setInput(& $input) {
		$this->input = $input;
		return $this; // Chaining!
	}
	
} // End class.
