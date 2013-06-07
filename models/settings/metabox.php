<?php
/**
* 
*/
require_once CJTOOLBOX_FRAMEWORK . '/settings/page.inc.php';

/**
* 
*/
class CJTSettingsMetaboxPage extends CJTSettingsPage {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		parent::__construct(get_class($this));
		// Load metabox settins.
		$this->load();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $property
	*/
	public function __get($property) {
		$value = null;
		switch ($property)	{
			// PostTypes always should be array.
			case 'postTypes':
				$value = parent::__get($property);
				$value = is_array($value) ? $value : array();
			break;
		}
		return $value;
	}

} // End class.
