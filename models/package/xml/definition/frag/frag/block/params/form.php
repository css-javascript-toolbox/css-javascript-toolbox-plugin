<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Block_Params_Form
extends CJT_Models_Package_Xml_Definition_Abstract {
	
	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		// Get block element.
		$register = $this->register();
		$blockId = $register['blockId'];
		// Try to load the form
		$tblForm = CJTxTable::getInstance('form');
		$tblForm->set('blockId', $blockId)
										->load();
		// Add form is not exists.
		if (!$tblForm->get('name')) {
			// Fetch form data / All scalar elements!
			$formData = new CJT_Framework_Xml_Fetchscalars($this->getNode());
			// Use blockId as formId.
			$formData['blockId'] = $blockId;
			// Set form data.
			$tblForm->setData($formData)
			// Unofruntatly xTable doesn't support adding the key
			// We need to get around by changing the key name to a dummy one!
											->setTableKey(array('id'))
			// Save into database!
											->save(true);
		}
		// Save form id for the chain!
		$this->register()->offsetSet('formId', $blockId);
		// Chaining.
		return $this;
	}

} // End class