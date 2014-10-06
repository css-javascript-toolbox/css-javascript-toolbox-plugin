<?php
/**
* @version view.php
*/

//
require_once CJTOOLBOX_MODELS_PATH . '/block.php';

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* Blocks view.
*/
class CJTBlocksManagerView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $backupId;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $blocks = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $hasBlocks = false;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $onloadglobalcomponents = array(
		'hookType' => CJTWordpressEvents::HOOK_FILTER,
		'parameters' => array('content')
	);

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $order;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $pageHook;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $securityToken;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($parameters) {
		parent::__construct($parameters);
		// Import other dependencies views.
		self::Import('blocks/cjt-block');
		// register callback to show styles needed for the admin page
		add_action('admin_print_styles', array(__CLASS__, 'enququeStyles'));
		// Load scripts for admin panel working
		add_action('admin_print_scripts', array(__CLASS__, 'enququeScripts'));
		// Blocks order is common for all users, override user meta-boxes order
		// by meta-box-order site option.
		add_filter('get_user_option_meta-box-order_cjtoolbox', array(&$this, 'getBlocksOrder'));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function display() {
		// Initialize view vars.
		$this->hasBlocks = !empty($this->blocks);
		// Add metabox for each block.
		foreach ($this->blocks as $block) {
			$block = new CJTBlockModel($block);
			// Create cjt-block view.
			$view = self::create('blocks/cjt-block');
			$blockView =& $view->getBlockView();
			// Initialize block view.
			$blockView->setBlock($block);
			// Add metabox
			add_meta_box($blockView->getMetaboxId(), $blockView->getMetaboxName(), array($view, 'display'), $this->pageHook, 'normal', 'core');
		}
		// Display the view.
		echo $this->getTemplate('blocks');
	}
	
	/**
	* put your comment there...
	* 
	* As result of do_meta_boxes this filter will be called.
	* 
	* Callback for apply_filters("get_user_option_{$option}", $result, $option, $user).
	* 
	*/
	public function getBlocksOrder() {
		return $this->order;
	}
	
	/**
	* Enqueue scripts.
	* 
	* Callback for admin_print_scripts-[$hook_manage].
	*/
	public static function enququeScripts() {
	  // Enquque single block scripts.
	  CJTBlocksCjtBlockView::enqueueScripts();
	  // Use blocks page scripts.
		self::useScripts(__CLASS__,
			'views:blocks:manager:public:js:{CJT-}blocks',
			'views:blocks:manager:public:js:{CJT-}blocks-page',
			'views:blocks:manager:public:js:{CJT-}ajax-multioperation'
		);
	}
	
	/**
	* Enqueue styles.
	* 
	* Callback for admin_print_styles-[$hook_manage].
	*/
	public static function enququeStyles() {
	  // Enquque single block styles.
	  CJTBlocksCjtBlockView::enqueueStyles();
	  // Styles list.
	  $styles = array('framework:css:{CJT-}toolbox', 'views:blocks:manager:public:css:{CJT-}blocks');
	  // IF WP < 3.8 add compatibility CSS file.
	  $wpVersion = new CJT_Framework_Wordpress_Currentversion();
	  if ($wpVersion->isLess('3.8')) {
			$styles[] = 'views:blocks:manager:public:css:{CJT-}blocks-wp-lt-3.8';
	  }
	  // Include styles.
		self::useStyles(__CLASS__, $styles);
	}
	
} // End class.

// Hookable!!
CJTBlocksManagerView::define('CJTBlocksManagerView');