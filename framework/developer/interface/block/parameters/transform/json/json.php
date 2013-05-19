<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Parameters_Transform_Json {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $excludes = null;
	
	/**
	* put your comment there...
	* 
	* @var CJT_Framework_Developer_Interface_Block_Parameters
	*/
	protected $params = null;

	/**
	* put your comment there...
	* 
	* @param CJT_Framework_Developer_Interface_Block_Parameters $params
	* @return CJT_Framework_Developer_Interface_Block_Parameters_Transform_Json
	*/
	public function __construct($params, $excludes) {
		// Initialize.
		$this->params = $params;
		$this->excludes = explode(',', $excludes);
	}

	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		// Initialize.
		$params = array();
		// Get all passed parameters as array!
		// Remove excluded list.
		$uParams = array_diff_key($this->params->getArray(), array_flip($this->excludes));
		$definition =& $this->params->getDefinition();
		// Get values for all parameters required to be represented as json object.
		foreach ($uParams as $name => & $value) {
			// Use Character case as defined in the parameters
			// definition other than the user passed parameter name.
			// User parameter read in lowercase other than the original.
			$params[$definition[$name]['name']] = $value;
		}
		// Javascript Object Notation.
		return json_encode((object) $params);
	}

} // End class.