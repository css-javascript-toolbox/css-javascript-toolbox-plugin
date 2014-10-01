<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Package
extends CJT_Models_Package_Xml_Definition_Abstract {

	/**
	* Do nothing!
	* 
	*/
	public function transit() {
		// Initialize.
		$register = $this->register();
		$modelPackage = CJTModel::getInstance('package');
		// Fetch package information with all readme and license tags locatted if
		// bundled with external files!
		$packageInfo = $register['packageParser']->getItem(
			$this->getNode(), 
			array('readme' => 'readme.txt', 'license' => 'license.txt')
		);
		// Add package to database!
		$register['packageId'] = $modelPackage->save($packageInfo);
		// Chain.
		return $this;
	}
	
} // End class