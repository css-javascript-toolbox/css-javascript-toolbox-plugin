<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php', 'framework:db:mysql:xtable.inc.php');

/**
* 
*/
class CJTTemplateStatesField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		if (isset($this->options['result']) && ($this->options['result'] == 'fullList')) {
			$this->items['published']['text'] = cssJSToolbox::getText('published');
			$this->items['draft']['text'] = cssJSToolbox::getText('draft');
			$this->items['trash']['text'] = cssJSToolbox::getText('trash');
		}
		else {
			CJTxTable::import('author');
			$internalAuthorsFlag = CJTAuthorTable::FLAG_SYS_AUTHOR;
			// Query all template state exluding Internal authors.
			$query = "SELECT DISTINCT(state) `text` 
													FROM #__cjtoolbox_templates t
													LEFT JOIN #__cjtoolbox_authors a
													ON  t.authorId = a.id
													WHERE (a.attributes & {$internalAuthorsFlag}) = 0
													ORDER BY `text`";
			$this->items = cssJSToolbox::getInstance()->getDBDriver()->select($query);			
		}
	}
	
} // End class.