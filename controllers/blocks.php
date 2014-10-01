<?php
/**
* @version $ Id; blocks.php 21-03-2012 03:22:10 Ahmed Said $
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* Blocks page controller.
* 
* @author Ahmed Said
* @version 6
*/
class CJTBlocksController extends CJTController {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'blocks', 'view' => 'blocks/manager');
	
	/**
	* Wordpress page id used to identify the page
	* and for associated meta data and some Wordpress options
	* to it.
	* 
	* @var string
	*/
	private $pageHookName = null;
	
	/**
	* put your comment there...
	* 
	*/
	public function extensionsAction() {
		parent::displayAction();
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
		$blocks['filters']['returnCodeFile'] = true;
		// Push data to the view.
		$this->view->blocks = $this->model->getBlocks(null, $blocks['filters']);
		$this->view->order = $this->model->getOrder();
		$this->view->backupId = $blocks['filters']['backupId'];
		$this->view->securityToken = $this->createSecurityToken();
		// page hook is added later after this object is already created.
		// Get page hook directrly from controllers.
		$this->view->pageHook = CJTPlugin::PLUGIN_REQUEST_ID;
		// Output the view.
		echo $this->view->display();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function installAction() {
		// Initialize.
		$model = $this->getModel('installer');
		// Do fresh installation if the installed version and the 
		// current version doesn't share the same release and edition signs.
		if ($model->isUpgrade() && $model->isCommonRelease()) {
			$this->request['layout'] = 'upgrade';
		}
		echo parent::displayAction();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function notInstalledNoticeAction() {
		$model = $this->getModel('installer');
		// Diplay notice only if not dismissed!
		if (!$model->dismissNotice()) {
			echo parent::displayAction();	
		}
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function setupAction() {
		echo parent::displayAction();
	}
	
} // End class.