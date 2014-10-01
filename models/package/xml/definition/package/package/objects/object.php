<?php
/**
* 
*/

/**
* As the package definition xml file is initially created
* to use <object> tag for both <block> and  <template> type objects
* and then it start to use a tag for evey object type CJT has it then needs
* a way to redirect/proxing the call to the new implementation objects
* <block> and <template>
* 
*/
class CJT_Models_Package_Xml_Definition_Package_Package_Objects_Object
implements CJT_Models_Package_Xml_Definition_Interface_Element {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $object;
	
	/**
	* put your comment there...
	* 
	* @param SimpleXMLElement $node
	* @param mixed $parent
	* @param mixed $factory
	* @return CJT_Models_Package_Xml_Definition_Package_Package_Object
	*/
	public function __construct(SimpleXMLElement $node, $parent, $factory) {
		// Construct type object.
		$objectTypeName = (string) $node->attributes()->type;
		$objectTypeClass = "CJT_Models_Package_Xml_Definition_Package_Package_{$objectTypeName}";
		$this->object = new $objectTypeClass($node, $parent, $factory);		
	}

	/**
	* put your comment there...
	* 
	*/
	public function processInners() {
		return $this->object->processInner();
	}

	/**
	* put your comment there...
	* 
	*/
	public function register() {
		return $this->object->register();
	}	

	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		return $this->object->transit();
	}	
	
	/**
	* put your comment there...
	* 
	*/
	public function virtualPath() {
		return $this->object->virtualPath();
	}
	
} // End class.