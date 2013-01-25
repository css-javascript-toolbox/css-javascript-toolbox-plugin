<?php
/**
*   
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTErrorsLogView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $items;
	
	/**
	* put your comment there...
	* 
	* @param mixed $pl
	*/
	public function display($tpl = null) {
		$model =& $this->model;
		// Read view vars!
		$this->items = $model->getItems();
		// Display view template!
		echo $this->getTemplate($tpl);
	}
	
} // End class
