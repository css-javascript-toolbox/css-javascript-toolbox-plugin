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

/** Change active profile to development state */
define('CJTOOLBOX_PROFILE_DEVELOPMENT', 'development');

/** Change active profile to production state */
define('CJTOOLBOX_PROFILE_PRODUCTION', 'production');

/** Set development state */
define('CJTOOLBOX_ACTIVE_PROFILE', CJTOOLBOX_PROFILE_DEVELOPMENT);

/** CJT version */
define('CJTOOLBOX_VERSION', '6.0');

/** CJT Name */
define('CJTOOLBOX_NAME', plugin_basename(dirname(__FILE__)));

/** CJT Text Domain used for localize texts */
define('CJTOOLBOX_TEXT_DOMAIN', CJTOOLBOX_NAME);

/** CJT Absoulte path */
define('CJTOOLBOX_PATH', dirname(__FILE__));

/** Dont use!! @deprecated */
define('CJTOOLBOX_INCLUDE_PATH', CJTOOLBOX_PATH . '/framework'); 

/** Frmaework path */
define('CJTOOLBOX_FRAMEWORK', CJTOOLBOX_INCLUDE_PATH); // Alias to include pa

/** MVC library framework */
define('CJTOOLBOX_MVC_FRAMEWOK', CJTOOLBOX_INCLUDE_PATH . '/mvc');

/** Models dir path */
define('CJTOOLBOX_MODELS_PATH', CJTOOLBOX_PATH . '/models');

/** Tables dir path */
define('CJTOOLBOX_TABLES_PATH', CJTOOLBOX_PATH . '/tables');

/** Models views path */
define('CJTOOLBOX_VIEWS_PATH', CJTOOLBOX_PATH . '/views');

/** Views controllers path */
define('CJTOOLBOX_CONTROLLERS_PATH', CJTOOLBOX_PATH . '/controllers');

/** URI to CJT Plugin dir */
define('CJTOOLBOX_URL', WP_PLUGIN_URL . '/' . CJTOOLBOX_NAME );

/** URI to CJT Views directory */
define('CJTOOLBOX_VIEWS_URL', CJTOOLBOX_URL . '/views');

/** HTML Components URI */
define('CJTOOLBOX_HTML_CONPONENTS_URL', CJTOOLBOX_URL . '/framework/html/components');

// If we're in development process the lab class.
if (CJTOOLBOX_ACTIVE_PROFILE == CJTOOLBOX_PROFILE_DEVELOPMENT) {
	@include '.dev/development-lab.php';
}

