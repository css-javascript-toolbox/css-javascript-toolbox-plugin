<?php
/**
* 
*/

// No direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTPackagesRawFileView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $rawContent;
	
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	public function display($tpl = null) {
		// Get model.
		$model =& $this->model;
		// Get raw content for the requested file.
		$this->rawContent = nl2br(htmlentities($model->getFileContent()));
		// Display!
		echo $this->getTemplate($tpl);
	}
	
} // End class.

// Hookable!!
CJTPackagesRawFileView::define('CJTPackagesRawFileView', array('hookType' => CJTWordpressEvents::HOOK_FILTER));