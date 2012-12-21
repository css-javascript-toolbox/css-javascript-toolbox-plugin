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
abstract class CJTController extends CJTHookableClass {
	
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
	protected $oncallback = array('parameters' => array('callback', 'action', 'args'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $ongetactionname = array('parameters' => array('action'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $ongetclassname = array('parameters' => array('class', 'name', 'type'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $ongetviewname = array('parameters' => array('view'));
		
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $onloadcontroller = array('parameters' => array('file', 'name'));
		
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
	public function __construct() {
		// Initialize hookable!
		parent::__construct();
		// Create default model.
		if (isset($this->controllerInfo['model'])) {
			$this->model = CJTModel::create($this->controllerInfo['model'], array(), $this->controllerInfo['model_file']);
		}
		// Create default view.
		$view = $this->ongetviewname($_REQUEST['view'] ? $_REQUEST['view'] : $this->controllerInfo['view']);
		if ($view) {
			$this->view = self::getView($view);
			// Push model into view.
			$this->view->setModel($this->model);
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function _doAction() {
		// Call default action only if not needed by derivded class.
		$action = isset($_GET['action']) ? $_GET['action'] : $this->defaultAction;
		// filter action name!
		$action = $this->ongetactionname($action);
		if ($action) {
			$actionHandler = "{$action}Action";
			// filter callback
			$callback = $this->oncallback(array($this, $actionHandler), $action);
			// Callback!
			call_user_func($callback);
		}
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @deprecated Use CJTController::getInstance()!
	*/
	public static function create($name) {
		// Import controller file.
		$pathToControllers = CJTOOLBOX_CONTROLLERS_PATH;
		$controllerFile = "{$pathToControllers}/{$name}.php";
		require self::trigger('CJTController.loadcontroller', $controllerFile, $name);
		// Get controller class name.
		$class = self::getClassName($name, 'Controller');
		// Instantiate controller class.
		return new $class();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function createSecurityToken() {
		return wp_create_nonce(self::NONCE_ACTION);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public static function getInstance($name) {
		return self::create($name);
	}
	
	/**
	* Use CJTModel::create instead.
	* 
	* @deprecated No longer used.
	*/
	public static function getModel($name, $params = array(), $file = null) {
		$model = null;
		$pathToModels = CJTOOLBOX_MODELS_PATH;
		if (!$file) {
			$file = $name;
		}
		// Import model file.
		$modelFile = "{$pathToModels}/{$file}.php";
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
		// Filter.
		$className = self::trigger('CJTController.getclassname', "CJT{$sanitizedName}", $name, $type);
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
	
} // End class.

// Hookable!
CJTController::define('CJTController', array('hookType' => CJTWordpressEvents::HOOK_FILTER));