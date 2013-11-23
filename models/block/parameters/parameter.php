<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Parameters_Parameter {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $contentParameter;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $defaultValue;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $id;
	
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
	protected $order;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $parent;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $required = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $type;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $childs = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $type
	* @param mixed $required
	* @param mixed $defaultValue
	* @param mixed $parent
	* @param mixed $id
	* @return CJT_Models_Block_Parameters_Parameter
	*/
	public function __construct($parameter) {
		$this->contentParameter = isset($parameter['contentParam']) ? $parameter['contentParam'] : null;
		$this->defaultValue = isset($parameter['defaultValue']) ? $parameter['defaultValue'] : null;
		$this->id = isset($parameter['id']) ? $parameter['id'] : null;
		$this->name = isset($parameter['name']) ? $parameter['name'] : null;
		$this->parent = isset($parameter['parent']) ? $parameter['parent'] : null;
		$this->required = isset($parameter['required']) ? $parameter['required'] : null;
		$this->type = isset($parameter['type']) ? $parameter['type'] : null;
		$this->order =  isset($parameter['order']) ? $parameter['order'] : null;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $childs
	* @return CJT_Models_Block_Parameters_Parameter
	*/
	public function addChild($child) {
		// Add child paramter!
		$this->childs[$child->getId()] = $child;
		// Chaining.
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getChilds() {
		return $this->childs;
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
	public function getDefaultValue() {
		return $this->defaultValue;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getId() {
		return $this->id;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getOrder() {
		return $this->order;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $slugIt
	*/
	public function getName($slugIt = false) {
		return $slugIt ? strtolower($this->name) : $this->name;
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
	public function getRequired() {
		return $this->required;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getType() {
		 return $this->type;
	}

} // End class.
