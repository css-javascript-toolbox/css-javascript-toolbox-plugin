<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTExtensionsPluginsListView extends CJTView {
	
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
	protected $listTypeName;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $securityToken;
	
	/**
	* put your comment there...
	* 
	* @param mixed $info
	* @return CJTInstallerNoticeView
	*/
	public function __construct($info) {
		// CJTView class!
		parent::__construct($info);
		// Enqueue Styles & Scripts.
		add_action('admin_print_styles', array(__CLASS__, 'enqueueStyles'));
		add_action('admin_print_scripts', array(__CLASS__, 'enqueueScripts'));
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $parent
	*/
	public function activateExtensionsMenuItem($parent) {
		// Hack Wordpress menu to select CSS & Javascript root menu item
		// and the Extensions child menu item!
		// We just hack get_admin_page_parent() function!
		$GLOBALS['typenow'] = CJTPlugin::PLUGIN_REQUEST_ID;
		$GLOBALS['pagenow'] = CJTPlugin::PLUGIN_REQUEST_ID;
		$GLOBALS['plugin_page'] = $GLOBALS['submenu'][CJTPlugin::PLUGIN_REQUEST_ID][CJTExtensionsAccessPoint::MENU_POSITION_INDEX][2];
		// We use this filter as (ACTION) not change in the return value!
		return $parent;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $links
	*/
	public function addExtensionActions($links, $file) {
		$pluginsFilesMap =& $this->extensions->getFiles2ClassesMap();
		$extensions =& $this->extensions->getExtensions();
		// If its a CJT extension!
		if (isset($pluginsFilesMap[$file])) {
			// Get extension data!
			$class = $pluginsFilesMap[$file];
			$extension = $extensions[$class];
			// We only work with extensions that required license key!
			if ($extension['definition']['primary']['requiredLicense']) {
				// Load license
				$definition = new SimpleXMLElement($extension['definition']['raw']);
				$component['pluginBase'] = $file;
				// Get extension title
				// Use first license type name for old extensions that doesnt
				// provide title attribute
				$defTitle = (string) $definition->license->attributes()->title;
				$component['title'] = $defTitle ? $defTitle : ((string) $definition->license->name[0]);
				// Get action Markup!
				$links['license-key'] = $this->getTemplate('default_setup_action', array('component' => $component));
			}
		}
		return $links;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	public function display($tpl = null) {
		// Get model object!
		$model = $this->getModel('extensions');
		// Some time we work as aspecific extensions page or geneal Wordpress plugins page!
		$this->securityToken = cssJSToolbox::getSecurityToken();
		$this->listTypeName = $model->getListTypeName();
		$this->extensions = CJTPlugin::getInstance()->extensions();
		if ($this->listTypeName == 'extensions') {
			// Select Extensions menu item instead of Wordpress Plugins item!
			add_filter('parent_file', array(&$this, 'activateExtensionsMenuItem'));
		}
		// Output internal HTML used by JS!
		add_action('admin_footer', array(&$this, 'outputCommonMarkups'));
		// Add SETUP Link inside the Plugins (only CJT extensions) row!
		add_filter('plugin_action_links', array(&$this, 'addExtensionActions'), 10 , 2);

	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueScripts() {
		$listTypeName = CJTModel::getInstance('extensions')->getListTypeName();
		// Use related scripts.
		self::useScripts(__CLASS__,
			'jquery',
			'thickbox',
			'framework:js:{CJT-}utilities',
			'framework:js:ajax:{CJT-}cjt-server',
			'views:extensions:plugins-list:public:js:{CJTExtensionsPluginsListView-}default',
			"views:extensions:plugins-list:public:js:{CJTExtensionsPluginsListView-}{$listTypeName}"
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueStyles() {
		$listTypeName = CJTModel::getInstance('extensions')->getListTypeName();
		// Use related scripts.
		self::useStyles(__CLASS__,
			'thickbox',
			"views:extensions:plugins-list:public:css:{CJTExtensionsPluginsListView-}{$listTypeName}"
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function outputCommonMarkups() {
		echo $this->getTemplate('default');
	}
} // End class.