<?php
/**
* 
*/

/**
* 
*/
class CJTBlocksCreateMetaBoxView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $block = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $options = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $securityToken = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $viewInfo
	* @return CJTBlockMetaBoxView
	*/
	public function __construct($viewInfo) {
		parent::__construct($viewInfo);
		// Initialize vars.
		$this->options = (object) array();
		// Add scripts ands styles actions.
		add_action('admin_print_scripts', array(__CLASS__, 'enqueueScripts'));
		add_action('admin_print_styles', array(__CLASS__, 'enqueueStyles'));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function display() {
		echo $this->getTemplate('create');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__,
			'jquery-ui-menu',
			'views:blocks:create-metabox:public:js:{CJT-}metabox',
			'views:blocks:manager:public:js:{CJT-}blocks',
			'framework:js:ajax:{CJT-}cjt-server',
			'framework:js:ui:{CJT-}jquery.link-progress',
			'framework:js:ajax:{CJT-}scripts-loader',
			'framework:js:ajax:{CJT-}styles-loader',
			'framework:js:wordpress:{CJT-}script-localizer'
		);
		/* 
		* Link Thickbox to header instead of footer.
		* media-upload script linked directly after thickbox script.
		* media-upload script override tb_position() function to show
		* thickbox popup forms in fixed position and size!
		* 
		* To overcome this problem we need to get a copy from original tb_position()
		* just after thickbox is loaded and just before media-upload is 
		* not override tb_position yet.
		* metabox.js now is between thickbox and media-upload, have fun!
		*/
		$GLOBALS['wp_scripts']->registered['thickbox']->args = 0;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueStyles() {
		// Use related styles.
		self::useStyles(__CLASS__,
			'views:blocks:create-metabox:public:css:{CJT-}metabox'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getBlock() {
		return $this->block;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getMetaboxName() {
		return $this->getBlock()->name;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $id
	*/
	public function getMetaboxId() {
		// Use the same id as regular CJT block.
		$blockView = CJTView::create('blocks/block');
		// To avoid outputing script and styles for block box Remove scripts and styles actions!
		remove_action('admin_print_scripts', array('CJTBlocksBlockView', 'enqueueScripts'));
		remove_action('admin_print_styles', array('CJTBlocksBlockView', 'enqueueStyles'));
		// Get metabox id.
		$blockView->setBlock($this->getBlock());
		return $blockView->getMetaboxId();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getOption($name) {
		return isset($this->options->{$name}) ? $this->options->{$name} : null;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getSecurityToken() {
		return $this->securityToken;	
	}
	/**
	* put your comment there...
	* 
	* @param mixed $block
	*/
	public function setBlock($block) {
		$this->block = $block;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	*/
	public function setOption($name, $value) {
		$this->options->{$name} = $value;
		// Chaining!
		return $this;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $token
	*/
	public function setSecurityToken($token) {
		$this->securityToken = $token;
	}
	
} // End class.

// Hookable!!
CJTBlocksCreateMetaBoxView::define('CJTBlocksCreateMetaBoxView');