<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_Extensions_Package_Activator {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $extensionPluginObject;
	
	/**
	* put your comment there...
	* 
	* @param mixed $extensionPluginObject
	* @return CJT_Framework_Extensions_Package_Activator
	*/
	public function __construct(& $extensionPluginObject) {
		# getting class name
		$this->extensionPluginObject = $extensionPluginObject;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & activate() {
		# Getting extension class
		$extensionClass = CJT_Framework_Extensions_Package_Extension::getPluginExtensionClass($this->extensionPluginObject);
		# No activation process until the Plugin is deactivated before
		# It must be installed first.
		if (CJT_Framework_Extensions_Package_State_Extension::isInstalled($extensionClass)) {
			# Initialize
			$stateExtension = CJT_Framework_Extensions_Package_State_Extension::create($extensionClass);
			$statePackage = new CJT_Framework_Extensions_Package_State_Packages($stateExtension->getExtensionDeDoc());
			$extBlocks = new CJT_Framework_Extensions_Package_Blocks($statePackage);
			# Enable all Blocks associated with extension packages
			$extBlocks->setState(CJT_Framework_Extensions_Package_Blocks::ACTIVE);
		}
		# Chain
		return $this;
	}
	
} # End class