<?php
/*
Plugin Name: CSS & JavaScript Toolbox
Plugin URI: http://wipeoutmedia.com/wordpress-plugins/css-javascript-toolbox
Description: WordPress plugin to easily add custom CSS and JavaScript to individual pages
Version: V6
Author: Wipeout Media 
Author URI: http://wipeoutmedia.com/wordpress-plugins/css-javascript-toolbox

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

/** CJT version */
define('CJTOOLBOX_VERSION', '6.0');

/** CJT Name */
define('CJTOOLBOX_NAME', plugin_basename(dirname(__FILE__)));

/** CJT Text Domain used for localize texts */
define('CJTOOLBOX_TEXT_DOMAIN', CJTOOLBOX_NAME);

/**
* CJT main controller -- represent Wordpress Plugin interface.
* 
* @package CJT
* @author Ahmed Said
* @version 6
*/
abstract class CJTPlugin {
		                                                                                                                                                                                                                
	/**
	* Target controller object.
	* 
	* @var CJTController
	*/
	public static $controller;
	
	/**
	* put your comment there...
	* 
	* @var cssJSToolbox
	*/
	public static $cssJSToolbox;
	
	/**
	* put your comment there...
	* 
	*/
	public static function addMenuPages() {
		$menuTitle = __('CSS & JavaScript Toolbox', CJTOOLBOX_TEXT_DOMAIN);
		// Blocks Manager page! The only Wordpress menu item we've.
		// All the other forms/grids (e.g templates-manager, etc...) is liked through this pages.
		add_options_page($menuTitle, $menuTitle, 10, 'cjtoolbox', array(&self::$controller, '_doAction'));
	}

	/**
	* put your comment there...
	* 
	*/
	public static function main() {
		// Process request
		self::preProcessRequest();
		self::processRequest();
		// Add menu pages!
		if (is_admin()) {
			add_action('admin_menu', array(__CLASS__, 'addMenuPages'));
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected static function preProcessRequest() {
		// Imporsenate request if it for edit post/page.
		if (strpos($_SERVER['REQUEST_URI'], 'wp-admin/post.php') !== false) {
			$_REQUEST['page'] = 'cjtoolbox';
			$_REQUEST['controller'] = 'metabox';
		}	
	}
	
	/**
	* Check if the request is for CJT controller,
	* if so create constroller object to server the request.
	* 
	* @return void
	*/
	protected static function processRequest() {
		/// Tri-Cases to run a controller! ///
		// case #1. We always have the Coupling controller running unless its ajax request!
		// case #2. If the $_REQUEST['page] == 'cjtoolbox' we'll run another controller!
		// case #3. Edit Post/Page page for metabox!!
		$itsAjaxRequest = (strpos($_SERVER['REQUEST_URI'], '/wp-admin/admin-ajax.php') !== false);
		$itsCJTRequest = isset($_REQUEST['page']) && ($_REQUEST['page'] == 'cjtoolbox');
		if (!$itsAjaxRequest || $itsCJTRequest) {
			// Import CJT Core class,
			require_once 'css-js-toolbox.class.php';
			self::$cssJSToolbox = cssJSToolbox::getInstance();
			// The following dependencies is always needed!
			require_once CJTOOLBOX_MVC_FRAMEWOK . '/model.inc.php';
			require_once CJTOOLBOX_MVC_FRAMEWOK . '/controller.inc.php';
			// run the coupling!
			if (!$itsAjaxRequest) {
				CJTController::getInstance('blocks-coupling');
			}
			// Dispath the other controller.
			if ($itsCJTRequest) {
				//CJTView shouldnt be alwaus involved but for now do it!!
				require_once CJTOOLBOX_MVC_FRAMEWOK . '/view.inc.php';
				// Default controller is "blocks" controller!
				$controller = isset($_REQUEST['controller']) ? $_REQUEST['controller'] : 'blocks';
				self::$controller = CJTController::getInstance($controller);
			}
		}
	}
	
}// End Class

// Let's Go!
add_action('plugins_loaded', array('CJTPlugin', 'main'));