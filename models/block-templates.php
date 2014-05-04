<?php
/**
* 
*/

// Disllow direct access.
defined('ABSPATH') or die('Access denied');

/**
* 
*/
class CJTBlockTemplatesModel extends CJTHookableClass {

	/**
	* Check weather a template is linked to specific block.
	* 
	* Check database block_templates table for record with the given ids.
	* 
	* @param Integer Block Id.
	* @param Integer Template Id.
	* @return boolean TRUE if linked, FALSE if not.
	*/
	public function isLinked($blockId, $templateId)	{
		// Try to load block template table with the requested Ids.
		$tableBlockTemplate = CJTxTable::getInstance('block-template');
		$tableBlockTemplate->set('blockId', $blockId)
																			 ->set('templateId', $templateId)
																			 ->load(array('blockId', 'templateId'));
		// Return TRUE if the blockId and the TemplateId returned from the load process!
		return ($tableBlockTemplate->get('blockId') && $tableBlockTemplate->get('templateId'));
	}

} // End class.

// Hookable!
CJTBlockTemplatesModel::define('CJTBlockTemplatesModel');