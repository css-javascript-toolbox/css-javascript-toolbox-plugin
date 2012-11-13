<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php', 'framework:db:mysql:xtable.inc.php');

/**
* 
*/
class CJTTemplateDevelopmentStatesField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		if ($this->options['result'] == 'fullList') {
			$this->items['release']['text'] = 'Release';
			$this->items['beta']['text'] = 'Beta';
			$this->items['alpha']['text'] = 'Alpha';
			$this->items['release-candidate']['text'] = 'Release Candidate';
			$this->items['revision']['text'] = 'Revision';
		}
		else {
			// Import dependencies.
			CJTxTable::import('template-revision');
			CJTxTable::import('author');
			$lastVersionFlag = CJTTemplateRevisionTable::FLAG_LAST_REVISION;
			$internalAuthorsFlag = CJTAuthorTable::FLAG_SYS_AUTHOR;
			$query = "SELECT DISTINCT(r.state) `text` 
														FROM #__cjtoolbox_template_revisions r
														LEFT JOIN #__cjtoolbox_templates t
														ON  r.templateId = t.id
														LEFT JOIN #__cjtoolbox_authors a
														ON  t.authorId = a.id
														WHERE (r.attributes & {$lastVersionFlag}) AND (a.attributes & {$internalAuthorsFlag}) = 0
														ORDER BY `text`";
			$this->items = cssJSToolbox::getInstance()->getDBDriver()->select($query);			
		}
	}
	
} // End class.