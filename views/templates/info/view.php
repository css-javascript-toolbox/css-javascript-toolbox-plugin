<?php
/**
* 
*/

/**
* 
*/
class CJTTemplatesInfoView extends CJTView {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/	
	public $item;
	
	/**
	* put your comment there...
	* 
	* @param mixed $vInfo
	* @return CJTTemplatesInfoView
	*/
	public function __construct($vInfo) {
		parent::__construct($vInfo)	;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $tpl
	*/
	public  function display($tpl = null) {
		// Get item.
		$this->item = $this->model->getItem();
		// For all items that has no value show "Not Available"
		foreach ((array)$this->item as $prop => $value) {
			if (!$this->item->{$prop}) {
				$this->item->{$prop} = cssJSToolbox::getText('N/A');
			}
		}
		echo $this->getTemplate($tpl);
	}
	
} // End class.

// Hookable!!
CJTTemplatesInfoView::define('CJTTemplatesInfoView');