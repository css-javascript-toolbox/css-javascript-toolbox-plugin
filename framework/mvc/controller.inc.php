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
	protected $action;
	
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
	protected $request;
	
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
	* @param mixed $hasView
	* @param mixed $request
	* @return CJTController
	*/
	public function __construct($hasView = null, $request = null) {
		// Initialize hookable!
		parent::__construct();
		// Read request parameters.
		$this->request = array_merge(((array) $_REQUEST), ((array) $request));
		// Create default model.
		if (isset($this->controllerInfo['model'])) {
			// E_ALL complain!
			if (!isset($this->controllerInfo['model_file'])) {
				$this->controllerInfo['model_file'] = null;
			}
			$this->model = CJTModel::create($this->controllerInfo['model'], array(), $this->controllerInfo['model_file']);
		}
		// Create default view.
		if ($hasView === null) { // Default value for $hasView = true
			// Request/passed parameters has priority over controller default view!
			$view = $this->ongetviewname(isset($this->request['view']) ? $this->request['view'] :
																												(isset($this->controllerInfo['view']) ? $this->controllerInfo['view'] : null)
			);
			if ($view) {
				$this->view  = self::getView($view)
				// Push data into view.
				->setModel($this->model)
				->setRequest($this->request);
			}
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function _doAction() {
		// Force use of internal action untless its empty
		// then look for submitted action or get the default!
		$action = $this->action ? $this->action : 
													(isset($_GET['action']) ? $_GET['action'] : $this->defaultAction);
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
	* @deprecated Use CJTController::getInstance() instead!
	* 
	* @param mixed $name
	* @param mixed $request
	*/
	public static function create($name, $hasView = null, $request = null) {
		// Import controller file.
		$pathToControllers = CJTOOLBOX_CONTROLLERS_PATH;
		$controllerFile = "{$pathToControllers}/{$name}.php";
		require_once self::trigger('CJTController.loadcontroller', $controllerFile, $name);
		// Get controller class name.
		$class = self::getClassName($name, 'Controller');
		// Instantiate controller class.
		return new $class($hasView, $request);
	}
	
	/**
	* put your comment there...
	* 
	* @deprecated Use cssJSToolbox::createSecurityToken
	*/
	public function createSecurityToken() {
		return wp_create_nonce(self::NONCE_ACTION);
	}

	/**
	* put your comment there...
	* 
	*/
	protected function displayAction() {
		// Get view layout!
		$layout = isset($this->request['layout']) ? $this->request['layout'] : 'default';
		ob_start();
		$this->view->display($layout);
		$content = ob_get_clean();
		return $content;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $hasView
	* @param mixed $request
	*/
	public static function getInstance($name, $hasView = null, $request = null) {
		return self::create($name, $hasView, $request);
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
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getRequestParameter($name) {
		return $this->request;	
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
	* @param mixed $action
	*/
	public function setAction($action) {
		$this->action = $action;
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	*/
	public function setRequestParameter($name, $value) {
		$this->request[$name]	= $value;
		return $this;
	}
	
} // End class.

// Hookable!
CJTController::define('CJTController', array('hookType' => CJTWordpressEvents::HOOK_FILTER));