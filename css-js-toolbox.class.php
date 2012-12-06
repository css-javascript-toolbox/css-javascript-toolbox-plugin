<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/** Change active profile to development state */
define('CJTOOLBOX_PROFILE_DEVELOPMENT', 'development');

/** Change active profile to production state */
define('CJTOOLBOX_PROFILE_PRODUCTION', 'production');

/** Set development state */
define('CJTOOLBOX_ACTIVE_PROFILE', CJTOOLBOX_PROFILE_DEVELOPMENT);

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

/**
* CJT Core class.
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
	* put your comment there...
	* 
	* @var mixed
	*/
	private $dbDriver;
	
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
		// Load configuration.
		self::$config = require(self::resolvePath('configuration.inc.php'));
		// Initialize vars!
		self::import('framework:db:mysql:queue-driver.inc.php');
		$this->dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getDBDriver() {
		return $this->dbDriver;	
	}
	
	/**
	* Get CJT Plugin object.
	* 
	* @return cssJSToolbox
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