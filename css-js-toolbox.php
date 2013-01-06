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

/** Access Points  path */
define('CJTOOLBOX_ACCESS_POINTS', CJTOOLBOX_PATH . '/access.points');

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
	const VERSION = '6.0'	;
	
	/**
	* 
	*/
	const DB_VERSION_OPTION_NAME = 'cjtoolbox_db_version';

	/**
	* 
	*/
	const PLUGIN_REQUEST_ID = 'cjtoolbox';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $accessPoints;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $installed;
	
	/**
	* put your comment there...
	* 
	* @var CJTPlugin
	*/
	protected static $instance;
		
	/**
	* put your comment there...
	* 
	*/
	protected function __construct() {
		parent::__construct();
		// Read vars!
		$this->installed = ((get_option(self::DB_VERSION_OPTION_NAME)) == self::DB_VERSION);
		// Apply blocks to the request.
		$this->apply();
		// Define access points.
		if (is_admin()) {
			// Import dependencies.
			require_once 'framework/wordpress/access-point.class.php';
			require_once 'framework/wordpress/definer.class.php';
			// Define access points!
			$this->accessPoints = CJTAccessPointsDefiner::getInstance('CJT', CJTOOLBOX_ACCESS_POINTS)->define();
		}
	}
	
	/**
	* Apply blocks assigned to the request!
	* 
	* @return void
	*/
	protected function apply() {
		// Bootstrap the Plugin!
		require_once 'css-js-toolbox.class.php';
		cssJSToolbox::getInstance();
		// Load MVC framework core!
		require_once CJTOOLBOX_MVC_FRAMEWOK . '/model.inc.php';
		require_once CJTOOLBOX_MVC_FRAMEWOK . '/controller.inc.php';
		// Load CJT Extensions!
		$this->loadExtensions();
		// Run the coupling only if the installer runs before!
		if ($this->installed) {
			CJTController::getInstance('blocks-coupling');
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getAccessPoints() {
		return $this->accessPoints;
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
	public function isInstalled() {
		return $this->installed;	
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
	
}// End Class

// Initialize events!
CJTPlugin::define('CJTPlugin');

// Let's Go!
add_action('plugins_loaded', array('CJTPlugin', 'getInstance'));