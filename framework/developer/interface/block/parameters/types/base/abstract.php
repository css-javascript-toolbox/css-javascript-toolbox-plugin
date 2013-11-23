<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_Abstract 
implements CJT_Framework_Developer_Interface_Block_Parameters_Types_Interface_Type {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $definition;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $factory = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $parent = null;

	/**
	* put your comment there...
	* 
	* @param mixed $definition
	* @param mixed $factory
	* @return CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_Abstract
	*/
	public function __construct($definition, $factory) {
		// Initialize.
		$this->definition = $definition;
		$this->factory = $factory;
		// Set Default value!
		$this->setDefault();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getDefinition() {
		return $this->definition;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getFactory() {
		return $this->factory;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getParent() {
		return $this->parent;
	}

	/**
	* put your comment there...
	* 
	*/
	protected abstract function setDefault();
	
	/**
	* put your comment there...
	* 
	* @param mixed $parent
	*/
	public function setParent($parent) {
		// Set parent.
		$this->parent = $parent;
		// Chaining.
		return $this;
	}
	/**
	* put your comment there...
	* 
	*/
	public function validate() {
		// Get final value!
		$value = $this->getValue();
		// Validate is required!
		return (!$this->getDefinition()->getRequired() || !empty($value));
	}

} // End class.
