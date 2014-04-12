<?php
/**
* @version $ Id; view.php 21-03-2012 03:22:10 Ahmed Said $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
* 
* The method is resposible for selecting the correct template
* and initialize template vars.
* 
* @author Ahmed Said
* @version 6
*/
class CJTBackupsManagerView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $backups = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $controllerName = '';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $currentBackup = null;
	
	/**
	* Output Add New Block markups.
	* 
	* @return void
	*/
	public function display() {
		echo $this->getTemplate('default');
	}
	
	/**
	* Output Javascript files requirred to Backups view to run.
	* 
	* @return void
	*/
	public static function enququeScripts() {
		// Import all required Javascript with localization.
		self::useScripts(__CLASS__,
			'jquery', 
			'framework:js:ui:{CJT-}jquery.link-progress', 
			'views:backups:manager:public:js:{CJT-}backups'
			);
	}
	
	/**
	* Output CSS files required to Backups view.
	* 
	* @return void
	*/
	public static function enququeStyles() {
		self::useStyles(__CLASS__,
			'framework:css:{CJT-}forms', 
			'views:backups:manager:public:css:{CJT-}backups'
			);
	}
	
} // End class.

// Hookable!!
CJTBackupsManagerView::define('CJTBackupsManagerView');