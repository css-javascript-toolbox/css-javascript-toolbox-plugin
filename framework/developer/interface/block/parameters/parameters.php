<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class CJT_Framework_Developer_Interface_Block_Parameters_Parameters {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $contentParameter = null;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $params = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $parameters
	* @param mixed $fatory
	* @return CJT_Framework_Developer_Interface_Block_Parameters_Parameters
	*/
	public function __construct($parameters) {
		// Validate parameters.
		foreach ($parameters as $parameter) {
			// Create type object.
			$parameterModel = $this->params[$parameter->getName(true)] = $this->getFactory()->create($parameter->getType(), $parameter);
			// Hold reference to the CONTENT PARAMETER.
			$parameter->getContentParameter() && ($this->contentParameter = $parameterModel);
		}
	}

	/**
	* put your comment there...
	* 
	*/
	public function getContentParameter() {
		return $this->contentParameter;
	}

	/**
	* put your comment there...
	* 
	*/
	protected abstract function getFactory();

	/**
	* put your comment there...
	* 
	*/
	public function & getParams() {
		return $this->params;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getMessages() {
		
	}

	/**
	* put your comment there...
	* 
	*/
	public function hasParams() {
		return !empty($this->params);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $values
	*/
	public function setValue($values) {
		foreach ($this->getParams() as $name => $param) {
			if (isset($values[$name])) {
				$param->setValue($values[$name]);	
			}
		}
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function validate() {
		return true;
	}

} // End class.