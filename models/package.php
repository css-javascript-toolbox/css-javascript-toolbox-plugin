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
			foreach ($objects as $objectId) {
				$tablePackageObjects->set('packageId', $tablePackage->get('id'))
																						 ->set('objectType', $type)
																						 ->set('objectId', $objectId)
																						 ->save();
			}
		}
	}
	
} // End class.

// Hookable!
CJTPackageModel::define('CJTPackageModel', array('hookType' => CJTWordpressEvents::HOOK_FILTER));