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
		$this->params[$name] = array('name' => $name, 'type' => $type, 'default' => $default);
		// Chaining.
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function get($name) {
		return $this->uParams[$name];
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
	*/
	public function json() {
		// Internal json trnsformer object caching!
		cssJSToolbox::import('framework:developer:interface:block:parameters:transform:json:json.php');
		return new CJT_Framework_Developer_Interface_Block_Parameters_Transform_Json($this);
	}

	/**
	* put your comment there...
	* 
	*/
	public function validate() {
		// Get params definition copy!
		$dParams = $this->params;
		// Validate passed parameters agianst the definition.
		foreach ($this->uParams as $name => $value) {
			// Check parameter existance.
			if (!isset($this->params[$name])) {
				// Warn user that the parameter is not exists!
				echo cssJSToolbox::getText("Invalid Block Shortcode parameters [{$name}]");
				// Remove from list.
				unset($this->uParams[$name]);
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