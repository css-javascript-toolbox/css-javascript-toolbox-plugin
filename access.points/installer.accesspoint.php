<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTInstallerAccessPoint extends CJTAccessPoint {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $stopNotices = false;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Initialize parent.
		parent::__construct();
		// Set name!
		$this->name = 'installer';
	}
	/**
	* put your comment there...
	* 
	*/
	protected function doListen() {
		// If not installed and not in manage page display admin notice!
		if (!CJTPlugin::getInstance()->isInstalled() && $this->hasAccess()) {
			add_action('admin_notices', array(&$this, 'notInstalledAdminNotice'));
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function installationPage() {
		if ($this->hasAccess()) {
			// Set as connected object!
			$this->connected();
			// Set controller internal parameters.
			$_REQUEST['view'] = 'installer/install';
			// create controller.
			return $this->route()
			// Set Action
			->setAction('install');
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function notInstalledAdminNotice() {
		// Show Not installed admin notice only 
		// if there is no access point processed/connected the request
		if (!$this->stopNotices)	{
			// Set MVC request parameters.
			$_REQUEST['view'] = 'installer/notice';
			// Instantiate installer cotroller and fire notice action!
			$this->route()
			// Set action name.
			->setAction('notInstalledNotice')
			// Fire action!
			->_doAction();
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function stopNotices()	{
		// Do not show admin notcies!
		$this->stopNotices = true;	
	}
	
} // End class.

// Hookable!
CJTInstallerAccessPoint::define('CJTInstallerAccessPoint', array('hookType' => CJTWordpressEvents::HOOK_FILTER));