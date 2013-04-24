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
	* @param mixed $package
	* @param mixed $objects
	* @return CJTxTable
	*/
	public function save($package, $types) {
		// Add package.
		$tablePackage = CJTxTable::getInstance('package')
										->setData($package)
										->save();
		// Add package objects map.
		$tablePackageObjects = CJTxTable::getInstance('package-objects');
		// Fetch objects under each type (block ,template).
		foreach ($types as $type => $objects) {
			// For each types get all object IDs!
			foreach ($objects as $object) {
				$tablePackageObjects->setItem() // Reset is needed as the object used accross multiple records
																																// and might cause interfering between records!
																						 ->setData($object)
																						 ->set('packageId', $tablePackage->get('id'))
																						 ->set('objectType', $type)
																						 ->save();
			}
		}
	}

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
			// Unlink only-linked-templates!
			// Block doesn't unlink templates linked to it!
			// Delete all the templates linked to our block!
			$linkedOnlyTemplatesQuery = 'DELETE FROM #__cjtoolbox_block_templates
																												 WHERE blockId IN(%s)';
			$dbd->exec(sprintf($linkedOnlyTemplatesQuery, implode(',', $blockIds)));
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

} // End class.

// Hookable!
CJTPackageModel::define('CJTPackageModel', array('hookType' => CJTWordpressEvents::HOOK_FILTER));