<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Block_Params_List_Param
extends CJT_Models_Package_Xml_Definition_Abstract {
	
	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		// Initialize.
		$register = $this->register();
		$blockId = $register['blockId'];
		$parent = isset($register['paramId']) ? $register['paramId'] : null;
		$paramData = new CJT_Framework_Xml_Fetchscalars($this->getNode());
		// Block root parameter and child parameter names is unique
		$tblParam = CJTxTable::getInstance('parameter')
																				->setTableKey(array('blockId', 'parent', 'name'))
																				->set('blockId', $blockId)
																				->set('parent', $parent)
																				->set('name', $paramData['name'])
																				->load();
		// Add if doesn't exists!
		if (!$tblParam->get('id')) {
			// Set relation data.
			$paramData['blockId'] = $blockId;
			$paramData['parent'] = $parent;
			// Save into database.
			$tblParam->setTableKey(array('id'))
												->setData($paramData)
												->save();
		}
		// Add (Or Override parent parameter id on the chain!)
		$register['paramId'] = $tblParam->get('id');
		// Chaining.
		return $this;
	}

} // End class