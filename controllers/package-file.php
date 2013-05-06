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
class CJTPackageFileController extends CJTAjaxController {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'package-file');
	
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
		$this->registryAction('install');
	}

	/**
	* put your comment there...
	* 
	*/
	protected function installAction() {
		// Initialize.
		$model =& $this->model;
		// Unzip and Parse package file when uploaded.
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Get uploaded file.
			$file = $_FILES['fileToUpload'];
			try {
				// Parse/Unzip+Parse definition the package file.
				$package = $model->parse($file['name'], $file['tmp_name']);
				// Install package
				$packageId = $model->install($package);
			}
			catch (Exception $exception) {
				$this->model->setState('error', array('msg' => $exception->getMessage()));
			}
		}
		// Display uploader!
		parent::displayAction();
	}

} // End class.