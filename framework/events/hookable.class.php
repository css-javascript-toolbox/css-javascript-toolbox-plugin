<?php
/**
* 
*/

/**
* 
*/
abstract class CJTHookableClass implements CJTIHookable {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $events;

	/**
	* put your comment there...
	* 
	* @param mixed $options
	* @return CJTHookableClass
	*/
	 protected function __construct($options = array()) {
		 $this->events 	= new CJTWordpressEvents($this, $options);
	 }
	 
	 /**
	 * put your comment there...
	 * 
	 * @param mixed $type
	 * @param mixed $params
	 */
	 public function __call($typeName, $params) {
		 return $this->events->trigger($typeName, $params);
	 }
 		   
	 /**
	 * put your comment there...
	 * 
	 * @param mixed $type
	 * @param mixed $params
	 */
	 public static function __callStatic($typeName, $params) {
		 return CJTWordpressEvents::getTypeEvents($typeName)->trigger($typeName, $params);
	 }
	 
	 /**
	 * put your comment there...
	 * 
	 * @param mixed $type
	 */
	 public function __get($typeName) {
		 return $this->events->getSubject($typeName);
	 }
	 
	 /**
	 * put your comment there...
	 * 
	 * @param mixed $type
	 * @param mixed $observer
	 */
	 public function __set($typeName, $observer) {
		 $this->events->bind($typeName, $observer);
	 }
	 
	 /**
	 * put your comment there...
	 * 
	 * @param mixed $type
	 * @param mixed $observer
	 */
	 public function bind($typeName, $observer) {
	 	 $events =(isset($this) && isset($this->events)) ? 
	 	 												$this->events : 
	 	 												CJTWordpressEvents::getTypeEvents($typeName, false);
	 	 return $events->bind($typeName, $observer, false);
	 }
	 
	 /**
	 * put your comment there...
	 * 
	 * @param mixed $class
	 * @param mixed $options
	 */
	 public static function define($class, $options = array()) {
	 	 return new CJTWordpressEvents($class, $options, true);
	 }
	 
	 /**
	 * put your comment there...
	 * 
	 */
	 public function iEvents() {
			return $this->events; 
	 }
	 
	 /**
	 * put your comment there...
	 * 
	 * @param mixed $typeName
	 * @param mixed $observer
	 */
	 public static function off($typeName, $observer) {
	 	 return CJTWordpressEvents::off($typeName, $observer);
	 }
	 
	 /**
	 * put your comment there...
	 * 
	 * @param mixed $typeName
	 * @param mixed $observer
	 */
	 public static function on($typeName, $observer) {
	 	 return CJTWordpressEvents::on($typeName, $observer);
	 }
	 
	 /**
	 * put your comment there...
	 * 
	 * @param mixed $type
	 */
	 public static function trigger($typeName) {
	 	 $events = (isset($this) && isset($this->events)) ? 
	 	 												$this->events : 
	 	 												CJTWordpressEvents::getTypeEvents($typeName, false);
	 	 // Get passed parameters!
	 	 $params = func_get_args();
	 	 unset($params[0]);
	 	 // Trigger the event!
	 	 return $events->trigger($typeName, $params, false);
	 }
 	 
	/**
	* put your comment there...
	* 
	* @param mixed $type
	* @param mixed $observer
	*/
	public function unbind($typeName, $observer) {
	 	 $events = (isset($this) && isset($this->events)) ? 
	 	 												$this->events : 
	 	 												CJTWordpressEvents::getTypeEvents($typeName, false);
	 	 return $events->unbind($typeName, $observer, false);
	}
 	 
} // End class