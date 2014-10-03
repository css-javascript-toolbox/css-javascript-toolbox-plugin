<?php
/**
* 
*/

/**
* 
*/
class CJTBlocksMetaBoxView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $blockView = null;
	
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
		// Create block view.
		$this->blockView = self::create('blocks/block');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function display() {
		// Display content.
		echo $this->getTemplate('metabox');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueScripts() {
		// Regular block scripts.
		CJTBlocksBlockView::enqueueScripts();
		// Use related scripts.
		self::useScripts(__CLASS__,
			'views:blocks:metabox:public:js:{CJT-}metabox',
			'views:blocks:manager:public:js:{CJT-}blocks',
			'views:blocks:metabox:public:js:optional:{CJT_CJT_BLOCK-}revision',
			'views:blocks:metabox:public:js:{CJT_METABOX_BLOCK-}block',
			'views:blocks:metabox:public:js:{CJT_METABOX_BLOCK-}jquery.block',
			'framework:js:ajax:{CJT-}cjt-server',
			'framework:js:ajax:{CJT-}scripts-loader',
			'framework:js:ajax:{CJT-}styles-loader',
			'framework:js:wordpress:{CJT-}script-localizer'
		);
		/* 
		* Link Thickbox to header instead of footer.
		* media-upload script linked directly after thickbox script.
		* media-upload script override tb_position function to show
		* thickbox popup forms in fixed position and size!
		* To overcome this problem we need to get a copy from original tb_position
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
		// Regular block styles.
		CJTBlocksBlockView::enqueueStyles();  
		// Import related styles.
		self::useStyles(__CLASS__,
			'framework:css:{CJT-}toolbox',
			'views:blocks:metabox:public:css:{CJT-}metabox'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getBlock() {
		return $this->blockView->block;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getBlockView() {
		return $this->blockView;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getMetaboxName() {
		return $this->blockView->getMetaboxName();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $id
	*/
	public function getMetaboxId() {
		return $this->blockView->getMetaboxId();
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
		$this->blockView->block = $block;
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
CJTBlocksMetaBoxView::define('CJTBlocksMetaBoxView');