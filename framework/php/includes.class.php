<?php
/**
* 
*/

/**
* 
*/
class CJTIncludes implements ArrayAccess {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $name;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $list;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($name, $inits = array()) {
		$this->name = $name;
		// Copy all values!
		$this->setArray($inits);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $key
	* @param mixed $path
	*/
	public function add($key, $path) {
		$this[$key] = $path;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public static function addShared($name) {
		return self::$sharedList[$name]	= new CJTIncludes(null);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function clear() {
		$this->list = array();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $file
	*/
	public function exists($file) {
		$result = array();
		// Search all paths in reverve orders so override is allowed!
		end($this->list);
		do {
			$path = current($this->list);
			$fullPath = "{$path}/{$file}";
			if (file_exists($fullPath)) {
				$result['key'] =  key($this->list);
				$result['fullPath'] = $fullPath;
				break;
			}
		} while (prev($this->list));
		return $result;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function hasPaths() {
		return !empty($this->list);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $file
	*/
	public function import($file) {
		$result = false;
		if ($fileInfo = $this->exists($file)) {
			$result = require_once $fileInfo['fullPath'];
		}
		return $result;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $path
	*/
	public function offsetExists($key) {
		return isset($this->list[$key]);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $path
	*/
	public function offsetGet($key) {
		return $this->list[$key];
	}
			
	/**
	* put your comment there...
	* 
	* @param mixed $path
	*/
	public function offsetSet($key, $path) {
		$this->list[$key]  = $path; 
	}

	/**
	* put your comment there...
	* 
	* @param mixed $path
	*/
	public function offsetUnset($key) {
		unset($this->list[$key]);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $list
	*/
	protected function setArray(& $list) {
		foreach ($list as $key => $path) {
			$this[$key] = $path;
		}
	}
	
} // End class.