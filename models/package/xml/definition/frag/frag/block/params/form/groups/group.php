<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Block_Params_Form_Groups_Group
extends CJT_Models_Package_Xml_Definition_Abstract {
	
	/**
	* 
	*/
	const VIRTUA_PATH = 'form/group';
	
	/**
	* Required by block/params/list/param/params/group object
	* to search for the associated group!
	* 
	*/
	public function getName() {
		return (string) $this->getNode()->name;
	}

	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		// Initialize.
		$register = $this->register();
		$formId = $register['formId'];
		// Fetch form data / All scalar elements!
		$groupData = new CJT_Framework_Xml_Fetchscalars($this->getNode());
		// Try to load the form
		$tblGroup = CJTxTable::getInstance('form-group');
		// Each form has a unique group names!
		$tblGroup->setTableKey(array('formId', 'name'))
										->set('formId', $formId)
										->set('name', $groupData['name'])
										->load();
		// Add form is not exists.
		if (!$tblGroup->get('id')) {
			// Use blockId as formId.
			$groupData['formId'] = $formId;
			// Set form data.
			$tblGroup->setTableKey(array('id'))
											->setData($groupData)
			// Save into database!
											->save();
		}
		// Save form id for the chain!
		$register['groupId'] = $tblGroup->get('id');
		// Chaining.
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function virtualPath() {
		return self::VIRTUA_PATH;
	}
	

} // End class