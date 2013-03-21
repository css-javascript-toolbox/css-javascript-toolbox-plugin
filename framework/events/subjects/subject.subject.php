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
	protected $name;
	
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
	* @var mixed
	*/
	protected $target;
	
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
		return isset($this->definition[$name]) ? $this->definition[$name] : null;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getInstance($name, $target, $definition, $includes) {
		// Instantiate!
		$subjectClass = $definition['subjectClass'];
		$subject = new $subjectClass();
		// Initialize subject!
		$subject->init($name, $target, $definition, $includes);
		return $subject;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		return $this->name;	
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
	public function getTarget() {
		return $this->target;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function init($name, $target, $definition, $includes) {
		$this->name = $name;
		$this->target = $target;
		$this->definition = $definition;
		$this->includes = $includes;
		return $this;
	}
	  
	  /**
	  * put your comment there...
	  *                                                                                                 
	  * @param mixed $params
	  */
	protected function initResultArray($params) {
		// Add observer as the first parameter!
		$this->result['params'] = array('observer' => null) + $params;
		//--- Add target + subject!
		//--- cancel for now !! array_unshift($this->result['params'], $this, $this->target);
		$this->result['return'] = false;
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
	* @param mixed $observer
	*/
	protected function processFilter($observer) {
		return true; // Always call observer!
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function trigger() {
		// Read parameters.
		$this->initResultArray(func_get_args());
		reset($this->observers);
		while ($observer = current($this->observers)) {
			if ($this->processFilter($observer)) {
				// Pass observer referecne along with user params!!
				$this->result['params']['observer'] = $observer;
				$this->result['return'] = call_user_func_array(array($observer, 'trigger'), $this->result['params']);
				// Prepare parameters based on the previous call result!
				$this->prepareResultParameters();				
			}
			next($this->observers);
		}
		// Return last result!
		return $this->result['return'];
	}
	
} // End class.