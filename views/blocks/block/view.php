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
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function display() {
		// Import template.
		echo $this->getTemplate($this->templateName);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueScripts() {
		// Use related scripts.
		self::useScripts(
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
			'framework:js:ace:plugins:{CJT-}cac',
			'framework:js:ace:plugins:cac:{CJT-}dialog',
			'framework:js:ace:plugins:cac:parsers:{CJT-}common',
			'framework:js:ace:plugins:cac:lib:{CJT-}mode',
			
			'framework:js:ace:plugins:cac:modes:{CJT-}css',
			'framework:js:ace:plugins:cac:modes:{CJT-}javascript',
			'framework:js:ace:plugins:cac:modes:{CJT-}html',
			'framework:js:ace:plugins:cac:modes:{CJT-}php',
			
			'views:blocks:block:public:js:{CJT-}ajax',
			'views:blocks:block:public:js:{CJT-}block',
			'views:blocks:block:public:js:{CJT-}jquery.block'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueStyles() {
		// Use related styles.
		self::useStyles(
			'thickbox',
			'views:blocks:block:public:css:{CJT-}block'
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
		return "<span class='block-name'>{$this->block->name}</span>";
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
		$this->block = $block;
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