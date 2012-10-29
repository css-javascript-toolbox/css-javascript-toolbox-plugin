<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTTemplateModel {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $inputs;
	
	/**
	* put your comment there...
	* 
	*/
	public function getItem() {
		// Import template table class.
		cssJSToolbox::import('tables:template.php');
		// Load Template record from database.
		$template = new CJTTemplateTable('xx');
		$template->load($this->inputs->id);
		// Error loading template record!
		if ($template->id) {
			throw new Exception('Error loading Template');
		}
		// Return PHP StdClass object.
		return $template->getStdObject();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function save() {
		// Save item into database.
		
	}
	
} // End class.