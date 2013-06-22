<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Shortcode_Segments_Segments 
	extends CJT_Framework_Developer_Interface_Block_Parameters_Parameters {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $content = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $content
	* @return CJT_Framework_Developer_Interface_Block_Shortcode_Segments
	*/
	public function __construct(& $content) {
		// Initialize.
		$this->content =& $content;
		// Initialize parent.
		parent::__construct(array());
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function validate() {
		// Read segments from Shortcode content.
		$segments = preg_split('/(\w+)\=\:\s+/', $this->content, null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		// All even indexes if for segment names and
		// odd index for values.
		for ($index = 0; $index < count($segments); $index+=2) {
			// Get key from current item and value from the next.
			$this->uParams[$segments[$index]] = $segments[$index + 1];
		}
		// Parent vaidation.
		return parent::validate();
	}
	
} // End class.