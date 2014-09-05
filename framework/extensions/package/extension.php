<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Framework_Extensions_Package_Extension extends CJTHookableClass {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $extension;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $extensionClass;
	
	/**
	* put your comment there...
	* 
	* @param mixed $extension
	* @return CJT_Framework_Extensions_Package_Extension
	*/
	public function __construct($extension) {
		# Hold extension class
		$this->extensionClass = get_class($this);
		$this->extension =& $extension;
	}

	/**
	* Uninstaller for all Package Extension
	* 
	* @param mixed $method
	* @param mixed $params
	* @return CJTWordpressEvents
	*/
	public static function __callStatic($method, $params) {
		# Initialize
		$packageModel = CJTModel::getInstance('package');
		# Getting extension package class name
		$extensionClass = basename(str_replace('_', DIRECTORY_SEPARATOR, $method));
		# Extension state
		$extensionState = CJT_Framework_Extensions_Package_State_Extension::create($extensionClass);
		$extensionPackagesState = new CJT_Framework_Extensions_Package_State_Packages($extensionState->getExtensionDeDoc());
		# Delete extension packages
		foreach ($extensionPackagesState->getInstalledPackages() as $name => $iPack) {
			# Remove package
			$packageModel->delete($iPack['id']);
		}
		# Remove install information
		$extensionPackagesState->clearInstallInfo();
		$extensionState->clearInstallInfo();
	}

	/**
	* put your comment there...
	* 
	*/
	public function _checkInstallationState() {
		# Initialize
		$extensionClass = $this->extensionClass;
		$extension =& $this->extension;
		$extensionDeDoc =& $extension['defDoc'];
		$extensionState = new CJT_Framework_Extensions_Package_State_Extension($extensionDeDoc);
		# Upgrade and Install is the same both 
		# required checking the packages inside
		# Package-Extension is a only a wrapper for packages inside!
		if ($extensionState->getState() != CJT_Framework_Extensions_Package_State_Extension::INSTALLED) {
			# Initialize
			$packageFileModel = CJTModel::getInstance('package-file');
			$packageModel = CJTModel::getInstance('package');
			$extensionDir = ABSPATH . PLUGINDIR . DIRECTORY_SEPARATOR . $extension['dir'];
			$packagesFolderPath = $extensionDir  . DIRECTORY_SEPARATOR . ((string) $extensionDeDoc->packages->attributes()->folder);
			# Getting packages state
			$extensionPackagesState = new CJT_Framework_Extensions_Package_State_Packages($extensionDeDoc);
			# Check packages
			foreach ($extensionPackagesState as $pckName => $package) {
				# Get state
				$pckState = $extensionPackagesState->getState();
				# If not yet installed or requied upgrade
				if ($pckState != CJT_Framework_Extensions_Package_State_Packages::INSTALLED) {
					# Take action based on package installation state
					switch ($pckState) {
						case CJT_Framework_Extensions_Package_State_Packages::UPGRADE;
							# Delete package
							$packageModel->delete($package['id']);
						break;
					}
					# Get package file info.
					$packageFile = $packagesFolderPath . DIRECTORY_SEPARATOR . $package['file'];
					# Add/Create Package
					$packageCJTToken = $packageFileModel->parse(uniqid(), $packageFile);
					$packageId = $packageFileModel->install($packageCJTToken);
					# Mark as installed/created/added
					$extensionPackagesState->packageInstalled($packageId);
				}
				# Remove from queue
				$extensionPackagesState->removeOld();
			}
			# Packages to delete!
			foreach ($extensionPackagesState->getDeletedPackages() as $name => $deletedPack) {
				# Delete package
				$packageModel->delete($deletedPack['id']);
			}
			# Upgrade packages state
			$extensionPackagesState->upgrade();
			# Upgrade extension state
			$extensionState->upgrade($extension['defFile']);
			# Register uninstaller
			register_uninstall_hook($extension['pluginFile'], array(__CLASS__, "uninstall_{$extensionClass}"));	
		}
	}

	/**
	* put your comment there...
	* 
	*/
	public function _extensionDeactivated() {
		# Initialize
		$extension =& $this->extension;
		$statePackage = new CJT_Framework_Extensions_Package_State_Packages($extension['defDoc']);
		$extBlocks = new CJT_Framework_Extensions_Package_Blocks($statePackage);
		# Enable all Blocks associated with extension packages
		$extBlocks->setState(CJT_Framework_Extensions_Package_Blocks::INACTIVE);
	}

	/**
	* put your comment there...
	* 
	*/
	public function getInvolved() {
		# Check installation state 
		if (CJTPlugin::getInstance()->isInstalled()) {
			# INitialize
			$extensionClass = $this->extensionClass;
			$extension =& $this->extension;
			$extensionFile = ABSPATH . PLUGINDIR . DIRECTORY_SEPARATOR . $extension['dir'] . DIRECTORY_SEPARATOR . $extension['file'];
			# Load/install extensions packages hook
			add_action('init', array($this, '_checkInstallationState'));	
			# Deactivation hooks
			register_deactivation_hook($extensionFile, array($this, '_extensionDeactivated'));
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $class
	*/
	public static function getPluginExtensionClass($object) {
		return str_replace('_Plugin', '', get_class($object));
	}
	
} # End class

// Hiookable!
CJT_Framework_Extensions_Package_Extension::define('CJT_Framework_Extensions_Package_Extension', array('hookType' => CJTWordpressEvents::HOOK_FILTER));