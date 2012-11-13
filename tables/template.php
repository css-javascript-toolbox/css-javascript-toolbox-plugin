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
class CJTTemplateTable extends CJTxTable {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public static $states;
	
	/**
	* put your comment there...
	* 
	* @param mixed $dbDriver
	* @param bool Is to enable Build In Queue name Generator.
	* @return CJTTemplatesTable
	*/
	public function __construct($dbDriver) {
		parent::__construct($dbDriver, 'templates');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function setQueueName() {
		$queueName = strtolower(preg_replace('/\W+/', '-', $this->get('name')));
		$this->set('queueName', $queueName);
		return $this;
	}
	
} // End class.

// Initialize static's!
CJTTemplateTable::$states = array(
		'draft' => cssJSToolbox::getText('Draft'),
		'published' => cssJSToolbox::getText('Publish'), 
		'trash' => cssJSToolbox::getText('Trash'),
);