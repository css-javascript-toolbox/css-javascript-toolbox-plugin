<?php
/**
* @version $ Id; block-ajax.php 21-03-2012 03:22:10 Ahmed Said $
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

/**
* Model base class.
*/
require_once CJTOOLBOX_MVC_FRAMEWOK . '/model.inc.php';

/**
* Represent single block object.
* 
* Provide simple access read or write to block properties.
* 
* @author Ahmed Said
* @version 6
*/
class CJTBlockModel extends CJTModel {

	/**
	* Global scope pins
	*/
	const PINS_FRONTEND = 0x01;
	const PINS_BACKEND = 0x02;
	
	/**
	* Pages Pins.
	*/
	const PINS_PAGES_ALL_PAGES = 0x10;
	const PINS_PAGES_CUSTOM_PAGE = 0x20;
	const PINS_PAGES_FRONT_PAGE = 0x40;
  
	/**
	* Posts Pins.
	*/	
	const PINS_POSTS_ALL_POSTS = 0x100;
	const PINS_POSTS_CUSTOM_POST = 0x200;
	const PINS_POSTS_RECENT = 0x400;
	const PINS_POSTS_BLOG_INDEX = 0x800;
	
	/**
	* Categories Pins.
	*/
	const PINS_CATEGORIES_ALL_CATEGORIES = 0x1000;
	const PINS_CATEGORIES_CUSTOM_CATEGORY = 0x2000;
		
	/**
	* Other general pages pins.
	*/
	const PINS_SEARCH = 0x10000;
	const PINS_ARCHIVE = 0x20000;
	const PINS_TAG = 0x40000;
	const PINS_AUTHOR = 0x80000;
	const PINS_ATTACHMENT = 0x100000;
	const PINS_404_ERROR = 0x200000;
	
	/**
	* 
	*/
	const PINS_LINKS = 0x1000000;
	const PINS_EXPRESSIONS = 0x2000000;
	
	/**
	* 
	*/
	const PINS_LINK_EXPRESSION = 0x3000000;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private static $customPins;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $propertiesMeta = array
	(
		'name' => array(),
		'pinPoint' => array(),
		'state' => array(),
		'location' => array(),
		'code' => array(),
		'links' => array(),
		'expressions' => array(),
		'id' => array(),
	);
	
	/**
	* Create block object.
	* 
	* @param integer Block id.
	* @param array Block data array or null to use default data.
	* @return void
	*/
	public function __construct( $values = array() ) 
	{
		// Allow pluggable custom pins
		$customPins = self::getCustomPins();
		
		// Allow pluggable fields
		$this->propertiesMeta = apply_filters( CJTPluggableHelper::FILTER_BLOCK_MODEL_PROPERTIES_META, $this->propertiesMeta );
		
		// Merge prpperties meta wioth custom pins to create one full propertiesMeta objetc
		// that hold both fields and custom pins fields
		$this->propertiesMeta = array_merge( $this->propertiesMeta, $customPins );
		
		// Properties backward compatibolity to hold all available properties 
		$this->properties = array_combine( 
		
			array_keys( $this->propertiesMeta ), 
			
			array_fill( 0, count( $this->propertiesMeta ), null ) 
			
			);
		
		// Set block properties.
		$this->setValues( $values );
	}
	
	/**
	* Get block property value.
	* 
	* @param string Property name.
	* @return mixed Property value.
	*/
	public function __get( $property )
	{
		
		// Return default values
		$value = 	( 	( $this->properties[ $property ] === null ) && 
									( isset( $this->propertiesMeta[ $property ][ 'default' ] ) ) ) ? 
							
							$this->propertiesMeta[ $property ][ 'default' ] :
							$this->properties[ $property ];
		
		return $value;
	}
	
