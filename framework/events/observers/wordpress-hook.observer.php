<?php
/**
* 
*/


//Import dependencies.
require_once 'observer.observer.php';

/**
* 
*/
abstract class CJTWordpressHookObserver extends CJTObserver {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $priority;
	
	/**
	* 
	*/
	const PRIORITY = 10;
	
	/**
	* put your comment there...
	* 
	* @param mixed $priority
	* @return CJTWordpressHookObserver
	*/
	public function __construct($name = null, $filter = null, $priority = self::PRIORITY) {
		// Initialize parent!
		parent::__construct($name, $filter);
		// Initialize vars!
		$this->priority = $priority;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $subject
	* @param mixed $callback
	* @return CJTObserver
	*/
	protected function init($subject, $callback, $param) {
		// Initialize parent!
		$return = parent::init($subject, $callback, $param);
		// Cache callback key!
		$this->key = self::getObserverKey($this->subject->getHookName(), $this->callback, $this->priority);
		return $return;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $callback
	* @param mixed $priority
	*/
	public static function getObserverKey($name, $callback, $priority = self::PRIORITY) {
		return _wp_filter_build_unique_id($name, $callback, $priority);
	}
	
} // End class