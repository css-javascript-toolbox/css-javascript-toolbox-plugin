<?php
/**
* 
*/

// No direct access.
defined('ABSPATH') or die('Access denied');

// Import dependencies.
cssJSToolbox::import('framework:mvc:controller-ajax.inc.php');

/**
* 
*/
class CJTTinymceBlocksController extends CJTAjaxController {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'tinymce-blocks');
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($hasView = null, $request = null) {
		// Initialize parent!
		parent::__construct($hasView, $request);
		// Register actions!
		$this->registryAction('getBlocksList');
		// @TODO: $this->defaultCapability is risky if  there is any admin actions added later, please remove!
		$this->defaultCapability = array('edit_posts', 'edit_pages');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getBlocksListAction() {
		// Read all blocks!
		$blocks = $this->model->getItems();
		// retreive owener name!
		foreach ($blocks as $id => $block) {
			$user =  get_userdata($block->owner);
			$block->owner = !$user ? 'N/A' : $user->display_name;
			$this->response['list'][$id] = $block;
		}
		$this->response['count'] = count($this->response['list']);
	}
	
} // End class.
