<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Models_Package_Xml_Definition_FragPackage 
extends CJT_Models_Package_Xml_Definition_Abstract {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $objectIdKeyName;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $objectTypeName;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $relationTypeName;
	
	/**
	* put your comment there...
	* 
	* @param mixed $tblPackage
	* @param mixed $packageId
	*/
	protected function save(CJTxTable & $tblPckObjects, $packageId) {
		// Initialize.
		$register =& $this->register();
		// Save Package object ref
		$tblPckObjects->setData(array(
			'packageId' => $packageId,
			'objectId' => $register[$this->objectIdKeyName],
			'objectType' => $this->objectTypeName,
			'relType' => $this->relationTypeName,
		))->save();
		// Chain/
		return $this;
	}
	/**
	* Do nothing!
	* 
	*/
	public function transit() {
		// Initialize.
		$tblPckObjects = CJTxTable::getInstance('package-objects');
		// Query package id.
		$tblPck = CJTxTable::getInstance('package');
		$pckData = new CJT_Framework_Xml_Fetchscalars($this->getNode());
		$tblPck->setData($pckData)
					 ->load(array('name'));
		// Add Block-Template-Link=>Package reference.
		$this->save($tblPckObjects, $tblPck->get('id'));
		// Chain.
		return $this;
	}

}