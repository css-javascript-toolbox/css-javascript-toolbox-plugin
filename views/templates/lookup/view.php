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
	public $templates = array();
	
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
	public function display() {
		echo $this->getTemplate('default');
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function enqueueScripts() {
		// Get JQuery.
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('cjt-templates-lookup', $this->getURI('js/lookup.js'));
	}
	                                                                                                               
	/**
	* put your comment there...
	* 
	*/
	protected function enqueueStyles() {
		// Get JQuery.
		wp_enqueue_style('cjt-forms', self::getViewURI('_common-files', 'css/forms.css'));
		wp_enqueue_style('jquery-ui-smoothness', self::getViewURI('_common-files', 'css/jquery/themes/smoothness/jquery-ui-1.8.21.custom.css'));
		wp_enqueue_style('cjt-templates-lookup-default', $this->getURI('css/default.css'));
	}
	
} // End class.