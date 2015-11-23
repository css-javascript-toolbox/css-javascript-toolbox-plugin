<?php
/**
* 
*/

// No direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTTinymceBlocksModel_DEPRECATED {
	
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
		$query['select'] = 'b.id, b.name title, b.owner, f.name formTitle';
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
		$query['from'] = '#__cjtoolbox_blocks b LEFT JOIN #__cjtoolbox_forms f ON b.id = f.blockId';
		$query['where'] = 'type = "block" and backupId IS null';
		return $query;
	}
	
} // End class.
