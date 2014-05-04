<?php
/**
* @version $ Id; view.php 21-03-2012 03:22:10 Ahmed Said $
*/

// Diallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* Get Add-New-Block view markups.
* 
* The method is resposible for selecting the correct template
* and initialize template vars.
* 
* @author Ahmed Said
* @version 6
*/
class CJTBlocksNewView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $position;
	
	/**
	* Output Add New Block markups.
	* 
	* @return void
	*/
	public function display() {
		$defaultValues = array('position');
		// Get form fields default values.
		foreach ($defaultValues as $name) {
		  if (array_key_exists($name, $_GET)) {
				$this->$name = $_GET[$name];
			}
		}
		echo $this->getTemplate('default');
	}
	
	/**
	* Output Javascript files requirred to Add-New-Block view to run.
	* 
	* @return void
	*/
	public static function enququeScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__,
			'jquery',
			'thickbox',
			'jquery-serialize-object',
			'framework:js:misc:{CJT-}simple-error-dialog',
			'views:blocks:new:public:js:{CJT-}add-new-block'
		);
	}
	
	/**
	* Output CSS files required to Add-New-Block view.
	* 
	* @return void
	*/
	public static function enququeStyles() {
		// Use related styles.
		self::useStyles(__CLASS__,
			'thickbox',
			'framework:css:{CJT-}error-dialog',
			'framework:css:{CJT-}forms'
		);
	}
	
} // End class.

// Hookable!!
CJTBlocksNewView::define('CJTBlocksNewView');