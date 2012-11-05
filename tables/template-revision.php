<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTTemplateRevisionTable extends CJTxTable {
	
	/** */
	const FLAG_LAST_REVISION = 0x01;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($dbDriver) {
		parent::__construct($dbDriver, 'template_revisions');
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $templateId
	*/
	public function fetchLastRevision($templateId) {
		$attributes = self::FLAG_LAST_REVISION;
		$query = "SELECT *
													FROM #__cjtoolbox_template_revisions
													WHERE templateId = {$templateId} AND (attributes & {$attributes})";
		$this->load($query);
		return $this;
	}
	
} // End class.