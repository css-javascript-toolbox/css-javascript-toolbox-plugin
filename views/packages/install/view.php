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
	* @param mixed $tpl
	*/
	public function display($tpl = null) {
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