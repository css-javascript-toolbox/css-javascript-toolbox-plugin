<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Framework_View_Block_Parameter_Base_List
extends CJT_Framework_View_Block_Parameter_Base_Abstract {	
	
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
		foreach ($this->getParams() as $param) {
			$param->setParent($this);
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function enqueueScripts() {
		// @TODO: Enqueue all childs!!
		return array();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function enqueueStyles() {
		// @TODO: Enqueue all childs!!
		return array();		
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getParams() {
		// Initialize.
		$params = array();
		$typeObject = $this->getTypeObject();
		// As the form objects might handle types that doesn't support
		// child parameters (Example: List type might handle Boolean type!).
		// We need to check the existance of the method.
		if (method_exists($typeObject, 'getParams')) {
			$params = $typeObject->getParams();
		}
		return $params;
	}

} // End class.