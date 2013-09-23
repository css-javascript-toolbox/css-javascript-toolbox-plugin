<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Models_Block_Assignmentpanel_Wordpress
extends CJT_Models_Block_Assignmentpanel_Base {
	
	/**
	* put your comment there...
	* 
	*/
	protected function pinsMap() {
		// Initialize.
		$blockId = $this->getBlockId();
		$params = $this->getTypeParams();
		$pinsMap = array();
		// Prepare block pinned items.
		$dbDriver = cssJSToolbox::getInstance()->getDBDriver();
		$pinsTable = new CJTBlockPinsTable($dbDriver);
		$pins = $pinsTable->get(null, array('blockId' => $blockId, 'pin' => $params['group']));
		// Create ITEM-ID => VALUE array map for the retrieved pins.
		foreach ($pins as $pin) {
			$pinsMap[$pin->value] = true;
		}
		// Returns.
		return $pinsMap;
	}

} // End class.
