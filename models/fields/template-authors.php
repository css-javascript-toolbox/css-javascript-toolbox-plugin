<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php', 'framework:db:mysql:xtable.inc.php');

/**
* 
*/
class CJTTemplateAuthorsField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		CJTxTable::import('author');
		$internalAuthorsFlag = CJTAuthorTable::FLAG_SYS_AUTHOR;
		// Query CJT Authors + Wordpress build-in local users.
		$query = " SELECT a.id, a.name `text`
													FROM #__cjtoolbox_authors a
													RIGHT JOIN #__cjtoolbox_templates t
													ON a.id = t.authorId
													WHERE (a.attributes & {$internalAuthorsFlag}) = 0
													ORDER BY `text`";
		// Get all exists authors
		$this->items = cssJSToolbox::getInstance()->getDBDriver()->select($query);
	}
	
} // End class.