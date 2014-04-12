<?php
/**
* @version view.php
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

/**
* Blocks view.
*/
class CJTBlocksRevisionsView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $blockId = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $revisions = array();
	
	/**
	* Output Javascript files requirred to Add-New-Block view to run.
	* 
	* @return void
	*/
	public function enququeScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__,
			'jquery',
			'views:blocks:revisions:public:js:{CJT-}revisions'
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
			'framework:css:forms'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function display() {
		// Display view.
		echo $this->getTemplate('default');
	}
	
} // End class.

// Hookable!!
CJTBlocksRevisionsView::define('CJTBlocksRevisionsView');