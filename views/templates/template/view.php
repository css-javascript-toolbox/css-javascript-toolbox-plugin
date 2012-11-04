<?php
/**
* 
*/

// No direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTTemplatesTemplateView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $isNew;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $item;
	
	/**
	* Initialize view object.
	* 
	* @see CJTView for more details
	* @return void
	*/
	public function __construct($parameters) {		
		parent::__construct($parameters);
		// Enqueue Styles & Scripts.
		$this->enququeScripts();
		$this->enququeStyles();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	public function display($tpl = null) {
		// Is it a new template or just editing exists one?
		$this->isNew = empty($_REQUEST['id']);
		// If editing exists on read it from database.
		if (!$this->isNew) {
			$this->item = $this->getModel()->getItem();
		}
		echo $this->getTemplate($tpl)	;
	}
	
	/**
	* Output Javascript files requirred to Add-New-Block view to run.
	* 
	* @return void
	*/
	public static function enququeScripts() {
		// Use related scripts.
		self::useScripts(
			'jquery',
			'jquery-serialize-object',
			'jquery-ui-tabs',
			'views:templates:template:public:js:{CJT-}template',
			
			'framework:js:ace(loadMethod=Tag, lookFor=ace)',
			
			'framework:js:ace:{CJT-}pluggable',
			'framework:js:ace:plugins:{CJT-}cac',
			'framework:js:ace:plugins:cac:{CJT-}dialog',
			'framework:js:ace:plugins:cac:parsers:{CJT-}common',
			'framework:js:ace:plugins:cac:lib:{CJT-}mode',
			
			'framework:js:ace:plugins:cac:modes:{CJT-}css',
			'framework:js:ace:plugins:cac:modes:{CJT-}javascript',
			'framework:js:ace:plugins:cac:modes:{CJT-}html',
			'framework:js:ace:plugins:cac:modes:{CJT-}php'
		);
	}
	
	/**
	* Output CSS files required to Add-New-Block view.
	* 
	* @return void
	*/
	public static function enququeStyles() {
		// Use related styles.
		self::useStyles(
			'views:templates:template:public:css:{CJT-}default',
			'framework:css:jquery-ui-1.8.21.custom',
			'framework:css:{CJT-}forms'
		);
	}
	
} // End class.