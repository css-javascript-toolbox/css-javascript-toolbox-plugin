<?php
/**
* 
*/

/**
* 
*/
class CJTBlockModel {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $inputs;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct()	 {
		
	}
	
	/**
	* Query Block based on the passed paramaters.
	* 
	*/
	public function get() {
		// import dependencies.
		cssJSToolbox::import('framework:db:mysql:xtable.inc.php');
		return CJTxTable::getInstance('block')
		->set($this->inputs['filter']['field'], $this->inputs['filter']['value'])
		->load(array($this->inputs['filter']['field']))
		->getData();
	}
	
} // End class.
