<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Forms {
	  
	/**
	* put your comment there...
	* 
	* @param mixed $blockId
	*/
	public function delete($blockIds) {
		// Initialize.
		$tblForm = CJTxTable::getInstance('form')
																			->setTableKey(array('blockId'));
		// Delete all forms.
		foreach ($blockIds as $blockId) {
			// Delete the form!
			$tblForm->setData(array('blockId' => $blockId))
											->delete();
		}
		// Delete form groups.
		$mdlGroups = new CJT_Models_Formgroups();
		$mdlGroups->delete($blockIds);
		// Chaining.
		return $this;
	}
	
} // End class.
