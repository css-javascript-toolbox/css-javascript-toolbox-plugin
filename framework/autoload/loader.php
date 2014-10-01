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
	* 
	* 
	* @var mixed
	*/
	protected static $instances = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $map;
	
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
		$this->map = new ArrayObject(array());
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
		// Identify instances by prefixes!
		if (!isset(self::$instances[$prefix])) {
			self::$instances[$prefix] = new CJT_Framework_Autoload_Loader($prefix, $path);
		}
		return isset(self::$instances[$prefix]) ? self::$instances[$prefix] : null;
	}
	
	/**
	* Find ClassAutoloader instance used for loading specific class.
	* 
	* The method is using Prefix algorithm for finding class autoloader
	* 
	* @param mixed $className
	*/
	public static function & findClassLoader($className) {
		# Getting class prefix
		$classComponents = explode('_', $className);
		$prefix = $classComponents[0];
		# Find autoloader
		$instance = isset(self::$instances[$prefix]) ? self::$instances[$prefix] : null;
		return $instance;
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

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getClassFile($name, $file) {
		// Get class component.
		$component = $this->getClassComponent($name);
		// Get class absolute path.
		$path = $this->path . DIRECTORY_SEPARATOR . $component->path;
		return $path . DIRECTORY_SEPARATOR . $file;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getPath() {
		return $this->path;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getPrefix() {
		return $this->prefix;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $class
	*/
	public function loadClass($class) {
		$classFile = '';
		// First, check the map!
		if ($this->map()->offsetExists($class)) {
			$classMappedPath = $this->map()->offsetGet($class);
			$classFile = $this->path . DIRECTORY_SEPARATOR . $classMappedPath;
		}
		// Any class start with our prefix should be auto loaded!
		else if (strpos($class, "{$this->prefix}_") === 0) {
			// Get class paths.
			$classFile = $this->getClassAbsolutePath($this->getClassComponent($class));
		}
		// Whatever a class file is set, import it!
		if ($classFile && file_exists($classFile)) {
			require $classFile;	
		}
	}
  
	/**
	* put your comment there...
	* 
	*/
	public function map() {
		return $this->map;
	}
	
} // End class.
