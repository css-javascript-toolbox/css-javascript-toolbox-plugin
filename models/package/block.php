<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Block {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $id = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $blockId
	* @return CJT_Models_Package_Block
	*/
	public function __construct($id) {
		// Initialize.
		$this->id = $id;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getPackageInfo() {
		// Initialize.
		$driver = cssJSToolbox::getInstance()->getDBDriver();
		// Query package info for the current block.
		$query = "SELECT p.id, p.author, p.webSite
											FROM #__cjtoolbox_packages p RIGHT JOIN #__cjtoolbox_package_objects o
											ON p.id = o.packageId AND o.objectType = 'block'
											WHERE o.objectId = {$this->id};";
		// Exec!
		$packageInfo = $driver->getRow($query, ARRAY_A);
		return $packageInfo;
	}

} // End class.
