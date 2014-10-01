<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Block_Links_Link_Package
extends CJT_Models_Package_Xml_Definition_FragPackage {

	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $objectIdKeyName = 'linkedTemplateId';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $objectTypeName = 'template';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $relationTypeName = 'link';
	
	/**
	* put your comment there...
	* 
	* @param mixed $tblPackage
	* @param mixed $packageId
	*/
	protected function save(CJTxTable & $tblPckObjects, $packageId) {
		// Initialize.
		$register =& $this->register();
		// Add Package Ref record,
		// only if this template is not bundled with the package.
		$data = array(
			'packageId' => $packageId,
			'objectId' => $register[$this->objectIdKeyName],
			'objectType' => $this->objectTypeName
		);
		$tblPckObjects->loadAsKey($data);
		if (!$tblPckObjects->get('relType')) {
			// Reset object.
			$tblPckObjects->setItem();
			// Save package object ref.
			parent::save($tblPckObjects, $packageId);
		}
		// Chain.
		return $this;
	}

} // End class