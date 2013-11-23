<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Parameters {

	/**
	* Delete parameter or group or parameters
	* or all parameters associasted to specific block.
	* 
	* Each Key might has only the parameterId or blockId or both of them.
	* 
	* @param array array('blockId' => ID, 'parameterId' => 'ID).
	* @return
	*/
	public function delete($keys) {
		// Initialize.
		$tblParams = CJTxTable::getInstance('parameter')
																					->setTableKey(array('blockId', 'parameterId'));
		// Delete parameters.
		foreach ($keys as $key) {
			// Allow only blockId to be passed as scalar!
			if (!is_array($key)) {
				$key = array($key);
			}
			// Blolckid passed @index 0 while parameters id @index 1
			$key = array(
				'blockId' => $key[0],
				'parameterId' => isset($key[1]) ? $key[1] : null,
			);
			// Delete record.
			$tblParams->setData($key)
													->delete();
		}
		// Chaining.
		return $this;
	}

} // End class.
