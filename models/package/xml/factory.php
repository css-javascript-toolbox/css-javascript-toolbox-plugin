<?php
/**
* 
*/

/**
* 
*/

class CJT_Models_Package_Xml_Factory {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $basePath = '';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $yields = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $basePath
	* @return CJT_Models_Package_Xml_Factory
	*/
	public function __construct($basePath) {
		$this->basePath = $basePath;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $current
	* @param mixed $path
	* @param mixed $node
	*/
	public function create($current, $path, $node) {
		// Get object element class name
		$classPath = "{$this->basePath}/{$path}";
		$className = 'CJT_' . str_replace(' ', '_', ucwords(str_replace('/', ' ', $classPath)));
		// Instantiate class.
		$object = new $className($node, $current, $this);
		$objectPath = $object->virtualPath();
		// Cache all instantiated objects so it can be accessed 
		// anywhere inside!
		if (!isset($this->yields[$objectPath])) {
			$this->yields[$objectPath] = array();
		}
		$this->yields[$objectPath][] = $object;
		// Return new object.
		return $object;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getBasePath() {
		return $this->basePath;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $class
	*/
	public function getClassRelativePath($class) {
		// Get class path.
		$classPath = strtolower(str_replace('_', '/', $class));
		// Remove base path from the class path.
		$classRelativePath = str_replace("cjt/{$this->basePath}/", '', $classPath);
		// returns
		return $classRelativePath;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $path
	*/
	public function & getCreatedObjects($path) {
		// Initialize.
		$objects = array();
		/// E_ALL complans
		if (isset($this->yields[$path])) {
			$objects = $this->yields[$path];
		}
		// Return objects.
		return $objects;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $class
	* @param mixed $path
	*/
	public function obtainRelativePath($class, $innerPath) {
		// Get class path relative to the package doc.
		$classRelativePath = $this->getClassRelativePath($class);
		// Build child relatoive path.
		$path = "{$classRelativePath}/{$innerPath}";
		return $path;
	}

} // End class.