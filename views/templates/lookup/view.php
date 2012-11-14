<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

/**
* No direct access.
*/
// No Direct Accesss code

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTTemplatesLookupView extends CJTView {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $items;
	
	/**
	* put your comment there...
	* 
	* @param mixed $info
	* @return CJTTemplatesLookupView
	*/
	public function __construct($info) {
		// Initialize CJTView base class.
		parent::__construct($info);
		// Initialize.
		$this->enqueueScripts();
		$this->enqueueStyles();
	}

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
		// Get JQuery.
		self::useScripts(
			'jquery-ui-accordion',
			'views:templates:lookup:public:js:{CJT_TEMPLATES-}lookup'
		);
	}
	                                                                                                               
	/**
	* put your comment there...
	* 
	*/
	protected function enqueueStyles() {
		self::useStyles(
			'framework:css:{CJT-}forms',
			'views:templates:lookup:public:css:{CJT-}lookup'
		);
	}
	
} // End class.