<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Block_Params_List_Param_Group
extends CJT_Models_Package_Xml_Definition_Abstract {
	
	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		// Initialize.
		$register = $this->register();
		$paramId = $register['paramId'];
		$groupParamData = new CJT_Framework_Xml_Fetchscalars($this->getNode());
		// Find group!
		$factory = $this->getFactory();
		$groups = $factory->getCreatedObjects('form/group');
		$paramGroupName = (string) $this->getNode()->attributes()->name;
		// Find the group to be associated with the parameter.
		foreach ($groups as $group) {
			// Find the group by the given name.
			if ($group->getName() == $paramGroupName) {
				// Check existntance!
				$tblGroupParam = CJTxTable::getInstance('group-parameter')
																											->setTableKey(array('parameterId'))
																											->set('parameterId', $paramId)
																											->load();
				// Add if not exists!
				if (!$tblGroupParam->get('groupId')) {
					// Get group id.
					$groupId = $group->register()->offsetGet('groupId');
					// Prepare data.
					$groupParamData['parameterId'] = $paramId;
					$groupParamData['groupId'] = $groupId;
					// Save to database.
					$tblGroupParam->setTableKey(array('id'))
																		->setData($groupParamData)
																		->save();
				}
				break;
			}
		}
		return $this;
	}

} // End class