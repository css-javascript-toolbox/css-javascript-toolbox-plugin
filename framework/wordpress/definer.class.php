<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTAccessPointsDefiner {
	
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
		$this->prefix = $prefix;
		$this->dir = $dir;	
	}
	
	/**
	* Define all access points found in a given directory.
	* 
	*/
	public function define() {
		// Iterate over all access points!
		$accessPoints = new DirectoryIterator($this->dir);
		foreach ($accessPoints as $file) {
			if (!$file->isDir()) {
				// Import access poitn file.
				require_once $file->getPathName();
				// Get access point class.
				$name = $file->getBaseName('.accesspoint.php');
				$class = "{$this->prefix}{$name}AccessPoint";
				$this->aPoints[$name] = new $class();
			}
		}
		return $this;
	}
	
	/**
	* Get access point that processed the request!
	* 
	*/
	public function getActive() {
		$active = null;
		// Search for active point that processed the request!
		foreach ($this->aPoints as $accessPoint) {			
			// Done, just exit!
			if ($accessPoint->isLoaded()) {
				$active = $accessPoint;
				break;
			}
		}
		return $active;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @param mixed $dir
	* @return CJTAccessPointsDefiner
	*/	
	public static function getInstance($prefix, $dir) {
		return new CJTAccessPointsDefiner($prefix, $dir);
	}
	
} // End class.
