<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTSetupSetupView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $component;
	
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
		// Enqueue scripts.
		add_action('admin_print_scripts', array(__CLASS__, 'enqueueScripts'));
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	public function display($tpl = null) {
		$this->component= $this->getModel('setup')->getCJTComponentData();
		$this->securityToken = cssJSToolbox::getSecurityToken();
		// Display view.
		echo $this->getTemplate($tpl);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__,
			'jquery-serialize-object',
			'framework:js:ajax:{CJT-}cjt-server',
			'views:setup:setup:public:js:{CJTSetupSetupView-}default'
		);
	}
	
} // End class.