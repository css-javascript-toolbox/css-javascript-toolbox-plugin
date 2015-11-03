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
class CJTPinsBlockSQLView extends CJTSQLView 
{
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $blocksQuery;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $customPins;
	
	/**
	* put your comment there...
	* 
	* @param mixed $dbDriver
	* @return CJTPinsBlockView
	*/
	public function __construct( $driver ) 
	{
		// Initialize SQLView Parent.
		parent::__construct( $driver );
		
		// Set default columns.
		$this->query->columns = array
		(
			'blocks.id',
			'blocks.owner',
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
	public function __toString() 
	{
		
		$filters = $this->query->filter;
		$customPins = $this->customPins;
		
		$customPins = implode( ' OR ', $customPins );
		
		if ( ! empty( $customPins ) ) 
		{
		  $customPins = " OR {$customPins}";
		}
		
		// Exclude blocks ids.
		$excludes = implode( ',', $filters->excludes );
		$excludes = ! empty( $excludes ) ? " AND `id` NOT IN ( {$excludes} )" : '';
		
		# Build where clause
		$this->blocksQuery[ 'where' ] = "( ( ( `backupId` IS NULL ) AND ( `state` = '{$filters->state}' ){$excludes} ) AND ( ( blocks.`pinPoint` & {$filters->pinPoint} ){$customPins} ) )";
		
		# Build final query
		$query = $this->buildQuery( $this->blocksQuery[ 'from' ], $this->blocksQuery[ 'where' ] );
		
		return $query;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function exec() 
	{
		
		# Note: OBJECT_K get unique blocks as they will override each others
		$blocks = $this->driver->select( $this, OBJECT_K );
		
		return $blocks;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $pinPoint
	* @param mixed $customPins
	* @param mixed $state
	* @param mixed $excludes
	*/
	public function & filters( $pinPoint, $customPins, $state = 'active', $excludes = array() ) 
	{
		
		$filters = array
		(
			'state' => $state,
			'pinPoint' => $pinPoint,
			'customPins' => $customPins,
			'excludes' => $excludes,
		);
		
		$this->query->filter = ( object ) $filters;
		
		# make query
		$this->makeBlocksQuery();
		
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getBlocksQuery()
	{
		return $this->blocksQuery;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getCustomPins()
	{
		return $this->customPins;
	}
	
	/**
	* put your comment there...
	* 
	*/
	private function & makeBlocksQuery()
	{
		
		$blocksTable = $this->driver->getTableName( cssJSToolbox::$config->database->tables->blocks );
		$pinsTable = $this->driver->getTableName( cssJSToolbox::$config->database->tables->blockPins );		
		$filters = $this->query->filter;
		
		// build custom pins filter.
		$customPins = array();
		
		foreach ( $filters->customPins as $pinFilter ) 
		{
			$pinFilter = (object) $pinFilter;
			$pins = implode( ',', $pinFilter->pins );
			$customPins[ ] = "( ( blocks.`pinPoint` & {$pinFilter->flag} ) AND ( pins.pin = '{$pinFilter->pin}' ) AND ( pins.`value` IN ( {$pins} ) ) )";
		}
		
		$this->customPins = $customPins;
		
		/**
		* @todo Allow backup ids to passed as filter.
		* @todo Allow block types to be passed as filter.
		*/
		// Add moe re columns.
		$this->query->columns[ 'blocksGroup' ] = "(blocks.pinPoint & {$filters->pinPoint}) blocksGroup";
		
		# This method allowed to run multiple time with diffeent parameters on the same instance
		$this->blocksQuery = array();
		
		// Build final query.
		$this->blocksQuery[ 'from' ] = "`{$blocksTable}` blocks LEFT JOIN `{$pinsTable}` pins ON blocks.`id` = pins.`blockId`";
		
		// WHERE CLAUSE WILL BE BUILD LATE IN __toString() method
				
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $query
	*/
	public function setBlocksQuery( $query )
	{
		
		$this->blocksQuery = $query;
		
		return $this;
	}
	
} // End class.