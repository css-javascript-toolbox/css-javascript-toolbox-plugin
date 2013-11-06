<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTInstallerWordpressQueueTemplates extends ArrayIterator {
	
	/**
	* 
	*/
	const TYPE_SCRIPTS = 'javascript';
	
	/**
	* 
	*/
	const TYPE_STYLE_SHEETS = 'css';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $type;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $typesMap = array(self::TYPE_SCRIPTS => 'scripts', self::TYPE_STYLE_SHEETS => 'styles');
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($type) {
		// Save type data for later use!
		$this->type = cssJSToolbox::$config->templates->types[$type];
		$this->type->name = $type;
		// Get which Wordpress queue (wp_script, wp_styles) object to fetch data from.
		$wpQueueName = self::$typesMap[$type];
		/**
		* @var CJTCouplingModel
		*/
		$coupling = CJTModel::getInstance('coupling');
		// Initialize ArrayIterator with templates list!
		parent::__construct($coupling->getQueueObject($wpQueueName)->registered);
		// Import dependencies!
		cssJSToolbox::import('framework:db:mysql:xtable.inc.php');
		CJTxTable::import('template');
		CJTxTable::import('author');
	}

	/**
	* put your comment there...
	* 
	*/
	public function install() {
		// Get template and key refernces!
		$wpTemplate = $this->current();
		$handle = $this->key();
		// Use only templates defined with the internal file systems!!
		$devFile = str_replace(".{$this->type->extension}", ".dev.{$this->type->extension}", $wpTemplate->src);
		// Get display name from Queue name!
		$displayName = ucfirst(str_replace(array('-', '_'), ' ', $handle));
		// Prepare periodically used vars!
		$time = current_time('mysql');
		// Add queue object as CJT template!
		$template = CJTxTable::getInstance('template')
		->set('name',  $displayName)
		->set('queueName', $handle)
		->set('type', $this->type->name)
		->set('creationDate', $time)
		->set('ownerId', get_current_user_id())
		->set('authorId', CJTAuthorTable::WORDPRESS_AUTHOR_ID)
		->set('state', 'published')
		->set('attributes', CJTTemplateTable::ATTRIBUTES_SYSTEM_FLAG)
		->save();
		// Add revision for that template.
		$revision = CJTxTable::getInstance('template-revision')
		->set('templateId', $template->get('id'))
		->set('revisionNo', 1)
		->set('version', $wpTemplate->ver)
		->set('changeLog', 'Cached by CJT installer!')
		->set('state', 'release')
		->set('dateCreated', $time)
		// Use .dev file once its exists, otherwise use the original file
		// even if its empty! Its the Wordpress repositibility to load the built-in
		// scripts not our. @See controllers/blocks-coupling.php where the templates/scripts is linked.
		->set('file', ((is_file(ABSPATH . "/{$devFile}" ) ? $devFile : $wpTemplate->src)))
		->save();
	}
	
} // End class.