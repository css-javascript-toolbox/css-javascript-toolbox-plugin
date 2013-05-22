<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
* 
* This is the first class name build for using autoload!
* Autoload is not being supported yet however this is the first
* class on the plain!!!!
*/
class CJT_Controllers_Coupling_Shortcode extends CJTHookableClass {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $attributes;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $content = null;

	/**
	* put your comment there...
	* 
	* @param mixed $attributes
	* @param mixed $content
	* @return CJT_Controllers_Coupling_Shortcode
	*/
	public function __construct($attributes, $content) {
		// Hookable initialization!
		parent::__construct();
		// Initialize.
		$this->attributes = $attributes;
		$this->content = $content;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		// Initialize.
		$attributes =& $this->attributes;
		// Default Class.
		if (!isset($attributes['class'])) {
			$shortcodeClass =	'block';
		}
		// Get Shortcode class file, check existance!
		$shortcodeClassFile = cssJSToolbox::resolvePath('controllers:coupling:shortcode:block:block.php');
		if (!file_exists($shortcodeClassFile)) {
			throw new Exception('Shortcode Type is not supported!! Only (block) type is currently available!!!');
		}
		// Import shortcodee handler file.
		require_once $shortcodeClassFile;
		// Import class.
		$className = "CJT_Controllers_Coupling_Shortcode_{$shortcodeClass}";
		// Load shortcode 'CLASS' handler.
		$shortcode = new $className($this->attributes, $this->content);
		// Return Shortcode replacements.
		return ((string) $shortcode);
	}

} // End class

// Hookable!
CJT_Controllers_Coupling_Shortcode::define('CJT_Controllers_Coupling_Shortcode');