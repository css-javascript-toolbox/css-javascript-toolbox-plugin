<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_List
extends CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_Abstract {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $params = array();
		
	/**
	* put your comment there...
	* 
	* @param mixed $definition
	* @param mixed $factory
	* @param mixed $parent
	* @return CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_List
	*/
	public function __construct($definition, $factory) {
		// Initialize parent.
		parent::__construct($definition, $factory);
		// Create structure parameters.
		foreach ($definition->getChilds() as $parameter) {
			// Instantiate type object.
			$typeObject = $this->getFactory()->create($parameter->getType(), $parameter);
			// Set parent!
			$typeObject->setParent($this);
			// Add to the list.
			$this->params[$parameter->getName(true)] = $typeObject;
		}
	}

	/**
	* put your comment there...
	* 
	*/
	public function getParams() {
		return $this->params;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getValue($useRealNames = null) {
		// Defaults.
		if ($useRealNames === null) {
			$useRealNames = false;
		}
		// Initialize value array.
		$value = array();
		// Get all values from all child parameters.
		foreach ($this->params as $key => $param) {
			// Get name.
			$name = $useRealNames ? $param->getDefinition()->getName() : $key;
			// Set Value!
			$value[$name] = $param->getValue($useRealNames);
		}
		return $value;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function setDefault() {
		// Set child defaults
		foreach ($this->params as $param) {
			$param->setDefault();
		}
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function validate() {
		// Initialize.
		if ($valid = parent::validate()) {
			// Validate structure parameters!
			foreach ($this->params as $param) {
				if (!$valid = $param->validate()) {
					break;
				}
			}			
		}
		return $valid;
	}

} // End class.
