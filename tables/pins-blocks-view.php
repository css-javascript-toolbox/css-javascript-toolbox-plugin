<?php
/**
* 
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

/**
* Import libs.
*/
require_once CJTOOLBOX_INCLUDE_PATH . '/db/mysql/sql-view.inc.php';

/**
*
*/
class CJTPinsBlockSQLView extends CJTSQLView {
	
	/**
	* put your comment there...
	* 
	* @param mixed $dbDriver
	* @return CJTPinsBlockView
	*/
	public function __construct($driver) {
		// Initialize SQLView Parent.
		parent::__construct($driver);
		// Set default columns.
		$this->query->columns = array(
			'blocks.id',
			'blocks.name',
			'blocks.pinPoint',
			'blocks.location',
			'blocks.links',
			'blocks.expressions'
		);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		// Prepare tables name.
		$blocksTable = $this->driver->getTableName(cssJSToolbox::$config->database->tables->blocks);
		$pinsTable = $this->driver->getTableName(cssJSToolbox::$config->database->tables->blockPins);		
		$filters = $this->query->filter;
		// build custom pins filter.
		$customPins = array();
		foreach ($filters->customPins as $pinFilter) {
			$pinFilter = (object) $pinFilter;
			$pins = implode(',', $pinFilter->pins);
			$customPins[] = "((blocks.`pinPoint` & {$pinFilter->flag}) AND (pins.pin = '{$pinFilter->pin}') AND (pins.`value` IN ({$pins})))";
		}
		$customPins = implode(' OR ', $customPins);
		if (!empty($customPins)) {
		  $customPins = " OR {$customPins}";
		}
		// Exclude blocks ids.
		$excludes = implode(',', $filters->excludes);
		$excludes = !empty($excludes) ? " AND `id` NOT IN ({$excludes})" : '';
		/**
		* @todo Allow backup ids to passed as filter.
		* @todo Allow block types to be passed as filter.
		*/
		// Add moe re columns.
		$this->query->columns['blocksGroup'] = "(blocks.pinPoint & {$filters->pinPoint}) blocksGroup";
		// Build final query.
		$query['from'] = "`{$blocksTable}` blocks
											LEFT JOIN `{$pinsTable}` pins
											ON blocks.`id` = pins.`blockId`";
		$query['where'] = "(((`backupId` IS NULL) AND (`state` = '{$filters->state}'){$excludes}) AND 
											((blocks.`pinPoint` & {$filters->pinPoint}){$customPins}))";
		// Combine all into one statement.
		$query = $this->buildQuery($query['from'], $query['where']);
		return $query;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function exec() {
		return $this->driver->select($this, OBJECT_K);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $pinPoint
	* @param mixed $customPins
	*/
	public function filters($pinPoint, $customPins, $state = 'active', $excludes = array()) {
		$filters = array(
			'state' => $state,
			'pinPoint' => $pinPoint,
			'customPins' => $customPins,
			'excludes' => $excludes,
		);
		$this->query->filter = (object) $filters;
	}
	
} // End class.