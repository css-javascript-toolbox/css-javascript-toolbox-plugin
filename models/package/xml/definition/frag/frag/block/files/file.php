<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Block_Files_File
extends CJT_Models_Package_Xml_Definition_Abstract {
	
	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		// Get block element.
		$register = $this->register();
		$blockId = $register['blockId'];
		// CodeFiles table.
		$tblCodeFile = new CJTBlockFilesTable(cssJSToolbox::getInstance()->getDBDriver());
		// Load Code Files if required.
		$register['packageParser']->fetchProperty($this->getNode(), 'code');
		// Fetch form data / All scalar elements!
		$codeFileData = new CJT_Framework_Xml_Fetchscalars($this->getNode());
		// Insert Code File.
		$tblCodeFile->setData($codeFileData)
								->set('blockId', $blockId)
								->save(true, true);
		// Chaining.
		return $this;
	}

} // End class