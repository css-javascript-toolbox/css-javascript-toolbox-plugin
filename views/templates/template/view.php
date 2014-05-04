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
	public $item;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onprepareitem = array('parameters' => array('item', 'isNew'));
	
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
		else {
			// E_ALL complain/Default values for new record if needed!
			$this->item = (object) array(
				'name' => '',
				'type' => '',
				'state' => '',
				'code' => '',
				'description' => '',
				'keywords' => '',
				'version' => '',
				'developmentState' => '',
				'changeLog' => '',
				'id' => '',
			);
		}
		$this->item = $this->onprepareitem($this->item, $this->isNew);
		echo $this->getTemplate($tpl)	;
	}
	
	/**
	* Output Javascript files requirred to Add-New-Block view to run.
	* 
	* @return void
	*/
	public function enququeScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__, 
			'jquery',
			'thickbox',
			'jquery-serialize-object',
			'jquery-ui-tabs',
			'framework:js:misc:{CJT-}simple-error-dialog',
			'framework:js:ace(loadMethod=Tag, lookFor=ace)',
			'framework:js:ace:{CJT-}pluggable',
			'views:templates:template:public:js:{CJT-}template'
		);
	}
	
	/**
	* Output CSS files required to Add-New-Block view.
	* 
	* @return void
	*/
	public function enququeStyles() {
		// Use related styles.
		self::useStyles(__CLASS__,
			'thickbox',
			'framework:css:jquery-ui-1.8.21.custom',
			'framework:css:{CJT-}forms',
			'framework:css:{CJT-}error-dialog',
			'views:templates:template:public:css:{CJT-}default'
		);
	}
	
} // End class.

// Hookable!!
CJTTemplatesTemplateView::define('CJTTemplatesTemplateView', array('hookType' => CJTWordpressEvents::HOOK_FILTER));