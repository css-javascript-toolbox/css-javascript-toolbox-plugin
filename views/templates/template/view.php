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
		// Enqueue Styles & Scripts.\
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
		$this->isNew = $_REQUEST['guid'] == '';
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
			'views:templates:template:public:js:{CJT-}template'
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
			'framework:css:{CJT-}forms'
		);
	}
	
} // End class.