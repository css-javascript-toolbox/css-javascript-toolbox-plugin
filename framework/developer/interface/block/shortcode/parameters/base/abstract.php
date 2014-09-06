<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_Abstract
implements CJT_Framework_Developer_Interface_Block_Parameters_Types_Interface_Type, 
CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Interface_Type {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $typeObject;
	
	/**
	* put your comment there...
	* 
	* @param mixed $parameter
	* @param mixed $factory
	* @return CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Base_Abstract
	*/
	public function __construct($parameter, $factory) {
		// Get base type factory.
		$baseTypeFactory = $this->getBaseTypeFactory($parameter);
		// Share the supplied factory!
		$this->typeObject = $baseTypeFactory['factory']->create($baseTypeFactory['typeName'], $parameter, $factory);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $parameter
	*/
	public function getBaseTypeFactory($parameter) {
		// Hold local instance!
		static $baseTypeFactory;
		if (!$baseTypeFactory) {
			$baseTypeFactory = new CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_Factory();
		}
		// Base type name is mapped to this class name!
		// boolean => boolean, texts => texts, etc....
		$className =  get_class($this);
		$name = substr($className, strrpos($className, '_') + 1);
		// Type factory
		return array(
			'factory' => $baseTypeFactory,
			'typeName' => $name,
		);
	}

	/**
	* put your comment there...
	* 
	*/
	public function getDefinition() {
		return $this->getTypeObject()->getDefinition();
	}

	/**
	* put your comment there...
	* 
	*/
	public function getFactory() {
		return $this->getTypeObject()->getFactory();
	}

	/**
	* put your comment there...
	* 
	*/
	public function getParent() {
		return $this->getTypeObject()->getParent();
	}

	/**
	* put your comment there...
	* 
	*/
	public function getTypeObject() {
		return $this->typeObject;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getValue($useRealNames = null) {
		return $this->getTypeObject()->getValue($useRealNames);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $parent
	*/
	public function setParent($parent) {
		return $this->getTypeObject()->setParent($parent);
	}
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setValue($value) {
		return $this->getTypeObject()->setValue($value);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function validate() {
		return $this->getTypeObject()->validate();
	}
	
} // End class.
