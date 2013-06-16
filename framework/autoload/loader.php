<?php
/**
* 
*/


// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* Auto load CJT classes.
* 
* @since 6.2
*/
class CJT_Framework_Autoload_Loader {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $path = null;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $prefix = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @param mixed $path
	* @return CJT_Autoload
	*/
	public function __construct($prefix, $path) {
		// Initialize.
		$this->prefix = $prefix;
		$this->path = $path;
		// Resgister SPL auto load function.
		spl_autoload_register(array($this, 'loadClass'));
	}

	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @param mixed $path
	*/
	public static function autoLoad($prefix = null, $path = null) {
		// Hold all auto load instances.
		static $instances = array();
		// Identify instances by prefixes!
		if (!isset($instances[$prefix])) {
			$instances[$prefix] = new CJT_Framework_Autoload_Loader($prefix, $path);
		}
		return $instances[$prefix];
	}

	/**
	* put your comment there...
	* 
	* @param mixed $class
	*/
	public function loadClass($class) {
		// Any class start with our prefix should be auto loaded!
		if (strpos($class, "{$this->prefix}_") === 0) {
			// Get class paths.
			$classFile = $this->getClassAbsolutePath($this->getClassComponent($class));
			// Import class file.
			require $classFile;
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $clsName
	*/
	public function getClassComponent($clsName) {
		// Initialize.
		$component = (object) array();
		// Lowerize!
		$clsName = strtolower($clsName);
		// Get class frags by gettign all names between _ character.
		$fragments = explode('_', $clsName);
		// Get components / exclude the prefix @ index [0].
		// Name.
		$component->name = $fragments[1];
		// Get path (after the prefix and just before the name!).
		$component->path = array();
		for ($frag = 1; $frag < (count($fragments) - 1); $frag++) {
			$component->path[] = $fragments[$frag] ;
		}
		$component->path = join(DIRECTORY_SEPARATOR, $component->path);
		// Name
		$component->name = end($fragments);
		// Fulle absolute path.
		return $component;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $classInfo
	*/
	public function getClassAbsolutePath($classInfo) {
		// fullpath = abspath + classpath + classname + .php
		$fullPath = $this->path . DIRECTORY_SEPARATOR . 
											$classInfo->path . DIRECTORY_SEPARATOR . $classInfo->name . '.php';
		return $fullPath;
	}

} // End class.