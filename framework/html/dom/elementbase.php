<?php
/**
* 
*/

class CJT_Framework_Html_Dom_Elementbase {

	/**
	* put your comment there...
	* 
	* @var SimpleXMLElement
	*/
	protected $simpleXML;
	
	/**
	* put your comment there...
	* 
	* @param mixed $tag
	* @return CJT_Framework_Html_Dom_Elementbase
	*/
	public function __construct($tag) {
		// Build XML Content.
		$xmlContent = "<root><{$tag}></{$tag}></root>";
		// Create SIMPLEXMLElement.
		$this->simpleXML = new SimpleXMLElement($xmlContent);
		// Target the tag element.
		$children = $this->getSimpleXML()->children();
		$this->simpleXML = $children[0];
	}

	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		return $this->getSimpleXML()->asXML();
	}

	/**
	* put your comment there...
	* 
	*/
	protected function & getSimpleXML() {
		return $this->simpleXML;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	* @param mixed $ns
	*/
	public function setAttribute($name, $value, $ns = null) {
		return $this->getSimpleXML()->addAttribute($name, $value, $ns);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $content
	*/
	public function & setContent($content) {
		// Set content.
		$this->getSimpleXML()->{0} = $content;
		// Chain.
		return $this;
	}
} // End class.
