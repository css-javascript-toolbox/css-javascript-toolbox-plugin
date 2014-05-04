<?php
/**
* 
*/

/**
* 
*/
class CJT_Framework_View_Block_Parameter_Grouper_Factory {
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $params
	*/
	public function create($name, $params) {
		// Upper case first letter.
		$name = ucfirst($name);
		// Build class name.
		$className = "CJT_Framework_View_Block_Parameter_Grouper_{$name}_{$name}";
		// Inistntiate grouper object.
		return new $className($params);
	}

} // End class.
