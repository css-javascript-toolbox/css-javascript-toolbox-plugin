<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// import dependencies.
cssJSToolbox::import('framework:mvc:controller-ajax.inc.php');

/**
* This class should replace any other controllers that
* has methods for interacting with a single Block (e.g block-ajax!)
* 
* All single Block actions (e.g edit, new and save) should be placed/moved here
* in the future!
*/
class CJTBlockController extends CJTAjaxController {

	/**
	* put your comment there...
	* 	
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'x-block', 'model_file' => 'xblock');
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		parent::__construct();
		// Actions!
		$this->registryAction('getBlockBy');
		$this->registryAction('getAPOP');
		$this->registryAction('loadUrl');
		$this->registryAction('getCode');
		$this->registryAction('downloadCodeFile');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function downloadCodeFileAction() {
		// BlockId, currentActiveFile.
		$blockId = $_GET['blockId'];
		$fileId = $_GET['fileId'];
		$returnAs = $_GET['returnAs'];
		// Get current File Code.
		$tblCodeFile = new CJTBlockFilesTable(cssJSToolbox::getInstance()->getDBDriver());
		$codeFile =	$tblCodeFile->set('id', $fileId)
														->set('blockId', $blockId)
														->load()
														->getData();
		// Return as downloadable-file or JSON.
		if ($returnAs == 'file') {
			// Get Download File info.
			$extension = $codeFile->type ? cssJSToolbox::$config->templates->types[$codeFile->type]->extension : 'txt';
			$file = "{$codeFile->name}.{$extension}";
			// Response Header parameters.
			header('Content-Description: File Transfer');
			header("Content-Disposition: attachment; filename=\"{$file}\""); //<<< Note the " " surrounding the file name
			header('Content-Transfer-Encoding: binary');
			header('Connection: Keep-Alive');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . strlen($codeFile->code));
			// AJAX Controller parameters.
			$this->httpContentType = 'application/octet-stream';
		}
		// Output code.
		$this->response = $codeFile->code;
	}

	/**
	* Query single block based on the provided criteria!
	* 
	*/
	public function getBlockByAction() {
		// Initialize.
		$returns = array_flip($_GET['returns']);
		// Set inputs.
		$inputs =& $this->model->inputs;
		$inputs['filter'] = $_GET['filter'];
		// Query Block.
		$this->response = array_intersect_key((array) $this->model->getBlockBy(), $returns);
	}
	
	/**
	* Get assigment panel objects page.
	* 
	*/
	public function getAPOPAction() {
		// Read inputs.
		$iPerPage = (int) $_GET['iPerPage'];
		$blockId = (int) $_GET['block'];
		$oTypeParams = $_GET['typeParams'];
		$offset = $_GET['index'];
		$assignedOnly = ($_GET['assignedOnly'] == 'false') ? false : true;
		$initialize = ($_GET['initialize'] == 'false') ? false : true;
		// Get the corresponding type object
		// for handling the request.
		$typeName = $oTypeParams['targetType'];
		/**
		* put your comment there...
		* 
		* @var CJT_Models_Block_Assignmentpanel_Base
		*/
		$typeObject = CJT_Models_Block_Assignmentpanel_Base
								::getInstance($assignedOnly, 
															$typeName,
															$offset, 
															$iPerPage, 
															$blockId, 
															$oTypeParams);
		// Fetch next page.
		$items = $typeObject->getItems();
		// Return result
		$this->response['count'] = count($items);
		$this->response['items'] = $items;
		// Return count only when the list is activated for
		// the first time.
		if ($initialize) {
			$this->response['total'] = $typeObject->getTotalCount();	
		}
	}
	
	/**
	* put your comment there...
	* 
	* @deprecated this is just a redirect to the CJTBlockContoller::getAction().
	*/
	protected function loadUrlAction() {
		// Read inputs.
		$url = $_GET['url'];
		// Read URL.
		$response = wp_remote_get($url);
		if ($error = $response instanceof WP_Error) {
			// State an error!
			$this->response['errorCode'] = $response->get_error_code();
			$this->response['message'] = $response->get_error_message($response['code']);
			break;
		}
		else {
			// Read code content.
			$this->response['content'] = wp_remote_retrieve_body($response);
		}
	}

} //  End class.
