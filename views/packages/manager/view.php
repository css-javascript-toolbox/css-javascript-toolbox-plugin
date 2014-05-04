<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTPackagesManagerView extends CJTView {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $items;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $itemsPerPage;
		
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $itemsTotal;
	
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
		// register callback to show styles needed for the admin page
		add_action('admin_print_styles', array(__CLASS__, 'enqueueStyles'));
		// Load scripts for admin panel working
		add_action('admin_print_scripts', array(__CLASS__, 'enqueueScripts'));
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	public function display($tpl = null) {
		// Initialize.
		$model = $this->getModel('packages');
		// Cache view vars.
		$this->itemsTotal = $model->getItemsTotal();
		$this->itemsPerPage = $model->getItemsPerPage();
		$this->items = $model->getItems();
		$this->securityToken = cssJSToolbox::getSecurityToken();
		// Display view.
		echo $this->getTemplate($tpl);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueScripts() {
		// Import dependencies scripts!
		self::useScripts(__CLASS__,
			'jquery',
			'jquery-serialize-object',
			'framework:js:ajax:{CJT-}cjt-server',
			'thickbox',
			'views:packages:manager:public:js:{CJT_PACKAGES-}manager'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueStyles() {
		// Import dependencies styles!
		self::useStyles(__CLASS__,
			'wp-admin',
			'colors-fresh',
			'thickbox',
			'views:packages:manager:public:css:{CJT-}default'
		);
	}
	
} // End class.

// Hookable!!
CJTPackagesManagerView::define('CJTPackagesManagerView');