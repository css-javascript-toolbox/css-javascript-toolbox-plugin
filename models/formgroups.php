<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Formgroups {

	/**
	* Delete parameter or group or parameters
	* or all parameters associasted to specific block.
	* 
	* Each Key might has only the parameterId or blockId or both of them.
	* 
	* @param array array('formId' => ID, 'groupId' => 'ID).
	* @return
	*/
	public function delete($keys) {
		// Initialize.
		$tblFormGroup = CJTxTable::getInstance('form-group')
															->setTableKey(array('formId', 'groupId'));
		$tblGroupXFields= CJTxTable::getInstance('form-group-xfields')
															->setTableKey(array('groupId'));
		$tblGroupParam = CJTxTable::getInstance('group-parameter')
															->setTableKey(array('groupId'));
		$tblParamTypedef = CJTxTable::getInstance('parameter-typedef')
																->setTableKey(array('parameterId'));
		// Delete groups..
		foreach ($keys as $key) {
			// Allow only blockId to be passed as scalar!
			if (!is_array($key)) {
				$key = array($key);
			}
			// Blolckid passed @index 0 while parameters id @index 1
			$key = array(
				'formId' => $key[0],
				'groupId' => isset($key[1]) ? $key[1] : null,
			);
			// Get all exists ids
			$groups = $tblFormGroup->setData($key)
														 ->fetchAll();
			// For each group get all assocuated parameters.
			foreach ($groups as $group) {
				// Delete GROUP XFields record associated with the group.
				$tblGroupXFields->set('groupId', $group['id'])	
												->delete();
				// Get all params.
				$params = $tblGroupParam->set('groupId', $group['id'])
																->fetchAll();
				// Delete parameters typedef!
				foreach ($params as $param) {
					$tblParamTypedef->set('parameterId', $param['parameterId'])
													->delete();
				}
				// Delete group parameters.
				$tblGroupParam->delete();
			}
			// Delete groups.
			$tblFormGroup->delete();
		}
		// Chaining.
		return $this;
	}
	
} // End class.
