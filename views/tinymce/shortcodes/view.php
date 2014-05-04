<?php
/**
* 
*/

// No direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTTinymceShortcodesView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @param mixed $info
	* @return CJTTinymceShortcodesView
	*/
	public function __construct($info) {
		// Initialize parent!
		parent::__construct($info);
		// Register TinyMCE Plugin with Wordpress!
		add_filter('mce_external_plugins', array($this, 'registerPlugin'), 1, 11);
		// Add TinyMCE button for adding shortcode!
		add_filter('mce_buttons', array($this, 'addButton'));
		// Enqueue dependencies scripts.
		self::enqueueScripts();
		self::enqueueStyles();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $buttons
	*/
	public function addButton($buttons) {
		// Add Blocks Shortcode button!
		array_push($buttons, 'separator', 'CJTBlockShortcode');
		return $buttons;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	public function display($tpl = null) {
		// Output view!
		echo $this->getTemplate($tpl);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function enqueueScripts() {
		// Please thos scripts below should be already loaded
		// by the metabox views If the tinymce is loaded inside the editbox
		// IN OTHER CASES!!! what is the other cases! we load it!
		self::useScripts(__CLASS__, 
			'thickbox',
			'framework:js:ajax:{CJT-}cjt-server'
		);
	}

	/**
	* put your comment there...
	* 
	*/
	public function enqueueStyles() {
		self::useStyles(__CLASS__,  'thickbox');
	}
		
	/**
	* put your comment there...
	* 
	* @param mixed $plugins
	*/
	public function registerPlugin($plugins) {
		// Register shortcode TinyMCE button Plugin.
		$plugins['CJTShortcodes'] =  $this->getURI('plugins/shortcode/shortcode.js');
		return $plugins;
	}
	
} // End class.
