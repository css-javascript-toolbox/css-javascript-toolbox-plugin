<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_PackageHelper {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $id;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $name;
	
	/**
	* put your comment there...
	* 
	* @param mixed $packageId
	* @return CJT_Models_Package_Xml_Definition_Package_Helper
	*/
	public function __construct($id) {
		$this->id = $id;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $objectId
	* @param mixed $objectType
	* @param mixed $relType
	*/
	public function associateObject($objectId, $objectType, $relType) {
		// Initialize.
		$tblPckObjects = CJTxTable::getInstance('package-objects');
		// Add Block-Template-Link=>Package reference.
		$tblPckObjects->setData(array(
			'packageId' => $this->getId(),
			'objectId' => $objectId,
			'objectType' => $objectType,
			'relType' => $relType,
		))->save();
		// Chain.
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getId() {
		return $this->id;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		return $this->name;
	}

} // ENd class.