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
class CJTBlocksInfoView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $info = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $parameters
	* @return CJTBlockView
	*/
	public function __construct($parameters) {
		parent::__construct($parameters);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function display() {
		echo $this->getTemplate('default');
	}
	
} // End class

// Hookable!!
CJTBlocksInfoView::define('CJTBlocksInfoView');