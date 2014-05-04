<?php
/**
* 
*/

class CJT_Framework_Html_Dom_Element_Script
extends CJT_Framework_Html_Dom_Elementbase {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $vars;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Create script element.
		parent::__construct('script');
		// INitialize vars.
		$this->vars = new ArrayObject(array());
		// INitialize with defauls.
		$this->setType('text/javascript');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		// Set vars.
		$content = '';
		$vars =& $this->vars();
		foreach ($vars as $var => $value) {
			$content .= "var {$var}={$value};";
		}
		$this->setContent($content);
		// Get parent string.
		return parent::__toString();
	}

	/**
	* put your comment there...
	* 
	* @param mixed $type
	*/
	public function & setType($type) {
		// Set type.
		$this->setAttribute('type', $type);
		// chain.
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & vars() {
		return $this->vars;
	}

} // End class.
