<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import dependencies!
cssJSToolbox::import('includes:installer:upgrade:upgrade.class.php');

/**
* 
*/
class CJTV02Upgrade extends CJTUpgradeNonTabledVersions {
	
	/**
	* put your comment there...
	* 
	* @param mixed $blocks
	*/
	protected function getBlocksIterator($blocks) {
		// Import iterator class file!
		cssJSToolbox::import('includes:installer:upgrade:0.2:includes:block.class.php');
		// Instantiate blocks iterator object!
		return new CJTInstallerBlocks02($blocks);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getInstance() {
		return new CJTV02Upgrade();
	}
	
} // End class.