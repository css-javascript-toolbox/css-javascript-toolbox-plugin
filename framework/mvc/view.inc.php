<?php
/**
* @version view.inc.php
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

/**
* CJT view base class.
*/
abstract class CJTView extends CJTHookableClass {
	
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
	protected $oncreated = array(
		'hookType' => CJTWordpressEvents::HOOK_ACTION,
		'parameters' => array('info'),
	);
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $oncreateview = array(
		'parameters' => array('view')
	);
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $ongetmodel = array(
		'parameters' => array('model')
	);

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onimporthelper = array(
		'parameters' => array('file'),
	);
		
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onimporttemplate = array(
		'parameters' => array('file'),
	);
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onloadtemplate = array
	(
		'parameters' => array
		( 
			'content',
			'file',
			'dir',
		),
	);

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onsetmodel = array(
		'parameters' => array('model'),
	);
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $ontemplateparameters =  array(
		'parameters' => array('params', 'name', 'dir', 'extension'),
	);
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $onusescripts = array(
		'parameters' => array('scripts'),
	);
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $onusestyles = array(
		'parameters' => array('styles'),
	);
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $params = null;
	
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
	private $viewInfo = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $views = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $info
	* @param mixed $params
	* @return CJTView
	*/
	public function __construct($info, $params = null) {
		// Initialize vars!
		$this->viewInfo = $info;
		$this->params = $params ? $params : array();
		// Initialize events engine!
		parent::__construct();
		// Fire created event!
		$this->oncreated($info);
	}
	
