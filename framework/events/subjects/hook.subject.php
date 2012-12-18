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
	* @var mixed
	*/
	protected $instanceId;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $instances = 0;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		$this->instanceId = ++self::$instances;	
	}
	
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
	*/
	public function getInstanceHookName() {
		return "{$this->hookName}-{$this->instanceId}";
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $defintion
	* @param mixed $includes
	*/
	protected function init($name, $target, $defintion, $includes) {
		// Initialize parent!
		$return = parent::init($name, $target, $defintion, $includes);
		$this->hookName = strtolower("{$this->definition['targetClass']}_{$name}");
		// Register Wordpress Filter,
		add_action($this->getHookName(), array(&$this, 'trigger'), 10, count($this->getDefinition('parameters')));
		add_action($this->getInstanceHookName(), array(&$this, 'trigger'), 10, count($this->getDefinition('parameters')));
		return $return;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $params
	*/
	public function prepareHookParameters($params) {
		// Add tag as the first parameter!
		array_unshift($params, $this->getInstanceHookName());
		// Return!
		return $params;
	}
	
} // End class.