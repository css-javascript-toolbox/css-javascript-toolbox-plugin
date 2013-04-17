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
class CJTPackageController extends CJTAjaxController {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'package');
	
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
		$this->registryAction('info');
		$this->registryAction('edit');
	}

} // End class.