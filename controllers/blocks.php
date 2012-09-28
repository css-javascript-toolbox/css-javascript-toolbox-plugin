<?php
/**
* @version $ Id; blocks.php 21-03-2012 03:22:10 Ahmed Said $
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");

/**
* Blocks page controller.
* 
* @author Ahmed Said
* @version 6
*/
class CJTBlocksController extends CJTController {
	
	/**
	* Wordpress page id used to identify the page
	* and for associated meta data and some Wordpress options
	* to it.
	* 
	* @var string
	*/
	private $pageHookName = null;
	
	/**
	* Initialize controller object.
	* 
	* @see CJTController for more details.
	* @return void
	*/
	public function __construct($controllerInfo) {
		parent::__construct($controllerInfo);
	}
	
	/**
	* Display blocks page content.
	* 
	* The method directory output the content of the 
	* blocks page into the output buffer.
	* 
	* It doesn't nothing except getting the HTML template for
	* the blocks page and fill it with the exists blocks.
	* 
	* @return void
	*/
	public function indexAction() {
		// Prepare backupId in case backup is restored.
		$backupId = filter_input(INPUT_GET, 'backupId', FILTER_SANITIZE_NUMBER_INT);
		$blocks['filters']['type'] = 'block';
		// If backupId is not provided it must be NULL in the filter,
		$blocks['filters']['backupId'] = $backupId ? $backupId : null;
		// Push data to the view.
		$this->view->blocks = $this->model->getBlocks(null, $blocks['filters']);
		$this->view->order = $this->model->getOrder();
		$this->view->backupId = $blocks['filters']['backupId'];
		$this->view->securityToken = $this->createSecurityToken();
		// page hook is added later after this object is already created.
		// Get page hook directrly from controllers.
		$this->view->pageHook = 'cjtoolbox';
		// Output the view.
		echo $this->view->display();
	}

} // End class.