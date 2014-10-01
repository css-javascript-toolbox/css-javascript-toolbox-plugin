<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Package_Xml_Definition_Frag_Frag_Block_Links_Link
extends CJT_Models_Package_Xml_Definition_Abstract {
	
	/**
	* put your comment there...
	* 
	*/
	public function transit() {
		// Initialize for linking templates.
		$register = $this->register();
		$link = $this->getNode();
		// Link block templates.
		// Get template object to link.
		$templateToLinkAttributes = (array) $link->attributes();
		$templateToLinkAttributes = $templateToLinkAttributes['@attributes'];
		$tblTemplate = CJTxTable::getInstance('template')
															->setData($templateToLinkAttributes) // Query by the given attributes.
															->load(array_keys($templateToLinkAttributes));
		if ($register['linkedTemplateId'] = $tblTemplate->get('id')) {
			// Always link as the block should be newely added
			// and in the normal cases its impossible to be alread linked!
			$tableBlockTemplate = CJTxTable::getInstance('block-template');
			$tableBlockTemplate->set('blockId', $register['blockId'])
												 ->set('templateId', $register['linkedTemplateId'])
												 ->save();
		}
		return $this;
	}
	
} // End class