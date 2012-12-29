<?php
/*
Plugin Name: CSS & JavaScript Toolbox
Plugin URI: http://css-javascript-toolbox.com
Description: WordPress plugin to easily add custom CSS and JavaScript to individual pages
Version: 6
Author: Wipeout Media 
Author URI: http://css-javascript-toolbox.com

Copyright (c) 2011, Wipeout Media.
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/** CJT Name */
define('CJTOOLBOX_NAME', plugin_basename(dirname(__FILE__)));

/** CJT Text Domain used for localize texts */
define('CJTOOLBOX_TEXT_DOMAIN', CJTOOLBOX_NAME);

/** CJT Absoulte path */
define('CJTOOLBOX_PATH', dirname(__FILE__));

/** Dont use!! @deprecated */
define('CJTOOLBOX_INCLUDE_PATH', CJTOOLBOX_PATH . '/framework'); 

/** Frmaework path */
define('CJTOOLBOX_FRAMEWORK', CJTOOLBOX_INCLUDE_PATH); // Alias to include path!

// Import dependencies
require_once CJTOOLBOX_FRAMEWORK . '/php/includes.class.php';
require_once CJTOOLBOX_FRAMEWORK . '/events/definition.class.php';
require_once CJTOOLBOX_FRAMEWORK . '/events/events.class.php';
require_once CJTOOLBOX_FRAMEWORK . '/events/wordpress.class.php';
require_once CJTOOLBOX_FRAMEWORK . '/events/hookable.interface.php';
require_once CJTOOLBOX_FRAMEWORK . '/events/hookable.class.php';

// Initialize events engine/system!
CJTWordpressEvents::__init(array('hookType' => CJTWordpressEvents::HOOK_ACTION));
CJTWordpressEvents::$paths['subjects']['core'] = CJTOOLBOX_FRAMEWORK . '/events/subjects';
CJTWordpressEvents::$paths['observers']['core'] = CJTOOLBOX_FRAMEWORK . '/events/observers';

/**
* CJT main controller -- represent Wordpress Plugin interface.
* 
* @package CJT
* @author Ahmed Said
* @version 6
*/
class CJTPlugin extends CJTHookableClass {

	/**
	* 
	*/
	const DB_VERSION = '2.0';
		
	/**
	* 
	*/
	const DB_VERSION_OPTION_NAME = 'cjtoolbox_db_version';

	/**
	* 
	*/
	const PLUGIN_REQUEST_ID = 'cjtoolbox';
	
	/**
	* Target controller object.
	* 
	* @var CJTController
	*/
	public $controller;
	
	/**
	* put your comment there...
	* 
	* @var CJTPlugin
	*/
	protected static $instance;
	
	/**
	* put your comment there...
	* 
	* @access public
	* @var CJTPluginOnPrePrcoessRequestWPAction
	*/
	protected $onpreprocessrequest;
	
	/**
	* Triggered when the request is actually served by CJT Plugin.
	* 
	* @access public
	* @var CJTPluginOnPrcoessRequestWPAction
	*/
	protected $onprocessrequest = array(
		'parameters' => array('itsAjaxRequest', 'itsCJTRequest', 'isProcessed')
	);
	
	/**
	* Triggered when the request is about to be checked!
	* 
	* @access public
	* @var CJTPluginOnPrcoessRequestCheckWPAction
	*/
	protected $onprocessrequestcheck = array(
		'parameters' => array('itsAjaxRequest', 'itsCJTRequest')
	);
		
