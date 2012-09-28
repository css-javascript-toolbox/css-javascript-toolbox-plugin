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
	protected $actionsMap = array();
	
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
	protected $httpCode = '200 OK';
	
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
	public $response = false;
	
	/**
	* put your comment there...
	* 
	*/
	public function _doAction() {
		// Authorize request.
		$authorized = check_ajax_referer(self::NONCE_ACTION, 'security', false);
		if (!$authorized) {
			$this->httpCode = "403 Not Authorized";
		}
		else {
			// Dispatch action.
			$action = current_filter();
			// Action name not specified by child class.
			if (!isset($this->actionsMap[$action])) {
				// Remove wp part
				$action = str_replace(self::ACTION_PREFIX, '', $action);
				// Get method name from action name.
				$method = ucfirst(str_replace('_', ' ', $action));
				$method = str_replace(' ', '', $method);
				// Lower case the first character.
				$method = strtolower($method{0}) . substr($method, 1);
				// Relying on the trailer "Action" for security.
				// Derivded class should not never use trailer "Action"
				// for internal methods.
				$method = "{$method}Action";
			}
			else {
				// Child class map the action to another method name.
				$method = $this->actionsMap[$action];
			}
			// Call Action Method.
			if (!method_exists($this, $method)) {
				$this->httpCode = '403 Not supported action';
			}
			else {
				$args = func_get_args();
				// When there are no arguments passed to Wordpress action.
				// do_action function pass an empty string parameter at index 0.
				if (count($args) == 1 && ($args[0] == '')) {
					$args = array();
				}
				call_user_func_array(array(&$this, $method), $args);
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
	* put your comment there...
	* 
	* @param mixed $action
	* @param mixed $priority
	* @param mixed $paramsCount
	*/
	protected function registryAction($action, $priority = 10, $paramsCount = 1, $prefix = self::ACTION_PREFIX) {
		$action = "{$prefix}{$action}";
		return add_action($action, array(&$this, '_doAction'), $priority, $paramsCount);
	}
	
} // End class.