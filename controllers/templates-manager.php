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
class CJTTemplatesManagerController extends CJTAjaxController {

	/**
	* 
	*/
	const SESSIONED_FILTERS = 'cjt_templates__manager';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controllerInfo = array('model' => 'templates-manager', 'view' => 'templates/manager');
	
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
		$this->registryAction('changeState');
		$this->registryAction('linkExternal');
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function displayAction() {
		// Set default filters.
		if (!isset($_REQUEST['filter_states'])) {
			$_REQUEST['filter_states'] = 'published';
		}
		// Save all filters!
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$filters = array_intersect_key($_REQUEST, array_flip(explode(',', $_REQUEST['allFiltersName'])));
			update_user_option(get_current_user_id(), self::SESSIONED_FILTERS, $filters);			
		}
		else {
			// Load sessioned filter from database options table!
			$filters = (array) get_user_option(self::SESSIONED_FILTERS, get_current_user_id());
			$_REQUEST = array_merge($_REQUEST, $filters);
		}
		// Push inputs into the model!
		$this->model->inputs = $_REQUEST;
		// Display view.
		parent::displayAction();	
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function deleteAction() {
		$this->model->inputs['ids'] = $_GET['ids'];
		// Response with changed ids.
		$this->response['changes'] = $this->model->delete();
	}
	
	/**
	* put your comment there...
	* 
	*/	
	protected function changeStateAction() {
		$this->model->inputs['ids'] = $_GET['ids'];
		$this->model->inputs['state'] = $_GET['params'];
		$this->model->changeState();
		// Response with changed ids.
		$this->response['changes'] = $this->model->inputs['ids'];
	}
	
	/**
	* Link external Resources (CSS, HTML, JS and PHP)
	* 
	* The action is to create external link as CJT template
	* and link it to the target block.
	*/
	protected function linkExternalAction() {
		// Import dependencies.
		cssJSToolbox::import('includes:templates:templates.class.php');
		// Initialize response as successed until error occured!
		$this->response = array('code' => 0, 'message' => '');
		// List of all the external templates records to create!
		$externalTemplates = array();		
		// Read inputs.
		$externals = explode(',', $_REQUEST['externals']);
		$blockId = (int) $_REQUEST['blockId'];
		// Add as templates.
		foreach ($externals as $externalResourceURI) {
			// Use URI base name as Template Name and the extension as Template Type.
			$externalPathInfo = pathinfo($externalResourceURI);
			// Template Item.
			$item = array();
			$item['template']['name'] = $externalPathInfo['basename'];
			$item['template']['type'] = CJTTemplates::getExtensionType($externalPathInfo['extension']);
			$item['template']['state'] = 'published';
			// Get external URI code!
			$externalResponse = wp_remote_get($externalResourceURI);
			if ($error = $externalResponse instanceof WP_Error) {
				// State an error!
				$this->response['code'] = $externalResponse->get_error_code();
				$this->response['message'] = $externalResponse->get_error_message($this->response['code']);
				break;
			}
			else {
				// Read code content.
				$item['revision']['code'] = wp_remote_retrieve_body($externalResponse);
				// Add to the save list!
				$externalTemplates[] = $item;
			}
		}
		// Save all templates if no error occured
		// Single error will halt all the linked externals! They all added as  a single transaction.
		if (!$this->response['code']) {
			// Instantiate Template Models.
			$modelLookup = CJTModel::getInstance('templates-lookup');
			$modelTemplate = CJTModel::getInstance('template');
			$modelBlockTemplates  = CJTModel::getInstance('block-templates');
			// Add all templates.
			foreach ($externalTemplates as $item) {
				// Check existance.
				$modelTemplate->inputs['filter']['field'] = 'name';
				$modelTemplate->inputs['filter']['value'] = $item['template']['name'];
				if (!($existsItem = $modelTemplate->getTemplateBy()) || !property_exists($existsItem, 'id') || !$existsItem->id) {
					// Create template.
					$modelTemplate->inputs['item'] = $item;
					$item = (array) $modelTemplate->save();
				}
				else {
					// The returned item has 'id' field not 'templateId'!!
					$item = array('templateId' => $existsItem->id);
				}
				// Link only if not already linked!
				if (!$modelBlockTemplates->isLinked($blockId, $item['templateId'])) {
					// Link template to the block.
					$modelLookup->inputs['templateId'] = $item['templateId'];
					$modelLookup->inputs['blockId'] = $blockId;
					$modelLookup->link();
				}
			}
		}		
	}

} // End class.