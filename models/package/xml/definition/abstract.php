<?php
/**
* 
*/

/**
* 
*/
abstract class CJT_Models_Package_Xml_Definition_Abstract 
implements CJT_Models_Package_Xml_Definition_Interface_Element {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $childs = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $defaultRule = true;
	
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
	protected $forceProcess = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $map = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $node = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $parent = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $register;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $rules = array();

	/**
	* put your comment there...
	* 
	* @param SimpleXMLElement $node
	* @param mixed $factory
	* @return CJT_Models_Package_Xml_Definition_Abstract
	*/
	public function __construct(SimpleXMLElement $node, $parent, $factory) {
		// Initialize.
		$this->node = $node;
		$this->parent = $parent;
		$this->factory = $factory;
		$this->register = new ArrayObject(array());
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
	* @param mixed $child
	*/
	protected function getDefaultMap($child) {
		// Initiaize.
		$path = '';
		$factory = $this->getFactory();
		// Get default object path.
		$path = $factory->obtainRelativePath(get_class($this), $child);
		return $path;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function getDefaultRule() {
		return $this->defaultRule;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getFactory() {
		return $this->factory;
	}

	/***
	* put your comment there...
	* 
	*/
	protected function getNode() {
		return $this->node;
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
	public function processInners() {
		// Initialize.
		$factory = $this->getFactory();
		$childs = $this->getNode()->children();
		// For every child node that has childs create an object!
		foreach ($childs as $childNode) {
			// Get node name.
			$nodeName = $childNode->getName();
			// Process only childs with childs neested or what added to the force-process-map.
			// Don't process scalars until they're on the force-process-map!
			if (in_array($nodeName, $this->forceProcess) || count($childNode->children())) {
				// Initialize object factory details.
				$objectInfo = array();
				// Get rule.
				$objectInfo['rule'] = isset($this->rules[$nodeName]) ? $this->rules[$nodeName] : $this->getDefaultRule();
				// Only process if rule is set to TRUE!
				if ($objectInfo['rule']) {
					// Get object class map.
					$objectInfo['map'] = isset($this->map[$nodeName]) ? $this->map[$nodeName] : $this->getDefaultMap($nodeName);
					// Instantiate object!
					$childObject = $factory->create($this, $objectInfo['map'], $childNode);
					// Share parameters.
					$childObject->register()->exchangeArray($this->register()->getArrayCopy());
					// Transit.
					$childObject->transit()
					// Process inners.
											->processInners();
					// Cache!
					$this->childs[] = $childObject;
				}
			}
		}
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & register() {
		return $this->register;
	}

	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		return $this;
	}

	/**
	* Default implementation to return physical path
	* relative to the current package document.
	* 
	*/
	public function virtualPath() {
		// Initialize.
		$factory =& $this->factory;
		// Return physical path relative to package document.
		return $factory->getClassRelativePath(get_class($this));
	}

} // End class.
