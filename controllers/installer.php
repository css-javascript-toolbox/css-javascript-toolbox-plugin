<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// import dependencies.
cssJSToolbox::import('framework:mvc:controller-ajax.inc.php');

/**
* 
*/
class CJTInstallerController extends CJTAjaxController {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'installer');
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		parent::__construct();
		// Register actions!
		$this->registryAction('install');
		$this->registryAction('dismissNotice');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function dismissNoticeAction() {
		$this->model->dismissNotice(true);
		$this->response = true;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function installAction() {
		// Get model object!
		$model =& $this->model;
		// Installa requested operation.
		$input['operation'] = $_REQUEST['operation'];
		$this->response = $model->setInput($input)->install();
	}
	
} // End class.
