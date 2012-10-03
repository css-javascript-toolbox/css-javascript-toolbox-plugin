<?php
/**
* @version controller.inc.php
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

/**
* CJT controller base class.
*/
abstract class CJTController {
	
	/**  */
	const NONCE_ACTION = 'cjtoolbox';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $defaultAction = 'index';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $model = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $view = null;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($controllerInfo) {
		$this->controllerInfo = $controllerInfo;
		/// Create view & Model.
		$this->init();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function _doAction() {
		// Call default action only if not needed by derivded class.
		$action = isset($_GET['action']) ? $_GET['action'] : $this->defaultAction;
		if ($action) {
			$actionHandler = "{$action}Action";
			$this->$actionHandler();
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public static function create($name) {
		$controllerInfo = cssJSToolbox::$controllers[$name];
		// Import controller file.
		$pathToControllers = CJTOOLBOX_CONTROLLERS_PATH;
		$controllerFile = "{$pathToControllers}/{$controllerInfo['controller']}.php";
		require $controllerFile;
		// Get controller class name.
		$class = self::getClassName($name, 'Controller');
		// Instantiate controller class.
		return new $class($controllerInfo);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function createSecurityToken() {
		return wp_create_nonce(self::NONCE_ACTION);
	}
	
	/**
	* Use CJTModel::create instead.
	* 
	* @deprecated No longer used.
	*/
	public static function getModel($name, $params = array()) {
		$model = null;
		$pathToModels = CJTOOLBOX_MODELS_PATH;
		// Import model file.
		$modelFile = "{$pathToModels}/{$name}.php";
		require_once $modelFile;
		// Create model object.
		$modelClass = self::getClassName($name, 'Model');
		if (!class_exists($modelClass)) {
			throw new Exception("Model class {$modelClass} doesn't exists!!!");
		}
		$model = new $modelClass($params);
		return $model;
	}
	
	/**
	* @deprecated No longer used.
	*/
	public static function getClassName($name, $type) {
		$className = '';
		// Every word start with uppercase character.
		$sanitizedName = ucfirst(str_replace(array('-', '_'), ' ', "{$name} {$type}"));
		// Remove spaces.
		$sanitizedName = str_replace(' ', '', $sanitizedName);
		$className = "CJT{$sanitizedName}";
		return $className;
	}
	
	/**
	* 
	* Use CJTView:create instrad.
	* 
	* @deprecated
	*/
	public static function getView($path) {
		$view = null;
		// Import view file.
		$viewInfo = self::getViewInfo($path);
		require_once $viewInfo['viewFile'];
		// Create view object.
		$name = str_replace(' ', '', ucwords(str_replace('/', ' ',$path)));
		$viewClass = self::getClassName($name, 'view');
		$view = new $viewClass($viewInfo);
		return $view;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getViewInfo($path) {
		// Path to views dir.
		$pathToViews = CJTOOLBOX_VIEWS_PATH;
		// Get view name.
		$name = basename($path);
		// View info struct.
		$viewInfo = array(
			'name' => $path,
			'url' => (CJTOOLBOX_VIEWS_URL . "/{$path}"),
			'path' =>  "{$pathToViews}/{$path}",
			'viewFile' => "{$pathToViews}/{$path}/view.php",
		);
		return $viewInfo;
	}
	/**
	* put your comment there...
	* 
	*/
	protected function init() {
		// Create default model.
		if (isset($this->controllerInfo['model'])) {
			$this->model = CJTModel::create($this->controllerInfo['model']);
		}
		// Create default view.
		if (isset($this->controllerInfo['view'])) {
			$this->view = self::getView($this->controllerInfo['view']);
			// Push model into view.
			$this->view->setModel($this->model);
		}
	}
	
} // End class.