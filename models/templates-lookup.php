<?php
/**
* 
*/

/**
* 
*/
class CJTTemplatesLookupModel {
	
	
	/**
	* put your comment there...
	* 
	*/
	public function getItems()	{
		// Initialize variables.
		foreach (cssJSToolbox::$config->templates->types as $typeId => $object) {
			$arrangedItems[$typeId] = array();
		}
		$tManager = CJTModel::create('templates-manager');
		// Quey all templates with the last revision!
		$items = $tManager->getItems(true);
		// First we need to group the templates by its type.
		$templatesGrouped = array();
		foreach ($items as $id => $template) {
			// [TEMPLATE-TYPE][AUTHOR-NAME][TEMPLATE-ID] = TEMPLATE.
			$arrangedItems[$template->type][$template->author][$id] = $template;
		}
		return $arrangedItems;
	}
	
} // End class.
