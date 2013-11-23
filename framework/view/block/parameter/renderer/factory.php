<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_View_Block_Parameter_Renderer_Factory {
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $definition
	* @param mixed $factory
	* @return CJT_Framework_Developer_Interface_Block_Parameters_Types_Interface_Type
	*/
	public function create($name, $definition) {
		$name = ucfirst($name);
		// Get type class name.
		$className = "CJT_Framework_View_Block_Parameter_Renderer_{$name}_{$name}" ;
		// Instantiate!
		return new $className($definition, $this);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $class
	* @param mixed $file
	*/
	public function getClassFile($class, $file) {
		// Get absolute path to the class.
		$loader = CJT_Framework_Autoload_Loader::autoLoad('CJT');
		return $loader->getClassFile($class, $file);
	}

} // End class
