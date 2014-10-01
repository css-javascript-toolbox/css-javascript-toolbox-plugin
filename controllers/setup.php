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
class CJTsetupController extends CJTAjaxController {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'setup');
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		parent::__construct();
		// Register actions!
		$this->registryAction('activationFormView');
		$this->registryAction('getState');		
		$this->registryAction('license');
		$this->registryAction('reset');
	}
	
	/**
	* Display Activation form!
	* 
	*/
	protected function activationFormViewAction() {
		// Display activation for for the requested component!
		parent::displayAction();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function getStateAction() {
		# Get component product types
		$licenseTypes = $this->model->getExtensionProductTypes($_REQUEST['component']);
		# Return license state back
		$this->response = $this->model->getStateStruct($licenseTypes);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function licenseAction() {
		// Read request parameters!
		$action = $_REQUEST['eddAction'];
		$state['component'] = $_REQUEST['component'];
		$state['license'] = $_REQUEST['license'];
		// Initializing!
		$model =& $this->model;
		// Request EDD through EDD SL APIs!
		$state['response'] = $model->dispatchEddCall($action, $state['component'], $state['license']);
		// Standarize response object! As check and activate responde by 'valid' and 'invalid' and deactivate responde
		// by deactivated and faild we then need to make them all likce check and activate!
		if (($action == 'deactivate') && ($state['response']['license'] != 'error')) {
			$map = array('deactivated' => 'valid', 'failed' => 'invalid');
			$deactivateResponseState = $state['response']['license'];
			$state['response']['license'] = $map[$deactivateResponseState];
		}
		// Cahe only if the request is 'activate' or 'deactivate' and the returned state is valid or 'deactivated! respectively.
		if (($action != 'check') && ($state['response']['license'] == 'valid')) {
			// We need to standarize the response object so the access will be always the same
			// EDD return 'deactivated' and 'valid' with the success request using 'deactivate' and 'activate' repectively!
			// Use valid instead of deactivate!
			if ($action == 'deactivate') {
				$state['response']['license'] = 'valid';
			}
			$state['action'] = $model->cacheState($state['component'], $action, $state);
		}
		// Return state object includes (component, license, edd response [and action only if valid])
		$this->response = $state;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function resetAction() {
		// Initializing!
		$model =& $this->model;
		// Read request parameters!
		$state['component'] = $_REQUEST['component'];
		$state['license'] = false;
		// Remove License state cache!
		$state['response']['license'] = $model->removeCachedLicense($state);
		// Set response parameters.
		$state['action'] = 'reset';
		// With DUMMY EDD response so we're standarizing the response for all actions
		// even those not belongs to EDD real requests!!
		$state['response']['item_name'] = $state['component']['name'];
		$this->response = $state;
	}
	
} // End class.
