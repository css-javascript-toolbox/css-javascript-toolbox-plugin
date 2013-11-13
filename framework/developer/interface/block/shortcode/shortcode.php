<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Shortcode_Shortcode {
	
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
	protected $content = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $parameters = null;
	
	/**
	* put your comment there...
	* 
	* @var CJT_Framework_Developer_Interface_Block_Shortcode_Segments
	*/
	protected $segments = null;

	/**
	* put your comment there...
	* 
	* @param mixed $block
	* @param mixed $parameters
	* @param mixed $content
	* @return CJT_Framework_Developer_Interface_Block_Shortcode
	*/
	public function __construct($block, $parameters, & $content) {
		// Initialize.
		$this->block = $block;
		$this->content =& $content;
		$this->parameters = new CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Parameters(
			new CJT_Models_Block_Parameters_Parameters($block->id)
		);
		// Load from shortcode parameters.
		$this->parameters->loadString($parameters, $content);
		//$this->segments = new CJT_Framework_Developer_Interface_Block_Shortcode_Segments_Segments($this->cleanContent());
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
	public function & cleanContent() {
		// Shortcode content.
		$content = $this->content();
		// Strip <BR /> tags added by Wordpress.
		$content = str_replace("<br />\n", "\n", $content);
		// Remove first and last.
		$content = preg_replace(array('/^\xA/', '/\xA$/'), '', $content);;
		return $content;
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
	* @param mixed $new
	*/
	public function content($value = null) {
		// Return content until its a __setter_.
		$result = $this->content;
		// Set value if apssed.
		if ($value !== null) {
			$this->content = $value;
			$result = $this;
		}
		return $result;
	}

	/**
	* put your comment there...
	* 
	*/
	public function fw() {
		static $fw = null;
		// Instantiate framework only when used.
		if (!$fw) {
			$fw = new CJT_Framework_Developer_Developer($this->block);
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
	
	/**
	* put your comment there...
	* 
	*/
	public function segments() {
		return $this->segments;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $done
	*/
	public function write($done = null) {
		if ($done) {
			$this->content(ob_get_clean());
		}
		else {
			ob_start();
		}
		return $this;
	}

} // End class.