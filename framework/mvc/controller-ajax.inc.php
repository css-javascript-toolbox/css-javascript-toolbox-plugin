<?php
/**
* 
*/

/**
* 
*/
abstract class CJTAjaxController extends CJTController {
	
  /** */
	const ACTION_PREFIX = 'wp_ajax_cjtoolbox_';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $methodName;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $actionsMap = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $defaultCapability = array('administrator');
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $impersonated = false;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $httpCode = '200 OK';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $httpContentType = 'text/plain';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onauthorize = array('parameters' => array('authorized'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onregisteraction  = array('parameters' => array('callback', 'action'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onresponse =  array('hookType' => CJTWordpressEvents::HOOK_ACTION);
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public $response = false;
	
	/**
	* put your comment there...
	* 
	*/
	public function _doAction() {
		// Authorize request.
		$authorized = $this->onauthorize(check_ajax_referer(self::NONCE_ACTION, 'security', false));
		if (!$authorized || !call_user_func_array('current_user_can', $this->defaultCapability)) {
			$this->httpCode = "403 Not Authorized";
		}
		else {
			// Clear Wordpress scripts object when using AJAX
			// This is to solve conflict caused by other Plugins
			// That uses admin_init hook to enqueue their scripts
			// Ajax request here is only for CJT no other Plugin
			// should involve any scripts with CJT
			// Remove all scritps enqueued before CJT Ajax Controller (this) received
			// the controller!
			global $wp_scripts;
			$wp_scripts = new WP_Scripts();
			// Dispatch action.
			$action = current_filter();
			// Get method name from frrom Wordpress action name!
			$method = $this->ongetactionname(str_replace(self::ACTION_PREFIX, '', $action));
			// Get method name from action name.
			$method = ucfirst(str_replace('_', ' ', $method));
			$method = str_replace(' ', '', $method);
			// Lower case the first character.
			$method = strtolower($method{0}) . substr($method, 1);
			// Cahe method name for child classes to use!
			$this->methodName = $method;
			// Relying on the trailer "Action" for security.
			// Derivded class should not never use trailer "Action"
			// for internal methods.
			$method = "{$method}Action";
			// If its mapped from child classed redirect the call!
			if ((isset($this->actionsMap[$action]) && ($use = $action)) || (isset($this->actionsMap[$method]) && ($use = $method))) {
				$method = $this->actionsMap[$use];
			}
			// Filter callback method and args.
			$callback = $this->oncallback((object) array('method' => array($this, $method), 'args' => func_get_args()), $action);
			// Call Action Method.
			if (!is_callable($callback->method)) {
				$this->httpCode = '403 Not supported action';
			}
			else {
				// When there are no arguments passed to Wordpress action.
				// do_action function pass an empty string parameter at index 0.
				if (count($callback->args) == 1 && ($callback->args[0] == '')) {
					$callback->args = array();
				}
				call_user_func_array($callback->method, $callback->args);
				// Controller loaded with Wordpress typical Ajax request (e.g meta-box-order).
				// We shouldn't output anything in these cases.
				if ($this->impersonated) {
					return;
				}
			}
		}
		// Set HTTP headers.
		header("HTTP/1.0 {$this->httpCode}");
		header("Content-Type: {$this->httpContentType}");
		// Allow filtering any response data/header!
		$this->onresponse();
		// Output type based on the content type MIME.
		switch ($this->httpContentType) {
		  case 'text/plain':
		  	echo json_encode($this->response);
		  	break;
		  	
		  default :
		  	echo $this->response;
		}
		die();
	}

	/**
	* Display templates manager form.
	* 
	*/
	protected function displayAction() {		
		// Return view.
		$this->httpContentType = 'text/html';
		$this->response = parent::displayAction();
	}
	
	/**
	* Redirect the request to another controller.
	* 
	* Why this method is created anyway is to allow
	* deprecating old controllers and start to create new one
	* a quiet manner!
	* 
	* The idea is to create the  new controller,  adding new Action there
	* and redirect the call throught current deprecated controller.
	* 
	* @param mixed $controller
	*/
	protected function redirect($controller) {
		// Initialize vars.
		$currentFilter= current_filter();
		// Remove current Action!
		remove_action($currentFilter, array(&$this, '_doAction'));
		// activate the target CTR!
		CJTController::getInstance($controller);
		// Fire the action manually.
		do_action($currentFilter);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $action
	* @param mixed $priority
	* @param mixed $paramsCount
	*/
	protected function registryAction($action, $priority = 10, $paramsCount = 1, $prefix = self::ACTION_PREFIX) {
		$action = "{$prefix}{$action}";
		$callback = $this->onregisteraction(array(&$this, '_doAction'), $action);
		// Adding action!
		add_action($action, $callback , $priority, $paramsCount);
		return $this;
	}
	
} // End class.

// Hookable!
CJTAjaxController::define('CJTAjaxController', array('hookType' => CJTWordpressEvents::HOOK_FILTER));