	/**
	* put your comment there...
	* 
	*/
	protected function __construct() {
		parent::__construct();
		// Process request
		$this->preProcessRequest();
		$this->processRequest();
		// Add menu pages!
		if (is_admin()) {
			add_action('admin_menu', array(&$this, 'addMenuPages'));
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function addMenuPages() {
		$menuTitle = __('CSS & Javascript Toolbox', CJTOOLBOX_TEXT_DOMAIN);
		// Blocks Manager page! The only Wordpress menu item we've.
		// All the other forms/grids (e.g templates-manager, etc...) is liked through this pages.
		add_menu_page($menuTitle, $menuTitle, 10, self::PLUGIN_REQUEST_ID, array(&$this->controller, '_doAction'));
		add_submenu_page(self::PLUGIN_REQUEST_ID, null, __('Extensions'), 10, null);
		// Hack Extensions menu item to point to Plugins page!
		$GLOBALS['submenu'][self::PLUGIN_REQUEST_ID][1][2] = admin_url('plugins.php?s=CJTE');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new CJTPlugin();
		}
		return self::$instance;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function loadExtensions() {
		// Load extensions lib!
		require_once 'framework/extensions/extensions.class.php';
		$extensions = new CJTExtensions();
		// Load all extensions!
		$extensions->load();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function preProcessRequest() {
		$this->onpreprocessrequest();
		// Imporsenate request if it for edit post/page.
		if (strpos($_SERVER['REQUEST_URI'], 'wp-admin/post.php') !== false) {
			$_REQUEST['page'] = self::PLUGIN_REQUEST_ID;
			$_REQUEST['controller'] = 'metabox';
		}	
	}
	
	/**
	* Check if the request is for CJT controller,
	* if so create constroller object to server the request.
	* 
	* @return void
	*/
	protected function processRequest() {
		/// Tri-Cases to run a controller! ///
		// case #1. We always have the Coupling controller running unless its ajax request!
		// case #2. If the $_REQUEST['page] == 'cjtoolbox' we'll run another controller!
		// case #3. Edit Post/Page page for metabox!!
		$itsAjaxRequest = (strpos($_SERVER['REQUEST_URI'], '/wp-admin/admin-ajax.php') !== false);
		$itsCJTRequest = isset($_REQUEST['page']) && (strpos($_REQUEST['page'], self::PLUGIN_REQUEST_ID) === 0);
		$this->onprocessrequestcheck($itsAjaxRequest, $itsCJTRequest);
		if ($isProcessed = (!$itsAjaxRequest || $itsCJTRequest)) {
			// Set development parameters!
			if (CJTOOLBOX_ACTIVE_PROFILE == CJTOOLBOX_PROFILE_DEVELOPMENT) {
				$GLOBALS['wpdb']->show_errors(true);
			}
			// Bootstrap the Plugin!
			require_once 'css-js-toolbox.class.php';
			cssJSToolbox::getInstance();
			// Load MVC framework core!
			require_once CJTOOLBOX_MVC_FRAMEWOK . '/model.inc.php';
			require_once CJTOOLBOX_MVC_FRAMEWOK . '/controller.inc.php';
			// Install/Upgrade if needed!
			if ($notInstalled = (get_option(self::DB_VERSION_OPTION_NAME) != self::DB_VERSION)) {
				require 'installer/router.class.php';
				$notInstalled = !CJTInstallerRouter::getInstance()->route();				
			}
			// Load CJT Extensions!
			$this->loadExtensions();
			// run the coupling!
			if (!$notInstalled && !$itsAjaxRequest) {
				CJTController::getInstance('blocks-coupling');
			}
			// Dispath the other controller.
			if ($itsCJTRequest) {
				//CJTView shouldnt be alwaus involved but for now do it!!
				require_once CJTOOLBOX_MVC_FRAMEWOK . '/view.inc.php';
				// If PAGE variable has a controller passed, use it.
				if (count($pageParts = explode('-', $_REQUEST['page'])) > 1) {
					$_REQUEST['controller'] = $pageParts[1];
				}
				// Default controller is "blocks" controller!
				$controller = isset($_REQUEST['controller']) ? $_REQUEST['controller'] : 'blocks';
				$this->controller = CJTController::getInstance($controller);
			}
		}
		$this->onprocessrequest($itsAjaxRequest, $itsCJTRequest, $isProcessed);
	}
	
}// End Class

// Initialize events!
CJTPlugin::define('CJTPlugin');

// Let's Go!
add_action('plugins_loaded', array('CJTPlugin', 'getInstance'), 10, 0);