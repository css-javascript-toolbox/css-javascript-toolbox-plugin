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
	* @var CJT_Framework_Developer_Interface_Block_Parameters
	*/
	protected $params = null;

	/**
	* put your comment there...
	* 
	* @param CJT_Framework_Developer_Interface_Block_Parameters $params
	* @return CJT_Framework_Developer_Interface_Block_Parameters_Transform_Json
	*/
	public function __construct($params) {
		$this->params = $params;
	}

	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		// Initialize.
		$json = '';
		// Get all passed parameters as array!
		$params =& $this->params->getArray();
		$definition =& $this->params->getDefinition();
		// Get values for all parameters required to be represented as json object.
		foreach ($params as $name => & $value) {
			// Cast to the correct type.
			$type = $definition[$name]['type'];
			// Get JSON for both array and objec data types.
			if (($type == 'array') || ($type == 'object')) {
				$value = json_decode($value);
			}
			else { // Cast.
				settype($value, $type);
			}
		}
		// Javascript Object Notation.
		return json_encode((object) $params);
	}

} // End class.