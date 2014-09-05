<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_Extensions_Package_Blocks {
	
	/**
	* 
	*/
	const ACTIVE = 'active';

	/**
	* 
	*/
	const INACTIVE = 'inactive';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $statePackage;
	
	/**
	* put your comment there...
	* 
	* @param CJT_Framework_Extensions_Package_State_Packages $statePackages
	* @return {CJT_Framework_Extensions_Package_Blocks|CJT_Framework_Extensions_Package_State_Packages}
	*/
	public function __construct(CJT_Framework_Extensions_Package_State_Packages & $statePackages) {
		# INitialize
		$this->statePackage =& $statePackages;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $state
	*/
	public function setState($state) {
		# Initialize
		$dbDriver = cssJSToolbox::getInstance()->getDBDriver();
		$statePackages =& $this->statePackage;
		# Build packages Id SQL  IN-OPERATO list
		$pcksSQLIdList = array();
		foreach ($statePackages->getInstalledPackages() as $name => $pck) {
			# Push Id.
			$pcksSQLIdList[] = $pck['id'];
		}
		$pcksSQLIdList = implode(',', $pcksSQLIdList);
		# Build query
		$query = "UPDATE
								(#__cjtoolbox_packages p RIGHT JOIN #__cjtoolbox_package_objects po ON p.id = po.packageId
								LEFT JOIN #__cjtoolbox_blocks b ON po.objectId = b.id)
								SET state = '{$state}'
								WHERE p.id IN ({$pcksSQLIdList}) AND po.objectType = 'block';";
		# Update state for all blocks associated with all installed packages
		$dbDriver->exec($query);
		# Chain
		return $this;
	}

} # End class