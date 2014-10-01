<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Package_Package_Block_Links_Link
extends CJT_Models_Package_Xml_Definition_Frag_Frag_Block_Links_Link {
	
	/**
	* put your comment there...
	* 
	*/
	public function transit() {		
		// Transit Link.
		parent::transit();
		$register = $this->register();
		// Add Package Ref record
		// ,only if this template is not bundled with the package.
		$packObjectsTbl = CJTxTable::getInstance('package-objects')
		->loadAsKey(array(
			'packageId' => $register['packageId'],
			'objectId' => $register['linkedTemplateId'],
			'objectType' => 'template'
		));
		if (!$packObjectsTbl->get('relType')) {
			$pckHelper = new CJT_Models_Package_Xml_Definition_PackageHelper($register['packageId']);
			$pckHelper->associateObject($register['linkedTemplateId'], 'template', 'link');
		}
		// Chaining.
		return $this;
	}

} // End class