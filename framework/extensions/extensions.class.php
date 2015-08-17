<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTExtensions extends CJTHookableClass {
	
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
	const PREFIXS = 'cjte-,css-javascript-toolbox-';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $defDocs;
	
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
	protected $file2Classmap;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $incompatibilies;
	
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
	protected $onautoload = array('parameters' => array('file', 'class'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onbindevent = array('parameters' => array('event', 'callback'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $ondetectextension  = array('parameters' => array('extension'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $ongetactiveplugins = array('parameters' => array('plugins'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onload = array('parameters' => array('params'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onloadcallback = array('parameters' => array('callback'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onloaddefinition = array('parameters' => array('definition'));
	
	/***
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $ontregisterautoload = array('parameters' => array('callback'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onreloadcacheparameters = array('parameters' => array('params'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onloaded = array(
		'hookType' => CJTWordpressEvents::HOOK_ACTION,
		'parameters' => array('class', 'extension', 'definition', 'result')
	);
	
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
			$classFile = $this->onautoload($this->extensions[$className]['runtime']['classFile'], $className);
			// Import class file!
			require_once $classFile;
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @param mixed $loadMethod
	* @return CJTExtensions
	*/
	public function __construct($prefix = self::PREFIXS, $loadMethod = self::LOAD_METHOD) {
		// Hookable!
		parent::__construct();
		// Initializing!
		$this->prefix = explode( ',', $prefix );
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
	public function & getExtensions($reload = false) {
		// Get cached extensions or cache then if not yest cached!
		extract($this->onreloadcacheparameters(compact('reload')));
		if ($reload || (!($extensions = $this->extensions) && !($extensions = get_option(self::CACHE_OPTION_NAME, array())))) {
			//Resting!
			$this->file2Classmap = array();
			$extensions = array();
			# Get all active plugins
			$activePlugins = wp_get_active_and_valid_plugins();
			# Get Network plugins
			if (is_multisite()) {
				$activePlugins = array_merge(wp_get_active_network_plugins(), $activePlugins);
			}
			// filter all installed Plugins to fetch all out Extensions!
			$activePlugins = $this->ongetactiveplugins($activePlugins);
			foreach ($activePlugins as $file) {
				$pluginDir = dirname($file);
				$pluginName = basename($pluginDir);
				// Any plugin with our prefix is a CJT extension!
				if ( ( strpos( $pluginName, $this->prefix[0] ) === 0 ) || 
					 	 ( strpos( $pluginName, $this->prefix[1] ) === 0 ) ) {
					// CJT Extsnsion must has the definition XML file!
					// First try for Edition-Specific file
					// if not exists try the generic one.
					$xmlFile = "{$pluginDir}/{$pluginName}.xml";
					if (file_exists($xmlFile)) {
						// Get Plugin primary data!
						$extension = array();
						$extension['pluginFile'] = $file;
						$extension['file'] = basename($file);
						$extension['defFile'] = basename($pluginDir) . DIRECTORY_SEPARATOR . basename($xmlFile);
						// Its useful to use ABS path only at runtime as it might changed as host might get moved.
						$extension['dir'] = basename($pluginDir);
						$extension['name'] = $pluginName;
						// Cache XML file.
						$extension['definition']['raw'] = file_get_contents($xmlFile);
						// Filer!
						$extension = $this->ondetectextension($extension);
						// Read Basic XML Definition!
						$definitionXML = $this->onloaddefinition(new SimpleXMLElement($extension['definition']['raw']));
						$attrs = $definitionXML->attributes();
						$extension['defDoc'] = $definitionXML;
						$extension['definition']['primary']['loadMethod'] = (string) $attrs->loadMethod;
						$extension['definition']['primary']['requiredLicense'] = (string) $definitionXML->license;
						$className = ((string) $attrs->class);
						// Add to list!
						$extensions[$className] = $extension;
						// Map Plugin FILE-2-CLASS name!
						$this->file2Classmap["{$extension['dir']}/{$extension['file']}"] = $className;
					}
				}
			}
		}
		$this->extensions = $this->onload($extensions);
		// Chaining
		return $this->extensions;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function load() {
		// Initialize.
		$frameworkVersion = new CJT_Framework_Version_Version(CJTPlugin::FW_Version);
		// Auto load CJT extensions files when requested.
		spl_autoload_register($this->ontregisterautoload(array($this, '__autoload')));
		// Load all CJT extensions!
		foreach ($this->getExtensions() as $class => $extension) {
			// Filters!
			extract($this->onload($extension, compact('class', 'extension')));
			// Build Extension plugin path
			$pluginPath = ABSPATH . PLUGINDIR . "/{$extension['name']}";
			// Set runtime variables.
			$this->extensions[$class]['runtime']['classFile'] = "{$pluginPath}/{$extension['name']}.class.php";
			// Load definition.
			$definitionXML = $extension['defDoc'];
			// Extensions below version 1.0 use static classes
			// while version 1.0 and up use objects
			if (((string) $definitionXML->attributes()->version) == '1.0') {
				// Instantiate extension object
				$extensionObject = new $class($extension);
				// Hold extension object
				$extension['exObject'] = $extensionObject;
				// Obejct callback
				$callback = array($extensionObject, $this->loadMethod);
			}
			else {
				# Static callback
				$callback = array($class, $this->loadMethod);
			}
			// Callback filter
			$callback = $this->onloadcallback($callback);
			// If auto load is speicifd then import class file and bind events.
			if ($extension['definition']['primary']['loadMethod'] == 'auto') {
				// If frameworkVersion is not provided assume its 0 (Older version)
				// before frameworkversion chech even supported.
				// otherwise compare it with current frameworkversion
				// If the version MAJOR is different current
				// then its incompatible.
				$extensionVer = new CJT_Framework_Version_Version((int) ((string) $definitionXML->attributes()->requireFrameworkVersion));
				if ($frameworkVersion->getMajor() < $extensionVer->getMajor()) {
					// Detects which requird updates CJT or the Extension itself.
					$extension['incompatibleMessage']['msg'] = cssJSToolbox::getText('Extension is required CJT Framework Version higher than currently installed, CJT need to get updated!!!');
					$extension['incompatibleMessage']['flag'] = cssJSToolbox::getText('Aborted');
					// Add to incomaptibility list.
					$this->incompatibilies[$pluginPath] = $extension;
				}
				else {
					# Detect extensions required old Framework
					if ($frameworkVersion->getMajor() > $extensionVer->getMajor()) {
						$extension['incompatibleMessage']['msg'] = cssJSToolbox::getText('Extension is required old CJT Framework Version than the installed. This extension might need to get update. Please check if this extension is currently behaving correctly!!!');
						$extension['incompatibleMessage']['flag'] = cssJSToolbox::getText('Ignored');
						// Add to incomaptibility list.
						$this->incompatibilies[$pluginPath] = $extension;
					}
					// Bind events for compatible extensions.
					foreach ($definitionXML->getInvolved->event as $event) {
						// filter!
						extract($this->onbindevent(compact('event', 'callback')));
						// Bind!
						CJTPlugin::on((string) $event->attributes()->type, $callback);
					}
				}
			}
			else { // If manual load specified just 
				if (class_exists($class)) { // Make sure the class is loaded!
					$this->onloaded($class, $extension, $definitionXML, call_user_func($callback));
				}
			}
		}
		if (!empty($this->incompatibilies)) {
			// Hook for processing incomaptible extensions.
			add_action('admin_notices', array(& $this, 'processIncompatibles'));
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $pluginName
	*/
	public function & getDefDoc($pluginName) {
		# Find if cached or create it
		if (!isset($this->defDocs[$pluginName])) {
			// Add CJT Def doc to def docs list
			$pluginXMLFile = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $pluginName . DIRECTORY_SEPARATOR . "{$pluginName}.xml";
			// Extension might removed while its license key still in the database
			// Make sure the extension still exists
			if (file_exists($pluginXMLFile)) {
				$this->defDocs[$pluginName] = new SimpleXMLElement(file_get_contents($pluginXMLFile));	
			}
			else {
				# Return null when extension is absent!
				$this->defDocs[$pluginName] = null;
			}
		}
		return $this->defDocs[$pluginName];
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getFiles2ClassesMap() {
		return $this->file2Classmap;	
	}
	
	/**
	* put your comment there...
	* 
	* @TODO: REMOVE HTML-MARKUP. CJT PLUGIN NEVER WRITE MARKUP IMIXED WITH HTML. ITS VERY BAD PROGRAMMING PRACTICE. THIS WILL BE REMOVED NEXT TIME AS WE IN RUSH!!!
	*/
	public function processIncompatibles() {
		// Proces only if in CJT page.
		if (!preg_match('/\/plugins\.php|page\=cjtoolbox/', $_SERVER['REQUEST_URI'])) {
			return;
		}
		// INitialize.
		$message = cssJSToolbox::getText('CJT detects incompatible installed extensions, listed below with status message for every extension:');
		$list = '';
		// For every compatible extension add
		// an list item with details 
		// if there is an update available or provide
		// a direct link to CJT website if no upgrade is available.
		// Upgrade wont be available in case no license key is activated!
		foreach ($this->incompatibilies as $class => $extension) {
			// Show details.
			$pluginInfo = get_plugin_data($extension['pluginFile']);
			// List item Markup
			$list .= "<li><a target='_blank' href='{$pluginInfo['PluginURI']}'>{$pluginInfo['Name']}</a> (Status: {$extension['incompatibleMessage']['flag']}, Message: {$extension['incompatibleMessage']['msg']})</li>\n";
		}
		// Output full message.
		// TODO: BAD PRACTICE1!!!!! NEVER MIX HTML WITH PHP, JUST TEMPORARY1!!!
		echo "<div class='cjt-incomaptible-extensions-notice updated' style='font-size:14px;font-weight:bold;padding-top:11px'>
						<span>{$message}</span>
						<ul style='list-style-type: circle;padding-left: 27px;font-size: 12px;'>{$list}</ul>
					</div>";
	}

} // End class.


// Hookable!
CJTExtensions::define('CJTExtensions', array('hookType' => CJTWordpressEvents::HOOK_FILTER));