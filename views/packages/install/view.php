<?php
/**
* 
*/

// No direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTPackagesInstallView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $moduleParamName;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $uploaderModuleName;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $uploaderActionName;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $uploaderControllerName;
	
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	public function display($tpl = null) {
		// Get uploader parameters.
		$this->uploaderControllerName = $this->getRequestParameter('uploaderControllerName');
		$this->uploaderActionName = $this->getRequestParameter('uploaderActionName');
		$this->uploaderModuleName = $this->getRequestParameter('uploaderModuleName');
		$this->moduleParamName = 'cjtajaxmodule';
		// Display form.
		echo $this->getTemplate($tpl);
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
			'framework:js:misc:{CJT-}simple-error-dialog',
			'views:packages:install:public:js:{CJT-}install'
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
			'framework:css:{CJT-}forms',
			'framework:css:{CJT-}error-dialog',
			'views:packages:install:public:css:{CJT-}install'
		);
	}
	
} // End class.

// Hookable!!
CJTPackagesInstallView::define('CJTPackagesInstallView', array('hookType' => CJTWordpressEvents::HOOK_FILTER));