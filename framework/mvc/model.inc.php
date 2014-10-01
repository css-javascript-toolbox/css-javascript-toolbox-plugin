<?php
/**
* @version model.inc.php
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

/**
* CJT model base class.
*/
abstract class CJTModel {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $properties = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $values
	* @return CJTModel
	*/
	public function __construct($values) {
		$this->setValues($values);
	}
	
	/**
	* put your comment there...
	* 
	* @deprecated Use CJTModel::getInstance.
	*/
	public static function create($model, 
																$params = array(), 
																$file = null,
																$overrideModelsPath = null,
																$overrideModelsPrefix = null) {
		return self::getInstance($model, $params, $file, $overrideModelsPath, $overrideModelsPrefix);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $model
	* @param mixed $params
	* @param mixed $file
	*/
	public static function getInstance($model, 
																		 $params = array(), 
																		 $file = null,
																		 $overrideModelsPath = null,
																		 $overrideModelsPrefix = null) {
		return CJTController::getModel($model, $params, $file, $overrideModelsPath, $overrideModelsPrefix);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getValues() {
		return ((object)$this->properties);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $model
	*/
	public static function import($model) {
		$pathToModels = CJTOOLBOX_MODELS_PATH;
		// Import model file.
		$modelFile = "{$pathToModels}/{$model}.php";
		require_once $modelFile;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $values
	*/
	public function setValues($values) {
		foreach ($values as $name => $value) {
			$this->properties[$name] = $value;
		}
	}
	
} // End class.