	/**
	* Create view object.
	* 
	* @deprecated Use CJTView::getInstance().
	*/
	public static function create($view) {
		return self::trigger('CJTView.createview', CJTController::getView($view));
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $view
	* @param mixed $params
	*/
	public static function getInstance($view, $params = null) {
		return self::trigger('CJTView.createview', CJTController::getView($view, $params));
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getModel($name = null) {
		// Instantiate model if required!
		if ($name) {
			$this->model = CJTModel::getInstance($name);
		}
		return $this->ongetmodel($this->model);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getParam($name) {
		return isset($this->params[$name]) ? $this->params[$name] : null;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $destination
	*/
	public function getPath($destination) {
		return self::getViewPath($this->viewInfo['name'], $destination, $this->viewInfo['viewsPath']);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getRequest() {
		return $this->request;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	*/
	public function getRequestParameter($name) {
		return isset($this->request[$name]) ? $this->request[$name] : null;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getTemplate($name, $params = array(), $dir = 'tmpl', $extension = null) {
		// Initialize defaults.
		if ($extension === null) {
			$extension = '.html.tmpl';
		}
		// filter parameters.
		$params = $this->ontemplateparameters($params, $name, $dir, $extension);
		// Push params into the local scope.
		extract($params);
		// Templates collected under the view/tmpl directory.
		$templateFile = $this->getPath("{$dir}/{$name}{$extension}");
		// Get template content into variable.
		ob_start();
		require $this->onimporttemplate($templateFile);
		$template = $this->onloadtemplate( ob_get_clean(), $name, "{$dir}/{$name}" );
		return $template;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getURI($destination) {
		return self::getViewURI($this->viewInfo['name'], $destination, $this->viewInfo['viewsUrl']);
	}	
	
	/**
	* put your comment there...
	* 
	* @param mixed $view
	* @param mixed $destination
	*/
	public static function getViewPath($view, $destination, $overrideViewPath = null) {
		# Use passed view path or use CJT constants if not passed
		if (!$overrideViewPath) {
			$overrideViewPath = CJTOOLBOX_VIEWS_PATH;
		}
		$viewPath = $overrideViewPath . DIRECTORY_SEPARATOR . $view;
		$destination = $destination ? "/{$destination}" : '';
		return "{$viewPath}{$destination}";
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $view
	* @param mixed $destination
	* @param mixed $overrideViewUrl
	*/
	public static function getViewURI($view, $destination, $overrideViewUrl = null) {
		if (!$overrideViewUrl) {
			$overrideViewUrl = CJTOOLBOX_VIEWS_URL;
		}
		$viewURI = "{$overrideViewUrl}/{$view}/public";
		return "{$viewURI}/{$destination}";
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $file
	* @param mixed $destination
	*/
	public static function getURIFromViewFile($file, $destination) {
		$path = dirname($file);
		$viewPath = str_replace((CJTOOLBOX_VIEWS_PATH . '/'), '', $path);
		return self::getViewURI($viewPath, $destination);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function importHelper($name, $helperDirectory = 'helpers') {
		$helperPath = "{$this->viewInfo['path']}/{$helperDirectory}/{$name}.inc.php";
		require_once $this->onimporthelper($helperPath);
	}
	
	/**
	* 
	*/
	public static function import($path) {
		$viewInfo = CJTController::getViewInfo($path);
		// Import view.
		require_once $viewInfo['viewFile'];
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $model
	*/
	public function setModel($model) {
		$this->model = $this->onsetmodel($model);
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $request
	*/
	public function setRequest(& $request) {
		$this->request = $request;
		return $this;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	*/
	public function setRequestParameter($name, $value) {
		$this->request[$name] = $value;
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function & suppressPrintScriptsHook() {
		// Access Global.
		global $wp_actions;
		// Mark as triggered.
		$wp_actions['wp_print_scripts'] = true;
		// Chain.
		return $this;
	}
	/**
	* put your comment there...
	* 
	*/
	protected static function useScripts($className, $scripts = null) {
		wp_enqueue_script('Just Load Default Scripts, this works great!!!!');
		// Use current class name is $className is not provided!
		if (!$className) {
			$className = __CLASS__;
		}
		// Accept variable number of args of script list.
		$allPassedArgs = func_get_args();
		$scripts = self::trigger("{$className}.usescripts", (is_array($scripts) ? $scripts : array_slice($allPassedArgs, 1)));
		$stack =& $GLOBALS['wp_scripts']->registered;
		if (!$scripts) {
			throw new Exception('CJTView::useScripts method must has at least on script parameter passed!');
		}
		// Script name Reg Exp pattern.
		$nameExp = '/\:?(\{((\w+)-)\})?([\w\-\.]+)(\(.+\))?(\;(\d))?$/';
		// For every script, Enqueue and localize, only if localization file found/exists.
		foreach ($scripts as $script) {
			// Get script name.
			preg_match($nameExp, $script, $scriptObject);
			// [[2]Prefix], [4] name. Prefix may be not presented.
			$name = "{$scriptObject[2]}{$scriptObject[4]}";
			$location = isset($scriptObject[7]) ? $scriptObject[7] : null;
			$scriptParameters = isset($scriptObject[5]) ? $scriptObject[5] : '';
			if (!isset($stack[$name])) {
				// Any JS lib file should named the same as the parent folder with the extension added.
				// Except files with _ at the begning
				$libPath = ':' . ((strpos($scriptObject[4], '_') === 0) ? substr($scriptObject[4], 1) : "{$scriptObject[4]}:{$scriptObject[4]}");
				// Pass virtual path to getURI and resolvePath to
				// get JS file URI and localization file path.
				$jsFile = cssJSToolbox::getURI(preg_replace($nameExp, "{$libPath}.js", $script));
				$localizationFile = cssJSToolbox::resolvePath(preg_replace($nameExp, "{$libPath}.localization.php", $script));
				// Enqueue script file.
				wp_enqueue_script($name, $jsFile, null, null, $location);
				// Set script parameters.
				if (preg_match_all('/(\w+)=(\w+)/', $scriptParameters, $params, PREG_SET_ORDER) ) {
					// Set parameters.
					foreach ($params as $param) {
						$stack[$name]->cjt[$param[1]] = $param[2];
					}
					// Initialize CJT for the script data object.
					// This object caryy other informations so that the other
					// Plugin parts/components can use it to know how script file work.
					$stack[$name]->cjt = (object) $stack[$name]->cjt;
				}
				// If localization file exists localize JS.
				if (file_exists($localizationFile)) {
					// Get localization text array.
					$localization = require $localizationFile;
					// Object name is the script name with .'s and -'s stripped.
					// Capitalize first char after each - or . and append I18N postfix.
					$objectName = str_replace(' ', '', ucwords(str_replace(array('.', '-'), ' ', "{$name}I18N")));
					// Ask Wordpress to localize the script file.
					wp_localize_script($name, $objectName, $localization);
				}
			}
			// Enqueue already registered scripts!
			else {
				wp_enqueue_script($name, null, null, null, $location);
			}
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function useStyles($className, $styles = null) {
		wp_enqueue_style('Just Load Default Styles, this works great!!!!');
		// Use current class name is $className is not provided!
		if (!$className) {
			$className = __CLASS__;
		}
		// Accept variable number of args of script list.
		$allPassedArgs = func_get_args();
		$styles = self::trigger("{$className}.usestyles", (is_array($styles) ? $styles : array_slice($allPassedArgs, 1)));
		if (!$styles) {
			throw new Exception('CJTView::useStyles method must has at least on script parameter passed!');
		}
		// Script name Reg Exp pattern.
		$nameExp = '/\:?(\{((\w+)-)\})?([\w\-\.]+)$/';
		// For every script, Enqueue and localize, only if localization file found/exists.
		foreach ($styles as $style) {
			// Get script name.
			preg_match($nameExp, $style, $styleObject);
			// [[2]Prefix], [4] name. Prefix may be not presented.
			$name = "{$styleObject[2]}{$styleObject[4]}";
			if (!isset($GLOBALS['wp_styles']->registered[$name])) {
				// Make all enqueued styles names unique from enqueued scripts.
				// This is useful when merging styles & scripts is required.
				$name = "CSS-{$name}";
				// Any JS lib file should named the same as the parent folder with the extension added.
				$libPath = ":{$styleObject[4]}";
				// Get css file URI.
				$cssFile = cssJSToolbox::getURI(preg_replace($nameExp, "{$libPath}.css", $style));
				// Register + Enqueue style.
				wp_enqueue_style($name, $cssFile);
			}
			else {
				// Enqueue already registered styles.
				wp_enqueue_style($name);
			}
		}
	}
	
} // End class.

// Initialize CJTView Event!
CJTView::define('CJTView', array('hookType' => CJTWordpressEvents::HOOK_FILTER));