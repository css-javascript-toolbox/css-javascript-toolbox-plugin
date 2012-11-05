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
	* @var mixed
	*/
	public $inputs;
	
	/**
	* put your comment there...
	* 
	* @param mixed $code
	*/
	public function embedded(& $code) {
		// Read inputs, initialzie vars!
		$templateId = $this->inputs['templateId'];
		// Read template revision.
		cssJSToolbox::import('framework:db:mysql:xtable.inc.php');
		$revision = CJTxTable::getInstance('template-revision')
													->fetchLastRevision($templateId);
		// Revision could not be queried!!
		if (!$revision->get('id')) {
			throw new Exception('Revision could not be found!!');
		}
		// Read revision code.
		$code = file_get_contents(ABSPATH . "/{$revision->get('file')}");
		return $revision;
	}
	
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
