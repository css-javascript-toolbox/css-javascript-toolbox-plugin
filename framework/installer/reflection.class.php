<?php
/**
* 
*/

/**
* 
*/
class CJTInstallerReflection {
	
	/**
	* 
	*/
	const ROOT_INSTALLER = 'CJTInstaller';
	
	/**
	* 
	*/
	const ROOT_UPGRADER = 'CJTUpgrader';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public static $excludeList = array('__construct', '__destruct', 'getInstance');
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $filters;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $installerClass;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $operations;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $rootClass;
	
	/**
	* put your comment there...
	* 
	* @param mixed $installerClass
	* @return CJTInstallerReflection
	*/
	public function __construct($installerClass, $rootClass, $filters) {
		$this->installerClass = $installerClass;
		$this->rootClass = $rootClass;
		$this->filters = $filters;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $installerClass
	* @param mixed $rootClass
	* @param mixed $filters
	*/
	public static function getInstance($installerClass, $rootClass = self::ROOT_INSTALLER, $filters = ReflectionMethod::IS_PUBLIC) {
		return new CJTInstallerReflection($installerClass, $rootClass, $filters);
	}
	
	/**
	* Get all Non-Static Public methods below $this->rootClass.
	* 
	* @return array
	*/
	public function getOperations() {
		// Load operations if not loaded yet!
		if ($this->operations === null) {
			$this->operations = array();
			// Only get operations from parent classes if Root class is not the target class!
			if ($this->installerClass != $this->rootClass) {
				// Get all parent class until $this->rootClass, discard other classes!
				$parents = array_reverse(array_keys(class_parents($this->installerClass)));
				$targetClasses = array_slice($parents, array_search($this->rootClass, $parents));;
			}
			$targetClasses[] = $this->installerClass;
			// reflect class methods!
			$reflection = new ReflectionClass($this->installerClass);
			foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				$methodName = $method->getName();
				// Get all Public, Non-Static, belong to any of the target Classes and includes in self::$excludeList variable!
				$static = $method->isStatic();
				$excluded = in_array($methodName, self::$excludeList);
				$targeted = in_array($method->getDeclaringClass()->getName(), $targetClasses);
				if (!$static && !$excluded && $targeted) {
					$this->operations[$methodName] = array('name' => $methodName);
					// Read Installer Reflection attributes!
					if (preg_match_all('/\@CJTInstallerReflection\<([^\>]+)\>/', $method->getDocComment(), $rawAttributes)) {
						foreach ($rawAttributes[1] as $rawAttribute) {
							$rawAttribute = explode('=', $rawAttribute);
							$this->operations[$methodName]['attributes'][$rawAttribute[0]] = $rawAttribute[1];
						}
					}
				}
			}
		}
		return $this->operations;
	}
		
	/**
	* put your comment there...
	* 
	*/
	public function getInstallerClass() {
		return $this->installerClass;
	}
	
} // End class.