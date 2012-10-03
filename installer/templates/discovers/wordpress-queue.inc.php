<?php
/**
* 
*/

/**
* 
*/
class TemplatesWordpressQueueDiscovering extends ArrayIterator {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dbDriver;
	
	/**
	* put your comment there...
	*                                   
	* @var mixed
	*/
	protected $templates = array();
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($dbDriver) {
		// Allow Third-Party to customize the installation process.
		parent::__construct(&$this->templates);
		// Instantuate Authors model.
		$this->dbDriver = $dbDriver;
		// Our job is to cache Wordpress registered scripts and styles.
		$this->discover();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function discover() {
		global $wp_scripts, $wp_styles;
		// Process both JS and CSS within single loop.
		$wpTemplates = array_merge($wp_scripts->registered, $wp_styles->registered)	;
		foreach ($wpTemplates as $wpTemplate) {
			$template = array()	;
			$template['name'] = $wpTemplate->
			
			// Add to discover list.
			$this->templates = $template;
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function install() {
		
	}
	
} // End class.