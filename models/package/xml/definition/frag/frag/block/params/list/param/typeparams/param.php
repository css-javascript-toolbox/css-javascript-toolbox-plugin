<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Block_Params_List_Param_TypeParams_Param
extends CJT_Models_Package_Xml_Definition_Abstract {
	
	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		// Initialize.
		$register = $this->register();
		$paramId = $register['paramId'];
		$typeparamsData = new CJT_Framework_Xml_Fetchscalars($this->getNode());
		// Prepare data.
		$typeparamsData['parameterId'] = $paramId;
		// Save to database.
		$tblTypedef = CJTxTable::getInstance('parameter-typeparams')
																					->setData($typeparamsData)
																					->save();
		
		// Chaining.
		return $this;
	}

} // End class