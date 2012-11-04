<?php
/**
* 
*/

/**
* 
*/
class TemplatesWordpressQueueDiscovering {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $wordpressAuthor;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Import xTable class!!
		cssJSToolbox::import('framework:db:mysql:xtable.inc.php');
		// Read Wordpress author data from db.
		$query = 'SELECT * FROM #__cjtoolbox_authors WHERE name = "Wordpress"';
		$this->wordpressAuthor = CJTxTable::getInstance('author', null, $query);
	}

	/**
	* put your comment there...
	* 
	*/
	public function install($typeName) {
		// Define Wordpress quesues as templates register.		
		$types = array(
			'javascript' => $GLOBALS['wp_scripts'],
			'css' => $GLOBALS['wp_styles'],
		);
		$type = $types[$typeName];
		$extension = cssJSToolbox::$config->templates->types[$typeName]->extension;
		foreach ($type->registered as  $handle =>$wpTemplate) {
			// Use only templates defined with the internal file systems!!
			// development file (.dev).js has priority over .js file.
			$devFile = str_replace(".{$extension}", ".dev.{$extension}", $wpTemplate->src);
			if ((is_file(ABSPATH . '/' . ($file = $devFile))) || (is_file(ABSPATH . '/' . ($file = $wpTemplate->src)))) {
				// Add queue object as CJT template!
				$template = CJTxTable::getInstance('template')
				->set('name', $handle)
				->set('type', $typeName)
				->set('creationDate', current_time('mysql'))
				->set('ownerId', get_current_user_id())
				->set('authorId', $this->wordpressAuthor->get('id'))
				->set('state', 'published')
				->save();
				// Add revision for that template.
				$revision = CJTxTable::getInstance('template-revision')
				->set('templateId', $template->get('id'))
				->set('revisionNo', 1)
				->set('version', $wpTemplate->ver)
				->set('changeLog', 'Adding as CJT Template!')
				->set('state', 'release')
				->set('dateCreated', current_time('mysql'))
				->set('file', $file)
				->save();
			}
		}
	}
	
} // End class.