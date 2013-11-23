<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_View_Block_Parameter_Renderer_Array_Array
extends CJT_Framework_View_Block_Parameter_Base_List {
	
	/**
	* put your comment there...
	* 
	* @param mixed $parameter
	* @param mixed $factory
	* @return CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_Abstract
	*/
	public function __construct($parameter, $factory) {
		// Parent procedure!
		parent::__construct($parameter, $factory);
		// Set parent reference for all child parameters.
		foreach ($this->getValues() as $param) {
			$param->setParent($this);
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getValues() {
		return $this->getTypeObject()->getValues();
	}
	
} // End class.