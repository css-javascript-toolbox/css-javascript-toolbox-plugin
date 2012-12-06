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
	protected $subject;
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @return CJTObserver
	*/
	public function __construct($name) {
		$this->name = $name;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getCallback() {
		return $this->callback;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $observer
	*/
	public static function getInstance($subject, $callback) {
		if (is_object($callback)) {
			if (!in_array(class_parents('CJTObserver', $callback))) {
				throw new Exception('Invalid observer callback object! Please provide a native PHP callback or observable object!!');
			}
			// callback is actually the observer!
			$observer = $callback;
		}
		else {
			// Instantiate Observer!
			$observerClass = $subject->getDefinition('observerClass');
			$observer = new $observerClass();
			$observer->init($subject, $callback);
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
	* @param mixed $subject
	* @param mixed $callback
	*/
	protected function init($subject, $callback) {
		// Initialize internals!
		$this->subject = $subject;
		$this->callback = $callback;
		// Chain!
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function trigger() {
		$params = func_get_args();
		$return = call_user_func_array($this->callback, $params);
		return $return;
	}
	
} // End class