	/**
	* Set block proprty value.
	* 
	* @param string Property name.
	* @param mixed Property value.
	* @return void
	*/
	public function __set($property, $value) 
	{
		switch ( $property ) 
		{
			case 'code':
			
			case 'links':
			
			case 'expressions':
			
				// New lines submitted to server as CRLF but displayed in browser as LF.
				// PHP script and JS work on two different versions of texts.
				// Replace CRLF with LF just as displayed in browsers.
				$value = preg_replace( "/\x0D\x0A/", "\x0A", $value );
				
			break;
		}
		
		$this->properties[ $property ] = $value;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $blockData
	*/
	public static function arrangePins( & $blockData ) 
	{

		$pinsGroupNames = array_flip( array_merge( array_keys( self::getCustomPins() ), array( 'pinPoint' ) ) );
		$dbDriver = cssJSToolbox::getInstance()->getDBDriver();
		$mdlBlock = new CJTBlocksModel();
		$block = $mdlBlock->getBlock( $blockData->id, array(), array( 'id', 'pinPoint' ) );
		$submittedPins = array_intersect_key( ( ( array ) $blockData ), $pinsGroupNames );
		$assignedPins = array_intersect_key( ( ( array ) $block ), $pinsGroupNames );
		
		// Transfer assigned PinPoint from "FLAGGED INTEGER" TO "ARRAY" like
		// the other pins.
		$assignedPins[ 'pinPoint' ] = array_keys( CJT_Models_Block_Assignmentpanel_Helpers_Auxiliary
																::getInstance()
																->getPinsArray( $assignedPins[ 'pinPoint' ] ) );
		
		// Walk through all assigned pins.
		// Unassigned any item with 'value=false'.
		// Whenever an item is found on the submitted
		// pins it should be removed from the submitted list
		foreach ( $submittedPins as $groupName => $submittedGroup ) 
		{
			// Get assigned pins group if found 
			if ( ! isset( $assignedPins[ $groupName ] ) ) 
			{
				// Initialize new assigned group array.
				$assignedPins[ $groupName ] = array();
			}
			
			$assignedGroup =& $assignedPins[ $groupName ];
			
			// For every submitted item there is three types.
			// 1. Already assigned :WHEN: (sync == true and value == true)
			// 2. Unassigned :WHEN: (value == false)
			// 3. Newly assigned :WHEN: (sync = false).
			foreach ( $submittedGroup as $submittedPinId => $submittedPin ) 
			{
				// Unassigned pin
				if ( ! $submittedPin[ 'value' ] ) 
				{
					// Find the submittedPinId AssignedPins index.
					$assignedIndex = array_search( $submittedPinId, $assignedGroup );
					// Unassigned it :REMOVE FROM ARRAY:
					unset( $assignedGroup[ $assignedIndex ] );
				}
				else if ( ! $submittedPin[ 'sync' ] ) 
				{
					// Add newly assigned item.
					$assignedGroup[] = $submittedPinId;
				}
				
			}
			
		}
		
		// Copy all assigned pins back to the block object.
		foreach ( $assignedPins as $groupName => $finalGroupAssigns )
		{
			$blockData->{$groupName} = $finalGroupAssigns;
		}
		
		// Important for caller short-circle condition.
		return true;
	}

	/**
	* put your comment there...
	* 
	* @deprecated Use calculatePinpoint
	*/
	public static function calculateBlockPinPoint( & $block ) 
	{
		
		// Generate PinPoint Value.
		if ( isset( $block->pinPoint ) && is_array( $block->pinPoint ) ) 
		{
			$pinPoint = 0;
			
			// Each item is a bit flag.
			foreach ( $block->pinPoint as $pin ) 
			{
				$pinPoint |= hexdec($pin);
			}
			
		}
		else 
		{
			// Provided as integer or not even provided!
			if ( ! isset( $block->pinPoint ) ) 
			{
				$block->pinPoint = 0;
			}
			
		  $pinPoint = (int) $block->pinPoint;
		}
		
		// Pin should be set only for not empty properties.
		// This help us retreiving only needed blocks when querying blocks code.
		$pins = array
		(
			self::PINS_LINKS => 'links',
			self::PINS_EXPRESSIONS => 'expressions',
		);
		
		$customPins = self::getCustomPins();
		
		// Add Custom Pins to pins to be calculated below
		foreach ( $customPins as $customPinName => $customPin )
		{
			$pins[ $customPin[ 'pinValue' ] ] = $customPinName;
		}

		foreach ( $pins as $flag => $pin ) 
		{
		  $pinPoint |= abs( ( int ) ( ! empty( $block->{$pin} ) ) ) * $flag;
		}
		
		$block->pinPoint = $pinPoint;
		
		return $block;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $block
	* @param mixed $pins
	*/
	public static function calculatePinPoint( $block, $pins ) 
	{
		// Add pins to Block object so that calculateBlockPinPoint can calculate PinPoint value!
		$block = ( object ) array_merge( $block, $pins );
		
		$pinPoint = self::calculateBlockPinPoint( $block )->pinPoint;
		
		return $pinPoint;
	}

	/**
	* put your comment there...
	* 
	*/
	public static function getCustomPins()
	{
		static $pins = array
		(		
			'pages' => array
			( 
			
				'default' => array(),
				'pinValue' => self::PINS_PAGES_CUSTOM_PAGE 
				
			),
			
			'posts' => 			array
			( 
			
				'default' => array(),
				'pinValue' => self::PINS_POSTS_CUSTOM_POST 
				
			),
			
			'categories' => array
			( 
			
				'default' => array(),
				'pinValue' => self::PINS_CATEGORIES_CUSTOM_CATEGORY 
				
			),
		);
		
		// Cache custom pins
		if ( ! self::$customPins )
		{
			self::$customPins = apply_filters( CJTPluggableHelper::FILTER_BLOCK_MODEL_CUSTOM_PINS, $pins );	
		}
		
		return self::$customPins;
	}
	
} // End class.