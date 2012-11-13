<?php
/**
* 
*/

/**
* Dont be confused between this Table and CJTBlocksTable class
* 
* this is the replacement of the old Blocks Table.
* 
* Use this as the other is deprecated!
* 
*/
class CJTBlockTable extends CJTxTable {

	/**
	* put your comment there...
	* 
	* @param mixed $dbDriver
	* @param bool Is to enable Build In Queue name Generator.
	* @return CJTTemplatesTable
	*/
	public function __construct($dbDriver) {
		parent::__construct($dbDriver, 'blocks');
	}
	
} // End class.