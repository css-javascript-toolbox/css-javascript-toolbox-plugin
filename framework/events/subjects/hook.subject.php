<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import dependencies.
require_once 'subject.subject.php';

/**
* 
*/
abstract class CJTEEWordpressHook extends CJTEESubject {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $hookName;
	
	/**
	* put your comment there...
	* 
	* @param mixed $params
	* @return mixed
	*/
	public abstract function callIndirect($params);
	
	/**
	* put your comment there...
	* 
	*/
	public function getHookName() {
		return $this->hookName;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $defintion
	* @param mixed $includes
	*/
	protected function init($defintion, $includes) {
		// Initialize parent!
		$return = parent::init($defintion, $includes);
		$this->hookName = strtolower("{$this->definition['serverClass']}_{$this->definition['name']}");
		// Register Wordpress Filter,
		add_action($this->hookName, array(&$this, 'trigger'), 10, count($this->getDefinition('parameters')));
		return $return;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $params
	*/
	public function prepareHookParameters($params) {
		// Add tag as the first parameter!
		array_unshift($params, $this->hookName);
		// Add subject reference as the last one!
		array_push($params, $this);
		// Return!
		return $params;
	}
	
} // End class.