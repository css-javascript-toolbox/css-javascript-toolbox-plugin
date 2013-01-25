<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTErrorsController extends CJTController {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'errors');
	
	/**
	* put your comment there...
	* 
	*/
	protected function indexAction() {
		echo parent::displayAction();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function trackAction() {
		// Register custom error handler!
		set_error_handler(array(&$this, 'phpErrorHandler'), E_ERROR | E_WARNING | E_USER_ERROR | E_USER_WARNING);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $number
	* @param mixed $message
	* @param mixed $file
	* @param mixed $line
	* @param mixed $context
	*/
	public function phpErrorHandler($number, $message, $file, $line, $context) {
		$processed = false;
		$model =& $this->model;
		// Handle only error belongs (lay down under plugin directory) to our plugin!
		if ($model->isProduction() && ($processed = (strpos($file, CJTOOLBOX_PATH) === 0))) {
			// Log error!
			$error = $model->log($number, $message, $file, $line, $context);
		}
		// True if processed o FALSE otherwise!
		return $processed;
	}
	
} // End class.