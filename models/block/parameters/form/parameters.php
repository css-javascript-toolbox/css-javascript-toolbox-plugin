<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Parameters_Form_Parameters
extends CJT_Models_Block_Parameters_Base_Parameters {
	
	/**
	* put your comment there...
	* 
	* @param mixed $blockId
	* @return CJT_Models_Block_Parameters_Form_Parameters
	*/
	public function __construct($blockId) {
		// Parent procedure!
		parent::__construct($blockId);
		// Read all values.
		$this->assignValues();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function assignValues() {
		// Initialize.
		$driver = cssJSToolbox::getInstance()->getDBDriver();
		// For every parameter assign the values.
		foreach ($this->getParams() as $param) {
			// Query all values.
			$recset = $driver->select("SELECT * 
																									FROM #__cjtoolbox_parameter_typedef
																									WHERE parameterId = {$param->getId()}", ARRAY_A);
			foreach ($recset as $valueRow) {
				$param->addValue(new CJT_Models_Block_Parameters_Form_Value($valueRow));
			}
		}
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $row
	*/
	public function createModelObject($row) {
		return new CJT_Models_Block_Parameters_Form_Parameter($row);
	}

	/**
	* put your comment there...
	* 
	*/
	protected function getQuery() {
		$query = "SELECT p.*, pe.renderer, pe.description, pe.helpText, g.id groupId, g.name groupName, g.description groupDescription
										 FROM #__cjtoolbox_parameters p LEFT JOIN #__cjtoolbox_blocks b ON p.blockId = b.id
										 LEFT JOIN #__cjtoolbox_form_group_parameters pe ON pe.parameterId = p.id
										 LEFT JOIN #__cjtoolbox_form_groups g ON pe.groupId = g.id
										 WHERE b.id = {$this->blockId}
										 ORDER by id ASC;";
		return $query;
	}

} // End class.
