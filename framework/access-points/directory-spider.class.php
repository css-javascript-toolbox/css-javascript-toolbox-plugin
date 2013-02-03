<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTAccessPointsDirectorySpider extends ArrayIterator {
	
	/**
	* 
	*/
	const CACHE_POINTER = 'settings.CJTAccessPointsDirectorySpider.cache';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $aPoints;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dir;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $prefix;
	
	/**
	* put your comment there...
	* 
	* @param mixed $dir
	* @return CJTAccessPoints
	*/
	public function __construct($prefix, $dir) {
		// Initialize specifc!
		$this->prefix = $prefix;
		$this->dir = $dir;
		// Load access points!
		$this->load();
		// ArrayIterator.
		parent::__construct($this->aPoints);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @param mixed $dir
	*/
	public static function getInstance($prefix, $dir) {
		return new CJTAccessPointsDirectorySpider($prefix, $dir);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $reload
	* @return CJTAccessPointsDirectorySpider
	* @todo Set realod = false when released!
	*/
	protected function load($reload = false) {
		// Get if cached and not force reload!
		if (!($this->aPoints = get_option(self::CACHE_POINTER)) || $reload) {
			// Reset access points!
			$this->aPoints =array();
			// Get all defined ap inside the specified directory!
			$accessPoints = new DirectoryIterator($this->dir);
			foreach ($accessPoints as $file) {
				if (!$file->isDir()) {
					// Build point info!
					$point = array();
					$point['file'] = $file->getFilename();
					$point['name'] = $file->getBaseName('.accesspoint.php');
					$point['class'] = "{$this->prefix}{$point['name']}AccessPoint";
					// Add to points list!
					$this->aPoints[$point['name']] = $point;
				}
			}			
			// Update the cache;
			update_option(self::CACHE_POINTER, $this->aPoints);
		}
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function point() {
		// Get access point info!
		$point =& $this[$this->key()];
		// Full absolulte path to access point file!
		$absPath = "{$this->dir}/{$point['file']}";
		// Instantiate point class, this will put it in action!
		require_once $absPath;
		return new $point['class']();
	}
	
} // End class.
