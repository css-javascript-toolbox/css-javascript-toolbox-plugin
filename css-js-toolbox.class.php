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
class cssJSToolbox extends CJTHookableClass {

	/**
	* 
	*/
	const CJT_WEB_SITE_DOMAIN = 'css-javascript-toolbox.com';
	
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
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $ongettext = array('parameters' => array('text'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $onimport = array('parameters' => array('vpaths'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $oninstantiate = array('parameters' => array('instance'));
		
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onloadconfiguration = array('parameters' => array('configuration'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onloaddbdriver  = array('parameters' => array('dbdriver'));

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $onresolvepath = array('parameters' => array('path', 'vpath'));
		
	/**
	* Initialize Plugin. 
	* 
	* @return void
	*/
	protected function __construct() {
		// Initialize hookable!
		parent::__construct();
		// Load configuration.
		self::$config = $this->onloadconfiguration(require(self::resolvePath('configuration.inc.php')));
		// Initialize vars!
		self::import('framework:db:mysql:queue-driver.inc.php');
		$this->dbDriver = $this->onloaddbdriver(new CJTMYSQLQueueDriver($GLOBALS['wpdb']));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getCJTWebSiteURL($path = null) {
		$domain = self::CJT_WEB_SITE_DOMAIN;	
		return "http://{$domain}/{$path}";
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getDBDriver() {
		return $this->dbDriver;	
	}
	
	/**
	* Get CJT Plugin object.
	* 
	* @return cssJSToolbox
	*/
	public static function & getInstance() {
		if (!self::$instance) {
			self::$instance = self::trigger('cssJSToolbox.instantiate', (new cssJSToolbox()));
		}
		return self::$instance;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getSecurityToken() {
		return wp_create_nonce(CJTController::NONCE_ACTION);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $text
	*/
	public static function getText($text) {
		// Make sure to don't use $this while calling!
		// $this might be an object other than CssJSToolbox!
		return self::__callStatic('cssJSToolbox.ongettext', array(__($text, CJTOOLBOX_TEXT_DOMAIN)));
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $path
	*/
	public static function getURI($vPath, $uriBase = null) {
		// PHP wrapper however its not imlpemented as wrapper yet
		// because this is not the point right now!
		if (strpos($vPath, 'extension://') === 0) {
			// Expression for getting plugin/extension name!
			$exp = '/^extension\:\/\/([^\/]+)/';
			preg_match($exp, $vPath, $extensionPath);
			// Get base URI + removing extension:// wrapper!
			$uriBase =  plugins_url($extensionPath[1]);
			$uri = self::getURI(preg_replace($exp, '', $vPath), $uriBase);
		} 
		else {
			// Translate Virtual path to real path.
			$path = str_replace(':', '/', $vPath);
			// Get full URI.
			if (!isset($uriBase)) {
				$uriBase = plugin_dir_url(__FILE__);
			}
			$uri = "{$uriBase}{$path}";			
		}
		return $uri;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function import() {
		// Initialize!
		$params = func_get_args();
		// Allow vriables list parameters.
		$vPaths = self::trigger('cssJSToolbox.import', $params);
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
		// Resolve CJT extensions path
		if (strpos($vPath, 'extension://') === 0) {
			// Remove extension wrapper
			$vPath = str_replace('extension://', '', $vPath);
			// Point to plugin directory
			$base = WP_PLUGIN_DIR;
		}
		// Replace all :'s with /'s.
		$path = str_replace(':', '/', $vPath);
		$path = "{$base}/{$path}";
		return self::trigger('cssJSToolbox.resolvepath', $path, $vPath);
	}
	
}// End Class

// Hookable!
cssJSToolbox::define('cssJSToolbox', array('hookType' => CJTWordpressEvents::HOOK_FILTER));