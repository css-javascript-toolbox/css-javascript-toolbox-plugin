<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');

// Import dependencies.
cssJSToolbox::import('framework:db:mysql:xtable.inc.php');
		
/**
* 
*/
class CJTPackageModel extends CJTHookableClass {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $params = array();

	/**
	* Delete single package.
	* 
	* This method is going to delete the package
	* and all the templates and blocks associated to it,
	* therefor it'll unlink/break-down the relationship
	* between those templates and blocks.
	* 
	* @param Integer Package Id.
	* @return CJTPackageModel Return $this.
	*/
	public function delete($id) {
		// Initialize.
		$modelTemplates = CJTModel::getInstance('templates-manager');
		$dbd = cssJSToolbox::getInstance()->getDBDriver();
		$assoObjectsQueryTmp = 'SELECT objectId 
																								FROM #__cjtoolbox_package_objects 
																								WHERE packageId = %d AND objectType = "%s" AND relType = "%s";';
		// Delete the package.
		CJTxTable::getInstance('package')
										->set('id', $id)
										->delete();
		// Delete blocks.
		$blockIds = array_keys($dbd->select(sprintf($assoObjectsQueryTmp, $id, 'block', 'add')));
		if (!empty($blockIds)) {
			// Delete blocks!
			CJTModel::getInstance('blocks')
		 								->delete($blockIds)
										->save();
		}
		// Delete templates.
		$modelTemplates->inputs['ids'] = array_keys($dbd->select(sprintf($assoObjectsQueryTmp, $id, 'template', 'add')));
		// Templates muct be in trash state before deleted!
		$modelTemplates->inputs['state'] = 'trash';
		// Move to Trash + Delete only if there is at least one Id!
		empty($modelTemplates->inputs['ids']) OR ($modelTemplates->changeState() AND $modelTemplates->delete());
		// Delete package objects map.
		CJTxTable::getInstance('package-objects')
									 ->set('packageId', $id)
									 ->delete(array('packageId'));
	}

	/**
	* put your comment there...
	* 
	* @param mixed $packageName
	*/
	public function exists($packageName) {
		// Load by name.
		$tablePackage = CJTxTable::getInstance('package')
																								->set('name', $packageName)
																								->load(array('name'));
		// The object table if exists FALSE otherwise.
		return ($tablePackage->get('id') ? $tablePackage : FALSE);
	}

	/**
	* put your comment there...
	* 
	*/
	public function getFileContent() {
		// Get package ID.
		$packageId = $_REQUEST['packageId'];
		// Get Field/File to read.
		$file = $this->getParam('file');
		// LOAD file content from database.
		$tblPackage = CJTxTable::getInstance('package');
		$content = $tblPackage->set('id', $packageId)
																					 ->load(array('id'))
																					 ->get($file);
		// Return file content.
		return $content;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function getParam($name) {
		 return isset($this->params[$name]) ? $this->params[$name] : null;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $package
	*/
	public function save($package) {
		// Add package.
		return CJTxTable::getInstance('package')
										->setData($package)
										->save()->get('id');
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	*/
	public function setParam($name, $value) {
		// Set internal parameter value.
		$this->params[$name] = $value;
		// Chaining.
		return $this;
	}

} // End class.

// Hookable!
CJTPackageModel::define('CJTPackageModel', array('hookType' => CJTWordpressEvents::HOOK_FILTER));