<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Shortcode {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $bcid;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $block = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $containerElementId = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $parameters = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $block
	* @param mixed $parameters
	* @return CJT_Framework_Developer_Block_Shortcode
	*/
	public function __construct($block, $parameters) {
		// Initialize.
		$this->block = $block;
		$this->parameters = (object) $parameters;
		// Generate Shortcode block container id.
		$this->bcid = md5(microtime());
		// Build block container element id.
		$this->containerElementId = "csmi-{$this->bcid}";
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function bcid() {
		return $this->bcid;
	}

	/**
	* put your comment there...
	* 
	*/
	public function block() {
		return $this->block;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function containerElementId() {
		return $this->containerElementId;
	}

	/**
	* put your comment there...
	* 
	*/
	public function fw() {
		static $fw = null;
		// Instantiate framework only when used.
		if (!$fw) {
			cssJSToolbox::import('framework:developer:developer.php');
			$fw = new CJT_Framework_Developer($this->block);
		}
		return $fw;
	}

	/**
	* put your comment there...
	* 
	*/
	public function params() {
		return $this->parameters;
	}

} // End class.