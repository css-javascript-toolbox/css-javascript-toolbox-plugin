<?php
/**
* 
*/

/**
* 
*/
abstract class CJTEvents {
 
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $classes = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public static $defaultOptions = array('prefix' => 'on');
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public static $definition;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $freeStatic;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $live;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $options = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public static $paths = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $subjects;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $target;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $targetClass;
	
	/**
	* put your comment there...
	* 
	* @param mixed $target
	* @param mixed $options
	* @return CJTEvents
	*/
	public function __construct($target, $options = array(), $freeStatic = false) {
		// Initialize vars!
		$this->target = $target;
		$this->options = array_merge(self::$defaultOptions, $options);
		$this->freeStatic = $freeStatic;
		$this->targetClass = is_object($this->target) ? get_class($this->target) : $this->target;
		// Define class events if not defined yet!
		$this->define();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $options
	*/
	public static function __init($options = array()) {
		if (!self::$definition) {
			self::$defaultOptions += $options;
			// Definition object!
			self::$definition = (new CJTEventsDefinition());
			// Paths!
			self::$paths['subjects'] = new CJTIncludes('subjects');
			self::$paths['observers'] = new CJTIncludes('observers');
		}
	}

	/**
	* put your comment there...
	* 
	* @param mixed $typeName
	* @param mixed $observer
	* @param mixed $typePrefixed
	* @return CJTEvents
	*/
	public function bind($typeName, $observer, $typePrefixed = true) {
		$type = $this->parseEventType($typeName, $typePrefixed);
		$subject = $this->getSubject($type);
		$subject[] = $observer;
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $type
	*/
	public function createSubject($event) {
		// Initializing!
		$subject = false;
		$event = $this->prepareEventTypeOptions($event);
		$type = $event['type'];
		// Import classd file if not exists!
		if (!class_exists($type['subjectClass'])) {
			self::$paths['subjects']->import($type['file']);
			if (!class_exists($type['subjectClass'])) {
				throw new Exception('Could not instantiate Subject class!! Class is not found!!');
			}
		}
		$type['targetClass'] = $this->targetClass;
		// Instantiate!
		$subject = call_user_func(array($type['subjectClass'], 'getInstance'), $event['name'], $this->target, $type, self::$paths['observers']);
		return $subject;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function define() {
		$className = $this->targetClass;
		if (!isset(self::$classes[$className])) {
			// Add class Definition!
			self::$definition->define($className, $this->options);
			// Store!
			self::$classes[$className] = $this;
		}
		return self::$definition->get($className);
	}

	/**
	* put your comment there...
	* 
	* @deprecated
	*/
	private function findEvents() {
		// If the class is not defined, define it!
		$typeDef  = $this->define();
		// Create subjects!!
		foreach ($typeDef['events'] as $event) {
			$eventId = $event['id'];
			if (!$subject = $this->getSubject($eventId)) {
				$subject = $this->subjects[$eventId] = $this->createSubject($event);
				// Live events!
				$lives = (array) self::$live[$this->targetClass][$eventId];
				foreach ($lives as $live) {
					$subject[] = $live['observer'];
				}
			}
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $class
	*/
	public function getDefinition() {
		return self::$definition->get($this->targetClass);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	*/
	public function getSubject($type) {
		// Initialize.
		$definition = $this->getDefinition();
		$events = $definition['events'];
		//print_r($type);
		// Instantiate subject if not ready yet!
		if (!isset($this->subjects[$type->name])) {
			// Check event type existance!
			if (!isset($events[$type->type])) {
				$type = print_r($type, true);
				throw new Exception("Event type is not found! Could not find ({$type}) event type!");
			}
			$event = $events[$type->type];
			// Create subject object! and bind live events!
			$subject = $this->subjects[$event['id']] = $this->createSubject($event);
			// Live events!
			$lives = isset(self::$live[$this->targetClass][$event['id']]) ? self::$live[$this->targetClass][$event['id']] : array();
			foreach ($lives as $live) {
				$subject[] = $live['observer'];
			}
		}		
		return $this->subjects[$type->name];
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	* @return CJTWordpressEvents
	*/
	public static function getTypeEvents($typeName, $typePrefixed = true) {
	 $type = self::parseEventType($typeName, $typePrefixed);
	 if (!self::$classes[$type->class]) {
		 throw new Exception("Type not found!! Could not find {$typeName}!");
	 }
	 return self::$classes[$type->class];
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function isFreeStatic() {
		return $this->freeStatic;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $typeName
	* @param mixed $observer
	*/
	public function off($typeName, $observer) {
		
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $typeName
	* @param mixed $observer
	*/
	public static function on($typeName, $observer) {
		$type = self::parseEventType($typeName, false);
		// Create live event!
		$on = array();
		$on['observer'] = $observer;
		self::$live[$type->class][$type->name][] = $on;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	*/
	public static function parseEventType($type, $typePrefixed = true) {
		$separator = '.';
		$prefix = 'on';
		// CLASS.TYPE
		$parts = explode('.', $type);
		// Return type array!
		$type = array();
		if (count($parts) == 1) {
			$type['type'] = $parts[0];
		}
		else {
			$type['class'] = $parts[0];
			$type['separator'] = $separator;
			$type['type'] = $parts[1];
		}
		if ($typePrefixed) {
			$type['name'] = substr($type['type'], strlen($prefix));
			$type['prefix'] = $prefix;
		}
		else {
			$type['name'] = $type['type'];
			$type['type'] = "on{$type['type']}";
		}
		return ((object) $type);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	*/
	protected function prepareEventTypeOptions($event) {
		return $event;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	* @param mixed $params
	* @param mixed $typePrefixed
	*/
	public abstract function trigger($type, $params = array(), $typePrefixed = true);
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	* @param mixed $observer
	*/
  public function unbind($type, $observer, $typePrefixed = true) {
		
  }
  
} // End class.