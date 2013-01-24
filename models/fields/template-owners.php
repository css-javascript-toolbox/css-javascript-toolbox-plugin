<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php', 'framework:db:mysql:xtable.inc.php');

/**
* 
*/
class CJTTemplateOwnersField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		// Query CJT Authors + Wordpress build-in local users.
		$query = ' SELECT o.ID id, o.user_login `text`
													FROM #__wordpress_users o
													RIGHT JOIN #__cjtoolbox_templates t
													ON o.ID = t.ownerId ORDER BY `text`';
		// Get all exists authors
		$this->items = cssJSToolbox::getInstance()->getDBDriver()->select($query);
	}
	
} // End class.