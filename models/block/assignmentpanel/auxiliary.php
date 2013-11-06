<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Assignmentpanel_Auxiliary
extends CJT_Models_Block_Assignmentpanel_Auxiliarybase {
	
	/**
	* put your comment there...
	* 
	*/
	protected function queryItems() {
		return $this->getList();
	}

	/**
	* put your comment there...
	* 
	*/
	public function getTotalCount() {
		return count($this->getList());
	}

} // End class.
