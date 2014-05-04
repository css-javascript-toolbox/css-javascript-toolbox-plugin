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
	* 
	*/
	const ATTRIBUTES_SYSTEM_FLAG = 0x01;
	
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
		$type = $this->get('type');
		// Santiize the template name!
		$sanitizedName = strtolower(sanitize_file_name($this->get('name')));
		// Prefix all user templates so it woule be unique when added
		// to Wordpress queue!
		$queueName = "cjt-{$type}-template-{$sanitizedName}";
		$this->set('queueName', $queueName);
		return $this;
	}
	
} // End class.

// Initialize static's!
CJTTemplateTable::$states = array(
		'draft' => cssJSToolbox::getText('draft'),
		'published' => cssJSToolbox::getText('published'), 
		'trash' => cssJSToolbox::getText('trash'),
);