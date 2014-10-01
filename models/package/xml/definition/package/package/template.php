<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Package_Package_Template
extends CJT_Models_Package_Xml_Definition_Frag_Frag_Template {

	/**
	* put your comment there...
	* 
	*/
	public function transit() {		
		// Transit Template.
		parent::transit();
		$register = $this->register();
		// Add Package Ref record.
		$pckHelper = new CJT_Models_Package_Xml_Definition_PackageHelper($register['packageId']);
		$pckHelper->associateObject($register['templateId'], 'template', 'add');
		// Chaining.
		return $this;
	}
	
} // End class