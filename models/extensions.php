<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTExtensionsModel {
	
	/**
	* put your comment there...
	* 
	*/
	public function getListTypeName() {
		// if the search term is for CJT extensions return 'extensions' otherwise return 'plugins'
		return ((isset($_REQUEST['s']) && ($_REQUEST['s'] == CJTExtensionsAccessPoint::PLUGINS_PAGE_SEARCH_TERM)) ? 'extensions' : 'plugins');
	}
	
} // End class.
