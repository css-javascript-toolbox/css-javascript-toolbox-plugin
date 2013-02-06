<?php
/**
* 
*/

// No direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTTinymceBlocksModel {
	
	/**
	* put your comment there...
	* 
	*/
	public function getItems() {
		// Initializing!
		$driver = cssJSToolbox::getInstance()->getDBDriver();
		// Get common quiery parts!@
		$query = $this->getItemsQuery();
		// Add fields list!
		$query['select'] = 'id, name title, owner';
		$query = "SELECT {$query['select']} FROM {$query['from']} WHERE {$query['where']};";
		// Retrieve blocks!
		return $driver->select($query);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItemsQuery() {
		// Build query parts!
		$query['from'] = '#__cjtoolbox_blocks';
		$query['where'] = 'type = "block" and backupId IS null';
		return $query;
	}
	
} // End class.
