<?php
/**
* 
*/

/**
* 
*/
abstract class CJTEE { // Events Engine!
	
	/**
	* 
	*/
	const ATTR_EXPLICIT_EVENT = 0x01;
	
	/**
	* 
	*/
	const ATTR_EXPLICIT_SUBJECT  = 0x02;
	
	/**
	* 
	*/
	const EVENT_PREFIX = 'on';
	
	/**
	* 
	*/
	const OBSERVER_CLASS = 'CJTObserver';

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $attributes;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $prefix;
	
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
	private $subjectDefaults;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $subjects = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	* @param mixed $params
	*/
	public abstract function __call($type, $params);
	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @return CJTEE
	*/
	protected function __construct($attributes = self::ATTR_EXPLICIT_EVENT, $subjectDefaults = array(), $prefix = self::EVENT_PREFIX) {
		$this->attributes = new CJTAttributes($attributes);
		$this->subjectDefaults = array_merge(array('class' => 'CJTObserversSubject', 'file' => 'subject'), $subjectDefaults);
		$this->prefix = $prefix;
		// Find events!
		$this->detectEvents();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $event
	*/
	public function __get($type) {
		$attrs =& $this->attributes;
		// The subject isn't registered yet and this is not allowed!
		if (!$this->subjects[$type] && $attrs->isOn(self::ATTR_EXPLICIT_SUBJECT)) {
			throw new Exception('Event Type is not regiestered!! Event type must be registered first!!');
		}
		// Subject need to be created!
		if (!($subject = $this->subjects[$type]['handler'])) {
			$subject = $this->subjects[$type]['handler'] = $this->getSubject($type);
		}
		return $subject;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $event
	* @param mixed $subject
	*/
	public function __set($type, $observer) {
		$this->addListener($type, $observer);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	* @param mixed $observer
	*/
	protected function addListener($type, $observer) {
		$attrs =& $this->attributes;
		// Make sure everything is valid!
		if (!property_exists($this, $type) && $attrs->isOn(self::ATTR_EXPLICIT_EVENT)) {
			throw new Exception('Event Type is not defined!!');
		}
		// Get subject object!
		$subject = $this->__get($type);
		// Add Observer!
		$subject[] = $observer;
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	* @param mixed $observer
	*/
	public function attach($type, $observer) {
		// Prefix with "on"!
		$type = "{$this->prefix}{$type}";
		// Add ti list.
		return $this->addListener($type, $observer);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	* @param mixed $observer
	*/
	public function deattach($type, $observer) {
		
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function detectEvents() {
		// Any variable start with our prefix is an event type
		$haystack	= get_object_vars($this);
		foreach ($haystack as $name => $value) {
			// Event found!
			if (strpos($name, $this->prefix) === 0) {
				$type = $name; // Just name things!
				// Subject need to be created!
				if (!($subject = $this->subjects[$type]['handler'])) {
					$subject = $this->subjects[$type]['handler'] = $this->getSubject($type);
				}
			}
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	*/
	protected function getSubject($type) {
		$subject = false;
		$typeDefinition = $this->prepareTypeDefinition($type);
		// Set defaults!!
		$typeDefinition = array_merge($this->subjectDefaults, $typeDefinition);
		// Import classd file if not exists!
		if (!class_exists($typeDefinition['class'])) {
			self::$paths['subjects']->import($typeDefinition['file']);
			if (!class_exists($typeDefinition['class'])) {
				throw new Exception('Could not instantiate Subject class!! Class is not found!!');
			}
		}
		// On the run parameters!
		$typeDefinition['name'] = substr($type, strlen($this->prefix));
		$typeDefinition['serverClass'] = get_class($this);
		$typeDefinition['parameters'][] = 'subject';
		// Instantiate!
		$subject = call_user_func(array($typeDefinition['class'], 'getInstance'), $typeDefinition, self::$paths['observers']);
		return $subject;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getAttributes() {
		return $this->attributes;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	*/
	protected function prepareTypeDefinition($type) {
		return ((array) $this->{$type});
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	* @param mixed $operation
	* 
	*/
	public function trigggerEvent($type, $params) {
		$this->__call($type, $params);
	}
	
} // End class.

// Statics!
CJTEE::$paths['subjects'] = new CJTIncludes('subjects');
CJTEE::$paths['observers'] = new CJTIncludes('observers');