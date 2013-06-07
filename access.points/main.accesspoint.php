<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTMainAccessPoint extends CJTAccessPoint {
	
	/**
	* put your comment there...
	*  
	* @var mixed
	*/
	protected static $instance;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize Access Point base!
		parent::__construct();
		// Set access point name!
		$this->name = 'main';
		// Needed for calling from nuinstall static method!
		self::$instance = $this;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// Register uninstall hook!
		if (CJTPlugin::getInstance()->isInstalled()) {
			// Wordpress need STATIC method!
			register_uninstall_hook(CJTOOLBOX_PLUGIN_FILE, array(__CLASS__, 'uninstall'));	
		}
		// If not in uninstall state then plugins_loaded hook
		// used to run the plugin!
		add_action('plugins_loaded', array(&$this, 'main'));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function main() {
		// Run the coupling only if installed!
		if (CJTPlugin::getInstance()->isInstalled()) {
			$this->controllerName = 'blocks-coupling';
			$this->route(false);
		}
		// Run all the aother access points!
		CJTPlugin::getInstance()->listen();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function uninstall() {
		// For the uninstaller to be run eraseData setting must be enabled.
		cssJSToolbox::import('models:settings:uninstall.php');
		$settings = new CJTSettingsUninstallPage();
		if ($settings->eraseData) {
			// Get the only instance we've for the main access point!
			$mainAccessPointObject = self::$instance;
			// Load default controller!
			$mainAccessPointObject->controllerName = 'default';
			$controller = $mainAccessPointObject->route(false)
			// Fire uninstall action!
			->setAction('uninstall')
			->_doAction();
		}
	}
	
} // End class.

// Hookable!
CJTMainAccessPoint::define('CJTMainAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));