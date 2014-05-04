<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Parameters_Form_Groups
extends ArrayObject {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $formId;
	
	/**
	* put your comment there...
	* 
	* @param mixed $formId
	* @return CJT_Models_Block_Parameters_Form_Groups
	*/
	public function __construct($formId) {
		// Initialize.
		$this->formId = $formId;
		$dbDriver = cssJSToolbox::getInstance()->getDBDriver();
		// Query groups.
		$groups = $dbDriver->select("SELECT * FROM #__cjtoolbox_form_groups g LEFT JOIN #__cjtoolbox_form_group_xfields xf ON g.id = xf.groupId WHERE g.formId = {$this->formId};", ARRAY_A);
		parent::__construct($groups);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getFormId() {
		return $this->formId;
	}
	
} // End class.