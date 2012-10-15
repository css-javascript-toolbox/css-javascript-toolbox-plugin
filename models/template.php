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
	public static function generateLocalGUID() {
		
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getItem() {
		// Import template table class.
		cssJSToolbox::import('tables:template.php');
		// Load Template record from database.
		$template = new CJTTemplateTable('xx');
		$template->load($this->inputs->guid);
		// Error loading template!
		if ($template->guid) {
			throw new Exception('Error loading Template');
		}
		// Return PHP StdClass object.
		return $template->getStdObject();
	}

	/**
	* put your comment there...
	* 	
	*/
	public function update(& $item) {
		// Add new template
		if (!$item->guid) {
			$guid = $this->generateLocalGUID();// Generate GUID.
		}
		else {
			// Update exists.
		}
	}
	
} // End class.