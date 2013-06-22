<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Parameters_Parameters
extends CJT_Models_Block_Parameters_Base_Parameters {
	
	/**
	* put your comment there...
	* 
	* @param mixed $row
	*/
	public function createModelObject($row) {
		return new CJT_Models_Block_Parameters_Parameter($row);
	}

	/**
	* put your comment there...
	* 
	*/
	protected function getQuery() {
		$query = "SELECT p.* 
										 FROM #__cjtoolbox_parameters p LEFT JOIN #__cjtoolbox_blocks b ON p.blockId = b.id
										 WHERE b.id = {$this->blockId}
										 ORDER by id ASC;";
		return $query;
	}

} // End class.
