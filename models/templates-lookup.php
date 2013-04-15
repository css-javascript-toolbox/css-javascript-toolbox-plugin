<?php
/**
* 
*/

// Import dependencies
cssJSToolbox::import('framework:db:mysql:xtable.inc.php');

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
	*/
	public function embedded() {
		// Read template revision.		
		$revision = CJTxTable::getInstance('template-revision')
													->fetchLastRevision($this->inputs['templateId']);
		// Revision could not be queried!!
		if (!$revision->get('id')) {
			throw new Exception('Revision could not be found!!');
		}
		$revisionFile = $revision->get('file');
		// Read revision code.
		$code = file_get_contents(ABSPATH . "/{$revisionFile}");
		// Decrypt PHP codes!
		if (preg_match('/\.php$/', $revisionFile)) {
			$code = CJTModel::getInstance('template')->decryptCode($code);
		}
		return $code;
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
		$query = "SELECT 		t.id, t.type, t.name, t.description, (t.attributes & 1) systemTemplate,
																							a.name author,
																							bt.blockId linked
																							FROM 	#__cjtoolbox_templates t
																							LEFT JOIN 	#__cjtoolbox_authors a
																								ON t.authorId = a.id
																							LEFT JOIN	
																							(SELECT * 
																							FROM #__cjtoolbox_block_templates 
																							WHERE blockId = {$this->inputs['blockId']}
																							) bt
																								ON t.id = bt.templateId
																							WHERE t.state = 'published'";
		$items = cssJSToolbox::getInstance()->getDBDriver()->select($query);
		// First we need to group the templates by its type.
		$templatesGrouped = array();
		foreach ($items as $id => $template) {
			// [TEMPLATE-TYPE][AUTHOR-NAME][TEMPLATE-ID] = TEMPLATE.
			$arrangedItems[$template->type][$template->author][$id] = $template;
		}
		return $arrangedItems;
	}

	/**
	* put your comment there...
	* 
	*/		
	public function link() {
		// Add db record!
		$map = CJTxTable::getInstance('block-template')
		->setData($this->inputs) // Load with data
		->save();
		// There will be an id when successed!
		return $map->get('id');
	}
	
	/**
	* put your comment there...
	* 	
	*/
	public function unlink() {
		// Delete record!
		$map = CJTxTable::getInstance('block-template')
		->setData($this->inputs) // Load with data
		->delete(array_keys($this->inputs)); // Delete using compound key!
		// Fields will be cleared when deleted!
		return $map->getKey();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function unlinkAll() {
		$query = "DELETE FROM #__cjtoolbox_block_templates 
													WHERE blockId = {$this->inputs['blockId']}";
		cssJSToolbox::getInstance()->getDBDriver()
			->delete($query)
			->processQueue();
	}
	
} // End class.
