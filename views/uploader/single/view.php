<?php
/**
* 
*/

// No direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTUploaderSingleView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $pon;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $state;
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	public function display($tpl = null) {
		// Push vars.
		$this->pon = $this->getRequestParameter('pon');
		$this->state = $this->model->getState('error');
		// Output.
		echo $this->getTemplate($tpl);
	}
	
	/**
	* Output Javascript files requirred to Add-New-Block view to run.
	* 
	* @return void
	*/
	public function enququeScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__, 'jquery', 'views:uploader:single:public:js:{CJT-}uploader');
	}
	
	/**
	* Output CSS files required to Add-New-Block view.
	* 
	* @return void
	*/
	public function enququeStyles() {
		// Use related styles.
		self::useStyles(__CLASS__, 'views:uploader:single:public:css:{CJT-}uploader');
	}
	
} // End class.

// Hookable!!
CJTUploaderSingleView::define('CJTUploaderSingleView', array('hookType' => CJTWordpressEvents::HOOK_FILTER));