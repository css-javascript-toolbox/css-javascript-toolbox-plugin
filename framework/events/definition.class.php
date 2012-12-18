<?php
/**
* 
*/

/**
* 
*/
class CJTEventsDefinition {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $definitions = array();
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {}
	
	/**
	* put your comment there...
	* 
	* @param mixed $class
	* @param mixed $options
	*/
	public function define($className, $options = array()) {
		$definition = array();
		$definition['options'] = $options;
		// Get class properties to find out the events!
		$class = new ReflectionClass($className);
		$properties = $class->getProperties(ReflectionProperty::IS_PROTECTED);
		$values = $class->getDefaultProperties();
		// protected (static + non-static) represent an event!
		foreach ($properties as $property) {
			$propertyName = $property->getName();
			$propertyClass = $property->getDeclaringClass()->getName();
			if (strpos($propertyName, $options['prefix']) === 0) {
				// Get event properties!
				$eventName = substr($propertyName, strlen($options['prefix']));
				// If the event is already defined inherits the options!
				$inheritedOptions = array();
				if (isset($this->definitions[$propertyClass]['events'][$property->isStatic()][$propertyName])) {
					$inheritedOptions = $this->definitions[$propertyClass]['events'][$property->isStatic()][$propertyName]['type'];
				}
				$definition['events'][$property->isStatic()][$propertyName] = array(
					'fullName' => $propertyName,
					'name' => $eventName, 
					'class' => $propertyClass,
					'id' => $eventName,
					'type' => array_merge($options, $inheritedOptions, ((array) $values[$propertyName]))
				);
			}
		}
		$this->definitions[$className] = $definition;
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $class
	*/
	public function get($class) {
		return $this->definitions[$class];
	}
	
} // End class.
