<?php
/**
* @version $ Id; ?FILE_NAME ?DATE ?TIME ?AUTHOR $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// import dependencies.
cssJSToolbox::import('framework:mvc:controller-ajax.inc.php');

/**
* 
* DESCRIPTION
* 
* @author ??
* @version ??
*/
class CJTTemplatesManagerController extends CJTAjaxController {

	/**
	* 
	*/
	const SESSIONED_FILTERS = 'cjt_templates__manager';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'templates-manager', 'view' => 'templates/manager');
	
	/**
	* 
	* Initialize new object.
	* 
	* @return void
	*/
	public function __construct() {
		// Initialize parent!
		parent::__construct();
		// Add actions.
		$this->registryAction('display');
		$this->registryAction('delete');
		$this->registryAction('changeState');
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function displayAction() {
		// Set default filters.
		if (!$_REQUEST['filter_states']) {
			$_REQUEST['filter_states'] = 'published';
		}
		// Save all filters!
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$filters = array_intersect_key($_REQUEST, array_flip(explode(',', $_REQUEST['allFiltersName'])));
			update_user_option(get_current_user_id(), self::SESSIONED_FILTERS, $filters);			
		}
		else {
			// Load sessioned filter from database options table!
			$filters = (array) get_user_option(self::SESSIONED_FILTERS, get_current_user_id());
			$_REQUEST = array_merge($_REQUEST, $filters);
		}
		// Push inputs into the model!
		$this->model->inputs = $_REQUEST;
		// Display view.
		parent::displayAction();	
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function deleteAction() {
		$this->model->inputs['ids'] = $_GET['ids'];
		// Response with changed ids.
		$this->response['changes'] = $this->model->delete();
	}
	
	/**
	* put your comment there...
	* 
	*/	
	protected function changeStateAction() {
		$this->model->inputs['ids'] = $_GET['ids'];
		$this->model->inputs['state'] = $_GET['params'];
		$this->model->changeState();
		// Response with changed ids.
		$this->response['changes'] = $this->model->inputs['ids'];
	}
	
} // End class.