<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_Xml_Fetchscalars extends ArrayIterator {
	
	/**
	* put your comment there...
	* 
	* @param SimpleXMLElement $element
	* @return CJT_Framework_Xml_Fetchscalars
	*/
	public function __construct(SimpleXMLElement $element) {
		$data = array();
		// Read all Child elements with no childs inside.
		foreach ($element->children() as $field => $child) {
			// Read all elements with no childs inside!
			// Only with text data!
			if (!count($child->children())) {
				$data[$field] = (string) $child;
			}
		}
		// Load array iterator with fetched data.
		parent::__construct($data);
	}
	
	
} // End class.