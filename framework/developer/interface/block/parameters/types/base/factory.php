<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Parameters_Types_Base_Factory {
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $definition
	* @param mixed $factory
	* @return CJT_Framework_Developer_Interface_Block_Parameters_Types_Interface_Type
	*/
	public function create($name, $definition, $factory = null) {
		// Default factory to this!
		if ($factory === null) {
			$factory = $this;
		}
		// Get type class name.
		$className = 'CJT_Framework_Developer_Interface_Block_Parameters_Types_' . ucfirst($name);
		// Instantiate!
		return new $className($definition, $factory);
	}

} // End class
