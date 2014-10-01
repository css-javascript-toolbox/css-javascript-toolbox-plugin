<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Block_Params_Form_Groups_Group_xFields
extends CJT_Models_Package_Xml_Definition_Abstract {

	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		// Initialize.
		$register = $this->register();
		$groupId = $register['groupId'];
		// Fetch form data / All scalar elements!
		$groupXFieldsData = new CJT_Framework_Xml_Fetchscalars($this->getNode());
		$groupXFieldsData['groupId'] = $groupId;
		// Save Group XFIELDS data.
		CJTxTable::getInstance('form-group-xfields')
						  ->setData($groupXFieldsData)
						  ->save();
		// Chaining.
		return $this;
	}

} // End class