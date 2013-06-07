<?php
/**
* 
*/

/**
* 
*/
class CJTSettingsModel {
	
	/**
	* 
	*/
	const SETTINGS_PAGE_DIR = 'settings';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $settingsPagesDir = self::SETTINGS_PAGE_DIR;
	
	/**
	* put your comment there...
	* 
	* @param mixed $page
	*/
	public function loadPage($name) {
		// Settings page file name.
		$pageFile = "{$name}.php";
		require_once "{$this->settingsPagesDir}/{$pageFile}";
		// Create settings page object.
		$name = str_replace(array('-', '_'), '', ucwords($name));
		$className = "CJTSettings{$name}Page";
		return new $className();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $settings
	*/
	public function save($settings) {
		// Each element represent a single settings page.
		foreach ($settings as $page => $data) {
			// Get page object.
			$pObject = $this->loadPage($page);
			// Update page settings.
			$pObject->set($data);
			// Save into the database.
			$pObject->update();
		}
	}
	
} // End class.
