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
class CJTPackagesController extends CJTAjaxController {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'packages');
	
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
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function deleteAction() {
		// Initialize.
		$model = $this->getModel('package');
		// Read inputs.
		$ids = $this->getRequestParameter('ids');
		// Process!
		if (is_array($ids) && count($ids)) {
			// Initialize response object.
			$this->response = array();
			// Delete all passed Ids!
			foreach ($ids as $id) {
				// Delete package.
				$model->delete($id);
				// List all the deleted packages!
				$this->response[] = $id;
			}
		}
	}

} // End class.