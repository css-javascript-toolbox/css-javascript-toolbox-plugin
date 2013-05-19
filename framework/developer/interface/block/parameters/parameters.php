<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJT_Framework_Developer_Interface_Block_Parameters {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $params = array();

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $uParams = null;
	
	/**
	* put your comment there...
	* 
	* @param mixed $params
	* @return CJT_Framework_Developer_Interface_Block_Parameters
	*/
	public function __construct($params) {
		$this->uParams = $params;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $type
	* @param mixed $default
	*/
	public function add($name, $type, $default = null) {
		// Add to params definition list.
		// Use lower case for ket as Wordpress shortcode parameters
		// is always transformed to lowercase.
		$this->params[strtolower($name)] = array('name' => $name, 'type' => $type, 'default' => $default);
		// Chaining.
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function get($name) {
		// Allow writing it in the original case inside the code
		// however its only retrived by the lowercvase letter.
		return $this->uParams[strtolower($name)];
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getArray() {
		return $this->uParams;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getDefinition() {
		return $this->params;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $excludes
	* @return CJT_Framework_Developer_Interface_Block_Parameters_Transform_Json
	*/
	public function json($excludes = null) {
		// Internal json trnsformer object caching!
		cssJSToolbox::import('framework:developer:interface:block:parameters:transform:json:json.php');
		return new CJT_Framework_Developer_Interface_Block_Parameters_Transform_Json($this, $excludes);
	}

	/**
	* put your comment there...
	* 
	*/
	public function validate() {
		// Get params definition copy!
		$dParams = $this->params;
		// Validate passed parameters agianst the definition.
		foreach ($this->uParams as $name => & $value) {
			// Check parameter existance.
			if (!isset($this->params[$name])) {
				// Warn user that the parameter is not exists!
				echo cssJSToolbox::getText("Invalid Block Shortcode parameters [{$name}]");
				// Remove from list.
				unset($this->uParams[$name]);
			}
			// Get parameter type.
			$type = $dParams[$name]['type'];
			// Cast to correct type.
			switch ($type) {
				case 'array':
				case 'object':
					// Convert CJT JSON Array and Object to JSON object.
					$value = str_replace(array('{A', '{O', 'A}', 'O}'), array('[', '[', ']', ']'), $value);
					// Get PHP var for JSON text!
					$value = json_decode($value);				
				break;
				case 'boolean':
					// Case boolean from string.
					$value = ($value == "true") ? true: false;
				break;
				default:
					// PHP native cast.
				  settype($value, $type);
				break;
			}
			// As the parameter is passed by user don't need the default.
			unset($dParams[$name]);
		}
		// Get other parameters not passed by user!
		// Get only parameters with default value !== null.
		foreach ($dParams as $name => $param) {
			if ($param['default'] !== null) {
				$this->uParams[$name] = $param['default'];
			}
		}
		// Chaining.
		return $this;
	}
	
} // End class.