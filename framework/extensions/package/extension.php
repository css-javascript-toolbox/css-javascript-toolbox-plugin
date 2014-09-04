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
	*/
	public function _checkInstallationState() {
		# Initialize
		$extensionClass = get_class($this);
		$extensions =& CJTPlugin::getInstance()->extensions()->getExtensions();
		$extension =& $extensions[$extensionClass];
		$extensionDeDoc =& $extension['defDoc'];
		$extensionState = new CJT_Framework_Extensions_Package_State_Extension($extensionDeDoc);
		# Upgrade and Install is the same both 
		# required checking the packages inside
		# Package-Extension is a only a wrapper for packages inside!
		if ($extensionState->getState() != CJT_Framework_Extensions_Package_State_Extension::INSTALLED) {
			# Initialize
			$packageFileModel = CJTModel::getInstance('package-file');
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
							
						break;
					}
					# Get package file info.
					$packageFile = $packagesFolderPath . DIRECTORY_SEPARATOR . $package['file'];
					# Add/Create Package
					$packageCJTToken = $packageFileModel->parse(uniqid(), $packageFile);
					$packageId = $packageFileModel->install($packageCJTToken);
				}
				# Remove from queue
				$extensionPackagesState->removeOld();
			}
			# Upgrade packages state
			$extensionPackagesState->upgrade();
			# Upgrade extension state
			$extensionState->upgrade();
		}
	}

	/**
	* put your comment there...
	* 
	*/
	public function getInvolved() {
		# Check installation state 
		add_action('admin_menu', array($this, '_checkInstallationState'), 100);
	}	
	
} # End class

// Hiookable!
CJT_Framework_Extensions_Package_Extension::define('CJT_Framework_Extensions_Package_Extension', array('hookType' => CJTWordpressEvents::HOOK_FILTER));