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
		$this->registryAction('getShortcode');
		$this->registryAction('getBlockParametersForm');
		// @TODO: $this->defaultCapability is risky if  there is any admin actions added later, please remove!
		$this->defaultCapability = array('edit_posts', 'edit_pages');
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function getBlockParametersFormAction() {
		// Get block id.
		$blockId = (int) $_REQUEST['blockId'];
		// Get block form!
		$form = CJTxTable::getInstance('form')
																	->setTableKey(array('blockId'))
																	->setData(array('blockId' => $blockId))
																	->load();
		// Set HTTP header
		$this->httpContentType = 'text/html';
		// Display form view.
		if ($form->get('blockId')) {
			// Load parameters form.
			$groups = new CJT_Models_Block_Parameters_Form_Groups($blockId);
			$params = new CJT_Models_Block_Parameters_Form_Parameters($blockId);
			$blockParams = new CJT_Framework_View_Block_Parameter_Parameters($params);
			// Return view content!
			$paramsView = CJTView::getInstance('tinymce/params', array(
				'groups' => $groups,
				'params' => $blockParams->getParams(),
				'form' => $form->getData(),
				'blockId' => $blockId
				)
			);
			// Return paramters form content!
			$this->response = $paramsView->getTemplate('default');			
		}
		else { // Error loading form!
			$this->response = cssJSToolbox::getText('The requested Block doesnt has parameters form!');
		}
	}

	/**
	* put your comment there...
	* 
	*/
	protected function getBlocksListAction() {
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
	
	/**
	* put your comment there...
	* 
	*/
	protected function getShortcodeAction() {
		// Get block id.
		$blockId = $_REQUEST['blockId'];
		// Load block
		$block = CJTxTable::getInstance('block')
																	->setTableKey(array('id'))
																	->setData(array('id' => $blockId))
																	->load()
																	->getData();
		$blockForm = (array) CJTxTable::getInstance('form')
																	->setTableKey(array('blockId'))
																	->setData(array('blockId' => $blockId))
																	->load()
																	->getData();
		// Load block parameters.
		$parameters = new CJT_Models_Block_Parameters_Parameters($blockId);
		$blockParams = new CJT_Framework_Developer_Interface_Block_Shortcode_Parameters_Parameters($parameters);
		// The the block has FORM associated 
		if (isset($blockForm['blockId'])) {
			// If the data is submitted then load the parameters object with the post data!
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// Get RAW data from the data submitted (avoid magic_quotes, Why called MAGIC!!!!)!
				$rawPostData = filter_input(INPUT_POST, 'form-data', FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
				// Fill the form by the posted data
				$blockParams->setValue($rawPostData);
				// Validate the submitted data against their parameters.
				if ($blockParams->validate()) {
					// Generate and Return Shortcode string!
					 $this->response['state'] = 'shortcode-notation';
					 $this->response['content'] = $blockParams->shortcode($block->name);
				}
				else {
					 $this->response['state'] = 'invalid';
					 $this->response['content'] = $blockParams->getMessages();					
				}
			}
			// Redirect to the parameters form view!
			else {
				$this->response['state'] = 'show-form';
			}
		}
		else {
			// If block doesn't has a parameters defined return
			// the Shortcode code string from the parameters loader.
			 $this->response['state'] = 'shortcode-notation';
			 $this->response['content'] = $blockParams->shortcode($block->name);
		}
	}

} // End class.
