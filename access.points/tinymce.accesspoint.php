<?php
/**
* 
*/

/**
* 
*/
class CJTTinymceAccessPoint extends CJTAccessPoint {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// initialize base!
		parent::__construct();
		// Set name!
		$this->name = 'tinymce';
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Only if installed!
		if (CJTPlugin::getInstance()->isInstalled()) {
	   // Don't bother doing this stuff if the current user lacks permissions
	   if ((current_user_can('edit_posts') || current_user_can('edit_pages')) && (get_user_option('rich_editing') == 'true')) {
	   	 add_filter('mce_external_plugins', array($this, 'registerButton'));
	   }
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $plugins
	*/
	public function registerButton($plugins) {
		// Load tinymce/shortcodes view through default controller!
		$this->controllerName = 'default';
		$this->route(null, array('view' => 'tinymce/shortcodes'))
		// Display 
		->setAction('display')
		->_doAction();
		// Don't register anything, we just use this filter as Action.
		return $plugins;
	}
	
} // End class