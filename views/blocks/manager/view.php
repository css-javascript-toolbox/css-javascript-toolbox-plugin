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
	*/
	public function __construct($parameters) {
		parent::__construct($parameters);
		// Import other dependencies views.
		self::Import('blocks/cjt-block');
		// Page load.
		add_action("load-settings_page_cjtoolbox", array($this, 'initPage'));
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
	* put your comment there...
	* 
	*/
	public function initPage() {
		global $current_screen;
		/// Add Screen Help tabs.
		$helpTabs = array(
			'overview' => array(
	  		'title' => __('Overview'),
			),
		);
		// Add tabs to WP_Screen object.
		foreach ($helpTabs as $id => $tab) {
			// Get view content from the external file.
			$helpFile = $id;
			$tab['content'] = $this->getTemplate($helpFile, array(), 'help/screen/tabs', '.html.help');
			// Push tab id to array.
			$tab['id'] = "cjt-blocks-page-help-{$id}";
			$current_screen->add_help_tab($tab);
		}
		// Set screen-help sidebar content.
		$sidebarContent = $this->getTemplate('sidebar', array(), 'help/screen', '.html.help');
		$current_screen->set_help_sidebar($sidebarContent);
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
		self::useScripts(
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
	  // Use blocks page styles.
		self::useStyles(
			'framework:css:{CJT-}toolbox',
			'views:blocks:manager:public:css:{CJT-}blocks'
		);
	}
	
} // End class.