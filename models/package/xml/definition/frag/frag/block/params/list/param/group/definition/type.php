<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Block_Params_List_Param_Group_Definition_Type
extends CJT_Models_Package_Xml_Definition_Abstract {
	
	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		// Initialize.
		$register = $this->register();
		$paramId = $register['paramId'];
		$typedefData = new CJT_Framework_Xml_Fetchscalars($this->getNode());
		// Prepare data.
		$typedefData['parameterId'] = $paramId;
		// Save to database.
		$tblTypedef = CJTxTable::getInstance('parameter-typedef')
																					->setData($typedefData)
																					->save();
		
		// Chaining.
		return $this;
	}

} // End class