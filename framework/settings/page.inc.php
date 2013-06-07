<?php
/**
* 
*/

/**
*  
*/
abstract class CJTSettingsPage {
	
	/**
	* 
	*/
	const PREFIX = 'cjt-settings';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $fullName = '';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $page = '';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $prefix = '';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $settings;
	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @return CJTSettingsModel
	*/
	public function __construct($page, $prefix = self::PREFIX) {
		$this->page = $page;
		$this->prefix = $prefix;
		// Build full name.
		$this->fullName = "{$prefix}.{$page}";
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $property
	*/
	public function __get($property) {
		return isset($this->settings->{$property}) ? $this->settings->{$property} : null;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $property
	* @param mixed $value
	*/
	public function __set($property, $value) {
		$this->settings->{$property} = $value;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function load() {
		$settings = get_option($this->fullName);
		if (!$settings) {
			$settings = array();
		}
		$this->settings = (object) $settings;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function clear() {
		$this->settings = (object) array();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		return $this->page;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $data
	*/
	public function set($data) {
		$this->settings = (object) $data;	
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function update() {
		update_option($this->fullName, $this->settings);
	}
	
} // End class.