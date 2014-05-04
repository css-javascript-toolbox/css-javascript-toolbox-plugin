<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTTemplatesManagerView extends CJTView {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $items = null;
	
	/**
	* put your comment there...
	* 
	*/
	public function display($tpl = null) {
		// Query templates list.
		$this->items = $this->getModel()->getItems();
		// Display the view.
		echo $this->getTemplate($tpl);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function enqueueScripts() {
		// Import dependencies scripts!
		self::useScripts(__CLASS__,
			'jquery',
			'jquery-serialize-object', 
			'thickbox',
			'views:templates:manager:public:js:{CJT_TEMPLATES-}manager'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function enqueueStyles() {
		// Import dependencies styles!
		self::useStyles(__CLASS__,
			'wp-admin',
			'colors-fresh',
			'thickbox',
			'framework:css:{CJT-}forms',
			'views:templates:manager:public:css:{CJT-}default'
		);
	}
	
} // End class.

// Hookable!!
CJTTemplatesManagerView::define('CJTTemplatesManagerView');