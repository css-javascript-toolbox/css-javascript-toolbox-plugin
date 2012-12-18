<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTExtensions {
	
	/**
	* 
	*/
	const CACHE_OPTION_NAME = 'cjt_extensions';
	
	/**
	* 
	*/
	const LOAD_METHOD = 'getInvolved';
	
	/**
	* 
	*/
	const PREFIX = 'cjte-';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $extensions;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $loadMethod;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $prefix;
	
	/**
	* put your comment there...
	* 
	* @param mixed $className
	*/
	 public function __autoload($className) {
		// Load only classed defined on the list!
		if (isset($this->extensions[$className])) {
			// Import class file!
			require_once $this->extensions[$className]['runtime']['classFile'];
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @param mixed $loadMethod
	* @return CJTExtensions
	*/
	public function __construct($prefix = self::PREFIX, $loadMethod = self::LOAD_METHOD) {
		$this->prefix = $prefix;
		$this->loadMethod = $loadMethod;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function __destruct() {
		spl_autoload_unregister(array($this, '__autoload'));
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $reload
	* @return CJTExtensions
	*/
	public function getExtensions($reload = false) {
		// Get cached extensions or cache then if not yest cached!
		if ($reload || !($extensions = get_option(self::CACHE_OPTION_NAME, array()))) {
			$extensions = array();
			$activePlugins = wp_get_active_and_valid_plugins();
			foreach ($activePlugins as $file) {
				$pluginDir = dirname($file);
				$pluginName = basename($pluginDir);
				// Any plugin with our prefix is a CJT extension!
				if (strpos($pluginName, $this->prefix) === 0) {
					// CJT Extsnsion must has the definition XML file!
					$xmlFile = "{$pluginDir}/{$pluginName}.xml";
					if (file_exists($xmlFile)) {
						// Get Plugin primary data!
						$extension = array();
						$extension['file'] = basename($file);
						// Its useful to use ABS path only at runtime as it might changed as host might get moved.
						$extension['dir'] = str_replace((ABSPATH . PLUGINDIR . '/'), '', $pluginDir) ;
						$extension['name'] = $pluginName;
						// Cache XML file.
						$extension['definition']['raw'] = file_get_contents($xmlFile);
						// Read Basic XML Definition!
						$definitionXML = new SimpleXMLElement($extension['definition']['raw']);
						$attrs = $definitionXML->attributes();
						$extension['definition']['primary']['loadMethod'] = (string) $attrs->loadMethod;
						// Add to list!
						$extensions[((string) $attrs->class)] = $extension;
						$definitionXML = null;
					}
				}
			}
			// Update the cache Cache!
			// ----update_option(self::CACHE_OPTION_NAME, $extensions);
		}
		$this->extensions = $extensions;
		// Chaining
		return $this->extensions;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function load() {
		// Load all CJT extensions!
		foreach ($this->getExtensions() as $class => $extension) {
			// Initialize common vars!
			$callback = array($class, $this->loadMethod);
			$pluginPath = ABSPATH . PLUGINDIR . "/{$extension['name']}";
			// If auto load is speicifd then import class file and bind events.
			if ($extension['definition']['primary']['loadMethod'] == 'auto') {
				// Set runtime variables.
				$this->extensions[$class]['runtime']['classFile'] = "{$pluginPath}/{$extension['name']}.class.php";
				// Bind events!
				$definitionXML = new SimpleXMLElement($extension['definition']['raw']);
				foreach ($definitionXML->getInvolved->event as $event) {
					CJTPlugin::on((string) $event->attributes()->type, $callback);
				}
			}
			else { // If manual load specified just 
				call_user_func($callback);
			}
		}
		// Auto load CJT extensions files when requested.
		spl_autoload_register(array($this, '__autoload'));
	}
	
} // End class.
