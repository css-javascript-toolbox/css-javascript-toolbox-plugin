<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Package_Package_Block
extends CJT_Models_Package_Xml_Definition_Frag_Frag_Block {

	/**
	* put your comment there...
	* 
	*/
	public function transit() {		
		// Transit Block.
		parent::transit();
		$register = $this->register();
		// Add Package Ref record.
		$pckHelper = new CJT_Models_Package_Xml_Definition_PackageHelper($register['packageId']);
		$pckHelper->associateObject($register['blockId'], 'block', 'add');
		// Chaining.
		return $this;
	}

} // End class