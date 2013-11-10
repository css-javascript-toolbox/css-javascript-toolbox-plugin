<?php
/**
* 
*/
require_once CJTOOLBOX_FRAMEWORK . '/settings/page.inc.php';

/**
* 
*/
class CJTSettingsAdvancedPage extends CJTSettingsPage {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		parent::__construct(get_class($this));
		// Load metabox settins.
		$this->load();
	}

} // End class.
