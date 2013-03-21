<?php
/**
* 
*/

//Import dependencies.
require_once 'observer.interface.php';

/**
* 
*/
abstract class CJTObserver implements CJTIObserver {

	/**
	* 
	*/
	const CALLBACK_CLASS = 0;
	
	/**
	* 
	*/
	const CALLBACK_METHOD = 1;
	
	/**
	* 
	*/
	const REDIRECT_MODE_PERMANENT = 0;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $callback;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $filter;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $key;
	
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
	protected $param;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $params;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $subject;
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @return CJTObserver
	*/
	public function __construct($name, $filter = null) {
		$this->name = $name;
		$this->filter = $filter;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getCallback($component = self::CALLBACK_CLASS) {
		$component = ($component !== null) ? $this->callback[$component] : $this->callback;
		return $component;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getFilter($name) {
		return $this->filter[$name];	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $observer
	*/
	public static function getInstance($subject, $callback) {
		if (is_object($callback)) {
			if (!in_array('CJTIObserver', class_implements($callback))) {
				throw new Exception('Invalid observer callback object! Please provide a native PHP callback or observable object!!');
			}
			// callback is actually the observer!
			$observer = $callback;
		}
		else {
			// Define vars (E_ALL complain)!.
			$name = null;
			$filter = null;
			$param = null;
			// Short-hand array structure!
			if (is_array($callback) && isset($callback['callback'])) {
				// Get all params without callback
				if (isset($callback['name'])) {
					$name = $callback['name'];
				}
				if (isset($callback['filter'])) {
					$filter = $callback['filter'];
				}
				$param = $callback['params'];
				// Get PHP native CALLBACK!
				$callback = $callback['callback'];
			}
			// Instantiate Observer!
			$observerClass = $subject->getDefinition('observerClass');
			$observer = new $observerClass($name, $filter);
			$observer->init($subject, $callback, $param);
		}
		return $observer;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getKey() {
		return $this->key;	
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
	*/
	public function getParam() {
		return $this->param;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getSubject() {
		return $this->subject;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getTarget() {
		return $this->getSubject()->getTarget();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $subject
	* @param mixed $callback
	*/
	protected function init($subject, $callback, $param) {
		// Initialize internals!
		$this->subject = $subject;
		$this->callback = $callback;
		$this->param = $param;
		// Chain!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $callback
	* @param mixed $mode
	*/
	public function redirect($callback, $mode = self::REDIRECT_MODE_PERMANENT) {
		// If callback is a class method and only the calss is passed
		// use event name as the method name (DIRECT-NAMING-MAP)! DNM
		if (is_array($callback) && !isset($callback[1])) {
			// @TODO Don't use HJARD-CODED 'on PREFIX!
			$callback[1] = "on{$this->subject->getName()}";
		}
		// Redirect based on the mode!
		switch ($mode) {
			// Remove current observer and add another one to be called
			// next and forever!
			case self::REDIRECT_MODE_PERMANENT :
				// Create a redirect observer and tell it to deattach me!
				$this->subject[] = $callback;
			break;
		}
		return $this->redirectReturn();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected abstract function redirectReturn();
	
	/**
	* put your comment there...
	* 
	*/
	public function trigger() {
		$this->params = func_get_args();
		// Callback!
		$return = call_user_func_array($this->callback, $this->params);
		return $return;
	}
	
} // End class