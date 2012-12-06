<?php
/**
* 
*/

// Import dependencies.
require_once 'subject.interface.php';

/**
* 
*/
abstract class CJTEESubject implements CJTEEISubject, Countable, ArrayAccess {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $definition;
	
	/**
	* put your comment there...
	* 
	* @var CJTIncludes
	*/
	protected $includes;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $observers = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $result;
	
	/**
	* put your comment there...
	* 
	*/
	public function count() {
		return count($this->observers);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getDefinition($name) {
		return $this->definition[$name];
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getInstance($definition, $includes) {
		// Instantiate!
		$subjectClass = $definition['class'];
		$subject = new $subjectClass();
		// Initialize subject!
		$subject->init($definition, $includes);
		return $subject;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $callback
	*/
	public function getObserver($callback) {
		// Make sure observer class file is included!
		$this->includes->import($this->getDefinition('observerFile'));
		// Instantiate observer!
		$observer = call_user_func(array($this->getDefinition('observerClass'), 'getInstance'), $this, $callback);
		return $observer;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getResult() {
		return $this->result;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function init($definition, $includes) {
		$this->definition = $definition;
		$this->includes = $includes;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $callback
	*/
	public function offsetExists($callback) {
		$key = null;
		
		// Check existance!
		return isset($this->observers[$key]);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $callback
	*/
	public function offsetGet($callback) {
		$key = null;
		
		// Return observer!
		return $this->observers[$key];
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $key
	* @param mixed $callback
	*/
	public function offsetSet($key, $callback) {
		$observer = $this->getObserver($callback);
		$key = $observer->getKey();
		// Add to observers list!
		$this->observers[$key] = $observer;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $callback
	*/
	public function offsetUnset($callback) {
		$observer = $this->getObserver($callback);
		$key = $observer->getKey();
		// Just remove from observers list!
		unset($this->observers[$key]);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareResultParameters() {
		// No changes should happen for the general observer parameters!
		return;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function trigger() {
		// Read parameters.
		$this->result['params'] = func_get_args();
		$this->result['return'] = false;
		// Notifying observers!!!
		foreach ($this->observers as $observer) {
			$this->result['return'] = call_user_func_array(array($observer, 'trigger'), $this->result['params']);
			// Prepare parameters based on the previous call result!
			$this->prepareResultParameters();
		}
		return $this->result['return'];
	}
	
} // End class.