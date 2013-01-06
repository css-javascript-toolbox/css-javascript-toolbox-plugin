<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
abstract class CJTAccessPoint {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controller;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerName;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $loaded;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $name;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $pageId = CJTPlugin::PLUGIN_REQUEST_ID;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($defaultController = 'blocks') {
		$this->controllerName = $_REQUEST['controller'] ? $_REQUEST['controller'] : $defaultController;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getController() {
		return $this->controllerName;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		return $this->name;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function isLoaded() {
		return $this->loaded;
	}
	
	/**
	* Instantiate the requested controller.
	* 
	*/
	public function route() {
		// Only loading one controller is allowed.
		if (!$this->controller) {
			$this->loaded = true; // Mark as loaded.
			// Import view class.
			require_once CJTOOLBOX_MVC_FRAMEWOK . '/view.inc.php';
			// Instantiate controller!
			$this->controller = CJTController::getInstance($this->controllerName);
		}
		return $this->controller;
	}
	
} // End class.
