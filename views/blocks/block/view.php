<?php
/**
* @version view.php
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* Blocks view
*/
class CJTBlocksBlockView extends CJTView {
	
	/** */
	const META_BOX_PREFIX = 'cjtoolbox';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $block = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $isClosed;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $localization;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $params = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $templateName = 'edit';
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($viewInfo) {
		parent::__construct($viewInfo);
		// Enqueue Styles & Scripts.
		add_action('admin_print_styles', array(__CLASS__, 'enqueueStyles'));
		add_action('admin_print_scripts', array(__CLASS__, 'enqueueScripts'));
		// Cast params to object
		$this->params = (object) $this->params;
		// Load localization text.
		$this->localization = require($this->getPath('public/js/jquery.block/jquery.block.localization.php'));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function display($template = null) {
		// Import template.
		echo $this->getTemplate($template ? $template : $this->templateName);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__,
			'jquery',
			'common', 
			'wp-lists', 
			'postbox', 
			'thickbox',
			'framework:js:hash:{CJT-}md5',
			'framework:js:cookies:{CJT-}jquery.cookies.2.2.0',
			'framework:js:ajax:{CJT-}cjt-server',
			'framework:js:ajax:{CJT-}cjt-server-queue',
			'framework:js:ui:{CJT-}jquery.toolbox',
			'framework:js:ace(loadMethod=Tag, lookFor=ace)',
			'framework:js:ace:{CJT-}pluggable',
			'views:blocks:block:public:js:{CJT-}ajax',
			'views:blocks:block:public:js:{CJT-}blockproperty',
			'views:blocks:block:public:js:optional:{CJT-}revision',
			'views:blocks:block:public:js:{CJT-}codefile-manager',
			'views:blocks:block:public:js:{CJT-}codefile',
			'views:blocks:block:public:js:{CJT-}block',
			'views:blocks:block:public:js:plugins:{CJT-}_dockmodule',
			'views:blocks:block:public:js:{CJT-}jquery.block'		
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueStyles() {
		// Initialize style.
		$styles = array(
			'thickbox',
			'views:blocks:block:public:css:{CJT-}block',
			'views:blocks:block:public:css:{CJT-}codefile'
		);
	  // IF WP < 3.8 add compatibility CSS file.
	  $wpVersion = new CJT_Framework_Wordpress_Currentversion();
	  if ($wpVersion->isLess('3.8')) {
			$styles[] = 'views:blocks:block:public:css:{CJT-}block-wp-lt-3.8';
	  }
	  // Include styles.
		self::useStyles(__CLASS__, $styles);
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
		$tip = cssJSToolbox::getText('Click to update Block name');
		return "<span class='block-name' title='{$tip}'>{$this->block->name}</span>";
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $id
	*/
	public function getMetaboxId() {
		return self::META_BOX_PREFIX ."-{$this->block->id}";
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getOption($name) {
		return $this->params->{$name};
	}
	
	/**
	* 
	* 
	*/
	public function setBlock($block) {
		// Set block.
		$this->block = $block;
		// Get block state (opened/closes)
		$closedBlockId = "cjtoolbox-{$this->block->id}";
		$closedMetaboxes = get_user_meta(get_current_user_id(), 'closedpostboxes_cjtoolbox', true);
		$this->isClosed = in_array($closedBlockId, ((array) $closedMetaboxes));
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	*/
	public function setOption($name, $value) {
		$this->params->{$name} = $value;
		// Chains!
		return $this;
	}
	
} // End class.

// Hookable!
CJTBlocksBlockView::define('CJTBlocksBlockView');