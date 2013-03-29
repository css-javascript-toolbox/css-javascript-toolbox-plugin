<?php
/**
* 
*/

// Import dependencies.
CJTView::Import('blocks/block');

/**
* 
*/
class CJTBlocksCjtBlockView extends CJTView {
	
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
	protected $templateName = 'default';
	
	/**
	* put your comment there...
	* 
	* @param mixed $viewInfo
	* @return CJTBlocksCjtBlock
	*/
	public function __construct($viewInfo) {
		parent::__construct($viewInfo);
		// Aggregate block view object!
		$this->blockView = self::create('blocks/block');
		// Register actions.
		add_action('admin_print_scripts', array(__CLASS__, 'enqueueScripts'));
		add_action('admin_print_styles', array(__CLASS__, 'enqueueStyles'));
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	public function display() {
		// Display block view
		echo $this->getTemplate($this->templateName);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function enqueueScripts() {
		// Import related JScripts.
		CJTBlocksBlockView::enqueueScripts();
		self::useScripts(__CLASS__,
			'jquery-ui-tabs', 
			'jquery-ui-accordion', 
			'views:blocks:cjt-block:public:js:{CJT_CJT_BLOCK-}jquery.block'
		);
	}

	/**
	* put your comment there...
	* 
	*/
	public static function enqueueStyles() {
		// Import related styles.
		CJTBlocksBlockView::enqueueStyles();
		self::useStyles(__CLASS__, 
			'views:blocks:cjt-block:public:css:{CJT_BLOCKS_PAGE_BLOCK-}block'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getBlockView() {
		return $this->blockView;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $pin
	* @param mixed $type
	*/
	public function getPinCheckbox($name, $pin) {
		$checked  = ($this->getBlockView()->getBlock()->pinPoint & $pin) ? 'checked=checked' : '';
		// Get checkbox value from pin.
		$value = dechex($pin);
		$checkbox = "<input type='checkbox' name='cjtoolbox[{$this->getBlockView()->getBlock()->id}][{$name}][]' value='{$value}' {$checked} />";
		return $checkbox;
	}
	
	/**
	* 
	* 
	*/
	public function setBlock($block) {
		$this->blockView->setBlock($block);
	}
	
} // End class.

// Hookable!!
CJTBlocksCjtBlockView::define('CJTBlocksCjtBlockView');