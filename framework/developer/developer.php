<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Developer {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $source;
	
	/**
	* put your comment there...
	* 
	* @param mixed $source
	*/
	public function __construct($source) {
		$this->source = $source;
	}

	/**
	* put your comment there...
	* 
	*/
	public function template() {
		static $template = null;
		// Instantiate Template library only when used.
		if (!$template) {
			cssJSToolbox::import('framework:developer:lib:template:template.php');
			$template = new CJT_Framework_Developer_Lib_Template($this->source);
		}
		return $template;
	}

} // End class.