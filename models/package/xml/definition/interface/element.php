<?php
/**
* 
*/

/**
* 
*/
interface CJT_Models_Package_Xml_Definition_Interface_Element {
	
	/**
	* put your comment there...
	* 
	* @param SimpleXMLElement $node
	* @return CJT_Models_Package_Xml_Definition_Interface_Element
	*/
	public function __construct(SimpleXMLElement $node, $parent, $factory);
	
	/**
	* put your comment there...
	* 
	*/
	public function processInners();
	
	/**
	* put your comment there...
	* 
	*/
	public function register();
	
	/**
	* put your comment there...
	* 
	*/
	public function transit();
	
	/**
	* Return virual path to current doc object
	*/
	public function virtualPath();
	
} // End class.
