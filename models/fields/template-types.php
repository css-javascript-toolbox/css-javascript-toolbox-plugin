<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php', 'framework:db:mysql:xtable.inc.php');

/**
* 
*/
class CJTTemplateTypesField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		if ((isset($this->options['result'])) && ($this->options['result'] == 'fullList')) {
			$this->items['css']['text'] = cssJSToolbox::getText('css');
			$this->items['javascript']['text'] = cssJSToolbox::getText('javascript');
			$this->items['html']['text'] = cssJSToolbox::getText('html');
			$this->items['php']['text'] = cssJSToolbox::getText('php');
		}
		else {
			CJTxTable::import('author');
			$internalAuthorsFlag = CJTAuthorTable::FLAG_SYS_AUTHOR;
			// Query all types  ezcluding internal authors!
			$query = "SELECT DISTINCT(type) `text` 
													FROM #__cjtoolbox_templates  t
													LEFT JOIN #__cjtoolbox_authors a
													ON  t.authorId = a.id
													WHERE (a.attributes & {$internalAuthorsFlag}) = 0
													ORDER BY `text`";
			$this->items = cssJSToolbox::getInstance()->getDBDriver()->select($query);
		}
	}
	
} // End class.