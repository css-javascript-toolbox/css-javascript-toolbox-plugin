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
class CJTCodeFilesController extends CJTAjaxController {

	/**
	* put your comment there...
	* 	
	* @var mixed
	*/
	protected $controllerInfo = array();
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		parent::__construct();
		// Actions!
		$this->registryAction('getList');
		$this->registryAction('delete');
		$this->registryAction('save');
		$this->registryAction('switch');
	}

	/**
	* put your comment there...
	* 
	*/
	public function deleteAction() {
		// Block id.
		$blockId = (int) $_GET['blockId'];
		$ids = $_GET['ids'];
		// Fetch code Blocks list.
		$tblCodeFiles = new CJTBlockFilesTable(cssJSToolbox::getInstance()->getDBDriver());
		// Delete all
		foreach ($ids as $id) {
			$tblCodeFiles->set('blockId', $blockId)
									 ->set('id', $id)
									 ->delete();
		}
		// Response with list and codeFileIds.
		$this->response['blockId'] = $blockId;
		$this->response['ids'] = $ids;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getListAction() {
		// Block id.
		$blockId = (int) $_GET['blockId'];
		//; Fetch code Blocks list.
		$tblCodeFiles = new CJTBlockFilesTable(cssJSToolbox::getInstance()->getDBDriver());
		$result = $tblCodeFiles->set('blockId', $blockId)->fetchAll();
		foreach ($result as $codeFile) {
			$codeFilesList[] = array(
				'name' => $codeFile['name'],
				'description' => $codeFile['description'],
				'type' => $codeFile['type'],
				'tag' => $codeFile['tag'],
				'id' => $codeFile['id'],
				'order' => $codeFile['order'],
			);
		}
		// Response with list and blockId.
		$this->response['blockId'] = $blockId;
		$this->response['list'] = $codeFilesList;
	}

	/**
	* put your comment there...
	* 
	*/
	public function saveAction() {
		// Block id.
		$blockId = (int) $_POST['blockId'];
		$codeFile = filter_input(INPUT_POST, 'codeFile', FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
		// Add blockId to codeFile record.
		$codeFile['blockId'] = (int) $blockId;
		// Get Code Files Table.
		$tblCodeFiles = new CJTBlockFilesTable(cssJSToolbox::getInstance()->getDBDriver());
		// Fill
		$tblCodeFiles->setData($codeFile)
		// Update or insert.
		->save(false, true);
		// Return New CodeFile Data.
		$this->response = (array) $tblCodeFiles->getData();
	}
 
	/**
	* put your comment there...
	* 
	*/
	public function switchAction() {
		// Block id.
		$blockId = (int) $_GET['blockId'];
		$fileId =(int) $_GET['codeFileId'];
		// Read Code File Record.
		$tblCodeFiles = new CJTBlockFilesTable(cssJSToolbox::getInstance()->getDBDriver());
		$codeFile = $tblCodeFiles->set('blockId', $blockId)
														 ->set('id', $fileId)
														 ->load()
														 ->getData();
		// Set author Active Code File Id.
		// TODO: Setting Active File ID should be in the MODEL!
		update_user_meta(get_current_user_id(), "cjt_block_active_file_{$blockId}", $fileId);
		// Return code file.
		$this->response = $codeFile;
	}

} //  End class.
