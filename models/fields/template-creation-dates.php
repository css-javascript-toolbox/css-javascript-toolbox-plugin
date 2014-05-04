<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php', 'framework:db:mysql:xtable.inc.php');

/**
* 
*/
class CJTTemplateCreationDatesField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		CJTxTable::import('author');
		$internalAuthorsFlag = CJTAuthorTable::FLAG_SYS_AUTHOR;
		// Query all dates (without time!) or internal authors.
		$query = " SELECT DISTINCT(DATE(t.creationDate)) `text`
													FROM #__cjtoolbox_templates t
													LEFT JOIN #__cjtoolbox_authors a
													ON  t.authorId = a.id
													WHERE (a.attributes & {$internalAuthorsFlag}) = 0
													ORDER BY `text`;";
		$this->items = cssJSToolbox::getInstance()->getDBDriver()->select($query);
	}
	
} // End class.