// For any reason this file included twice make sure we're still fine!
if (!class_exists('cssJSToolbox')) {
	
	/**
	* CJT main controller -- represent Wordpress Plugin interface.
	* 
	* @package CJT
	* @author Original Developer
	* @version 0.3
	*/
	class cssJSToolbox {

		/**
		* put your comment there...
		* 
		* @todo remove this and use configuration instead
		* 
		* @var mixed
		*/
		public static $config = null;
		                                                                                                                                                                                                                  
		/**
		* Target controller object.
		* 
		* @var CJTController
		*/
		private static $controller = null;
		
		/**
		* Controllers mapping.
		* 
		* @var array
		*/
		public static $controllers = array(
			'blocks-coupling' => array(
				'identifications' => array('.*'),
				'controller' => 'blocks-coupling',
				'model' => 'blocks',
			),
			'blocks' => array(
				'identifications' => array('page=cjtoolbox'),
				'controller' => 'blocks',
				'model' => 'blocks',
				'view' => 'blocks/manager',
				'menu' => array(
					'slug' => 'cjtoolbox',
					'title' => 'CSS & JavaScript Toolbox',
					'pageTitle' => 'CSS & JavaScript Toolbox',
					'capabilities' => '10'
				),
			),
			'metabox' => array(
				'identifications' => array('\/wp-admin\/post\.php\?post=(\d+)&action=edit', 'controller=metabox'),
				'controller' => 'metabox',
				'dependencies' => array(
					 'controller-ajax',
				),
			),
			'settings' => array(
				'identifications' => array('controller=settings'),
				'controller' => 'settings',
				'model' => 'settings',
				'dependencies' => array(
					 'controller-ajax',
				),
			),
			'blocks-ajax' => array(
				'identifications' => array('controller=blocks-page-ajax'),
				'controller' => 'blocks-ajax',
				'model' => 'blocks',
				'dependencies' => array(
					 'controller-ajax',
				),
			),
			'block-ajax' => array(
				'identifications' => array('controller=block-ajax'),
				'controller' => 'block-ajax',
				'model' => 'blocks',
				'dependencies' => array(
					'controller-ajax',
				),
			),
			'blocks-backups' => array(
				'identifications' => array('controller=blocks-backups'),
				'controller' => 'blocks-backups',
				'model' => 'blocks-backups',
				'dependencies' => array(
					'controller-ajax',
				),
			),
			'templates-lookup' => array(
				'identifications' => array('controller=templates-lookup'),
				'controller' => 'templates-lookup',
				'model' => 'templates',
				'dependencies' => array(
					'controller-ajax',
				),
			),
			'templates-manager' => array(
				'identifications' => array('controller=templates-manager'),
				'controller' => 'templates-manager',
				'model' => 'templates-manager',
				'view' => 'templates/manager',
				'dependencies' => array(
					'controller-ajax',
				),
			),
		);
		
		/**
		* Reference of CJT Plugin object.
		* 
		* @var cssJSToolbox
		*/
		public static $instance = null;
		
		/**
		* Initialize Plugin. 
		* 
		* @return void
		*/
		protected function __construct() {
			/** @todo Use self::$configuration object instead by loading the configuration from the xml file */
			self::$config = (object) array(
				'database' => (object) array(
					'tables' => (object) array(
						'blocks' => 'cjtoolbox_blocks',
						'blockPins' => 'cjtoolbox_block_pins',
						'backups' => 'cjtoolbox_backups',
						'templates' => 'cjtoolbox_templates',
						'authors' => 'cjtoolbox_authors',
						'templateDependencies' => 'cjtoolbox_template_dependencies',
						'blockTemplates' => 'cjtoolbox_block_templates',
					),
				),
			);
			// Start this plugin once all other plugins are fully loaded.
			add_action('plugins_loaded', array(&self::$instance, 'init'));
		}
		
		/**
		* Add CJT admin pages.
		* 
		* Callback for (admin_menu)
		*/
		public function addMenuPages() {
			$controllers =& self::$controllers;
			foreach ($controllers as &$controller) {
				// Not all backend controller has backend page.
				if (isset($controller['menu'])) {
					$menu = $controller['menu'];
					$controller['pageHook'] = add_options_page($menu['title'], $menu['pageTitle'], $menu['capabilities'], $menu['slug'], array(&self::$controller, '_doAction'));
				}
			}
		}
		
		/**
		* Check if the request is for CJT controller,
		* if so create constroller object to server the request.
		* 
		* @return void
		*/
		protected function dispatchControllers() {
			$controllerFound = false;
			$controllers =& self::$controllers;
			$request = $_SERVER['REQUEST_URI'];
			// Identify request controller.
			foreach ($controllers as &$controller) {
				$identificatons = $controller['identifications'];
				// Check REQUEST_URL against controllers regular expressions.
				foreach ($identificatons as $identification) {
					$identity = array();
					// Its possible to identifing multiple controllers in the same time.
					// create them all, no break statment.
					if (preg_match("/{$identification}/", $request, $identity) ) {
						// Import MCV framwork
						if (!$controllerFound) {
							$controllerFound = true;
							// Single check is better than 4! Don't import files if already imported.
							require_once CJTOOLBOX_INCLUDE_PATH . '/exceptions.inc.php';
							require_once CJTOOLBOX_MVC_FRAMEWOK . '/view.inc.php';
							require_once CJTOOLBOX_MVC_FRAMEWOK . '/controller.inc.php';
						}
						// Import controller dependencies.
						if (isset($controller['dependencies'])) {
							foreach ($controller['dependencies'] as $dependencyName) {
							  $file = "{$dependencyName}.inc.php";
							  require_once CJTOOLBOX_MVC_FRAMEWOK . "/{$file}";
							}
						}
						// Import controller file.
						$pathToControllers = CJTOOLBOX_CONTROLLERS_PATH;
						$controllerFile = "{$pathToControllers}/{$controller['controller']}.php";
						require $controllerFile;
						// Create controller object.
						$controllerClass = CJTController::getClassName($controller['controller'], 'controller');
						// Push identify/matched patterns into the controller infor structure.
						$controller['identity'] = $identity;
						self::$controller = new $controllerClass($controller);
					}
				}
			}
			return $controllerFound;
		}
		
		/**
		* Get CJT Plugin object.
		* 
		* @return cssJSToolbox.
		*/
		public static function getInstance() {
			if (!self::$instance) {
				self::$instance = new cssJSToolbox();
			}
			return self::$instance;
		}
		
		/**
		* put your comment there...
		* 
		* @param mixed $text
		*/
		public function getText($text) {
			return __($text, CJTOOLBOX_TEXT_DOMAIN);
		}
		
		/**
		* put your comment there...
		* 
		* @param mixed $path
		*/
		public static function getURI($vPath) {
			// Translate Virtual path to real path.
			$path = str_replace(':', '/', $vPath);
			// Get full URI.
			$uri = plugin_dir_url(__FILE__) . "{$path}";
			return $uri;
		}
		
		/**
		* put your comment there...
		* 
		*/
		public static function import() {
			// Allow vriables list parameters.
			$vPaths = func_get_args();
			foreach ($vPaths as $vPath) {
				// Import file.
				require_once self::resolvePath($vPath);
			}
		}
		
		/**
		* 
		* 
		* Callback for plugins_loaded.
		*/
		public function init() {
			// Dispatch to controller.
			$this->dispatchControllers();
			// Initialize.
			if (is_admin()) {
				// Load Plugin translation.
				load_plugin_textdomain(CJTOOLBOX_TEXT_DOMAIN, null, 'css-javascript-toolbox/langs');
				// Add menu page.
				add_action('admin_menu', array(&$this, 'addMenuPages'));
			}
		}
		
		/**
		* put your comment there...
		* 
		* @param mixed $vPath
		* @param mixed $base
		*/
		public static function resolvePath($vPath, $base = CJTOOLBOX_PATH) {
			// Replace all :'s with /'s.
			$path = str_replace(':', '/', $vPath);
			$path = "{$base}/{$path}";
			return $path;
		}
		
	}// End Class

	// Let's Go!
	cssJSToolbox::getInstance();
}