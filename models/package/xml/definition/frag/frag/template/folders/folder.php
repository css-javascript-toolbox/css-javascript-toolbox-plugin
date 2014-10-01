<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Template_Folders_Folder
extends CJT_Models_Package_Xml_Definition_Abstract {
	
	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		// Initialize.
		$register = $this->register();
		$node = $this->getNode();
		$fileSystem =& $GLOBALS['wp_filesystem'];
		// Get absolute path to template directory!
		$templateDirectory = ABSPATH . dirname($register['templateFile']);
		// Get <folders> tag common path.
		$foldersPath = (string) $this->getParent()->getNode()->attributes()->path;
		// Folder absolute path.
		if ($detinationName = (string) $node->attributes()->destination) {
			$folderPath =  $detinationName;	
		}
		else {
			$folderPath = $node->attributes()->path;
		}
		$folderAbsPath = $register['packageParser']->getDirectory() . "/{$foldersPath}/{$folderPath}";
		// Create destination path.
		$folderDestinationPath = "{$templateDirectory}/{$folderPath}";
		if (!file_exists($folderDestinationPath)) {
			mkdir($folderDestinationPath, 0775);	
		}
		// Copy files (FLAT)!!.
		foreach (new DirectoryIterator($folderAbsPath) as $file) {
			if (!$file->isDot() && $file->isFile()) {
				$fileSystem->copy($file->getPathName(), "{$folderDestinationPath}/{$file->getFileName()}");
			}
		}
		// Chain.
		return $this;
	}
	
} // End class