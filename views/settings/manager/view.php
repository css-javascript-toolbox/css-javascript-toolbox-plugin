<?php
/**
* 
*/

// No direct access allowed.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTSettingsManagerView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $pages = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $settings = null;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($info) {
		parent::__construct($info);
		// Define setting pages.
		$this->pages = array(
			array('name' => 'uninstall', 'displayName' => cssJSToolbox::getText('Uninstall')),
			array('name' => 'metabox', 'displayName' => cssJSToolbox::getText('MetaBox')),
		);
	}
	
	/**
	* put your comment there...
	* 
	* 
	*/
	protected function enqueueScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__,
			'jquery-ui-tabs',
			'jquery-serialize-object',
			'thickbox',
			'views:settings:manager:public:js:{CJT-}settings'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function enqueueStyles() {
		// Use related styles.
		self::useStyles(__CLASS__,
			'framework:css:{CJT-}forms',
			'framework:css:jquery-ui-1.8.21.custom',
			'views:settings:manager:public:css:{CJT-}default'
		);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getPage($name, $params = array()) {
		$path = "pages/{$name}";
		return $this->getTemplate($path, $params);
	}
	
} // End class.

// Hookable!!
CJTSettingsManagerView::define('CJTSettingsManagerView');