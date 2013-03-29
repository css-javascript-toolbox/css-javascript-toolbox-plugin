<?php
/**
* 
*/

/**
* This file is a future replacement for block.php file.
* 
* The controller @block.php file is deprecated and all the feature actions
* for a single block should be defined here!
* 
*/
class CJTXBlockModel {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $inputs;
	
	/**
	* Query Block based on the passed paramaters.
	* 
	*/
	public function getBlockBy() {
		// import dependencies.
		cssJSToolbox::import('framework:db:mysql:xtable.inc.php');
		return CJTxTable::getInstance('block')
		->set($this->inputs['filter']['field'], $this->inputs['filter']['value'])
		->load(array($this->inputs['filter']['field']))
		->getData();
	}
	
} // End class.
