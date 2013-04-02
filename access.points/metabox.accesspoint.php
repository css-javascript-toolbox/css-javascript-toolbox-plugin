<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTMetaboxAccessPoint extends CJTAccessPoint {
	 
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'metabox';
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Only if permitted!
		if ($this->hasAccess()) {
			// Add CJT Block metabox!
			add_action("add_meta_boxes", array(&$this, 'postsMetabox'), 10, 2);
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $postType
	* @param mixed $post
	*/
	public function postsMetabox($postType, $post) {
		// Initialize.
		$controller = false;
		// Veil access point unless CJT installed!
		if (CJTPlugin::getInstance()->isInstalled()) {
			// Only if permitted!
			if ($this->hasAccess()) {
				// Set as connected object!
				$this->connected();
				// Load metabox controller!
				$this->controllerName = 'metabox';
				// Standarize calling the controller with Ajax requests!
				// Ajax uses 'post' parameter as postId!
				$post = $post->ID;
				// Dispatch controller!
				$controller = $this->route(null, compact('postType', 'post'));
			}
		}
		return $controller;
	}
	
} // End class.

// Hookable!
CJTMetaboxAccessPoint::define('CJTMetaboxAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));