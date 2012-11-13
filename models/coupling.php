<?php
/**
* 
*/

cssJSToolbox::import('tables:blocks.php');
/**
* 
*/
class CJTCouplingModel {

	/**
	* 	
	*/
	public static $templateTypes = array('scripts' => 'javascript', 'styles' => 'css');
	
	/**
	* put your comment there...
	* 
	*/
	public function getOrder() {
		return get_option('meta-box-order_cjtoolbox');
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $linksExpressionFlag
	* @param mixed $pinPoint
	* @param mixed $customPins
	*/
	public function getPinsBlocks($linksExpressionFlag, $pinPoint, $customPins) {
		// Import required libraries for CJTPinsBlockSQLView.
		require_once CJTOOLBOX_TABLES_PATH . '/pins-blocks-view.php';
		// Initialize new CJTPinsBlockSQLView view object.
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$view = new CJTPinsBlockSQLView($dbDriver);
		// Apply filter to view.
		$view->filters($pinPoint, $customPins);
		// retreiving blocks data associated with current request.
		$blocks = $view->exec();
		// Get links & expressions Blocks.
		// NOTE: We need only blocks not presented in $blocks var -- exclude their id.
		$view->filters($linksExpressionFlag, array(), 'active', array_keys($blocks));
		// We'll process all blocks inside single loop.
		$blocks = $blocks + $view->exec();
		return $blocks;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $blocks
	*/
	public function getLinkedTemplates($blocks) {
		// Accept single id too!
		$blocks = (array) $blocks;
		// Import dependencies!
		cssJSToolbox::import('framework:db:mysql:xtable.inc.php', 'tables:template-revision.php');
		// Initialize vars.		
		$templates = array();		
		$query['select'] = 'SELECT t.id, t.type, t.queueName, r.version, r.file, bt.blockId';
		$query['from'] = 'FROM #__cjtoolbox_block_templates bt LEFT JOIN #__cjtoolbox_templates t
																				ON bt.templateId = t.id
																				LEFT JOIN #__cjtoolbox_template_revisions r
																				ON bt.templateId = r.templateId';
		// Where clause.
		$query['where']['blocks'] = implode(',', $blocks);
		$query['where']['attributes'] = CJTTemplateRevisionTable::FLAG_LAST_REVISION;
		$query['where'] = "WHERE bt.blockId IN ({$query['where']['blocks']}) AND (r.attributes & {$query['where']['attributes']})";
		$query = "{$query['select']} {$query['from']} {$query['where']}";
		$templates = cssJSToolbox::getInstance()->getDBDriver()->select($query);
		return $templates;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	*/
	public function getQueueObject($type) {
		 // Make sure Queue Object is ready/instantiated!
		 $globalQueueObjectName = "wp_{$type}";
		 if (!isset($GLOBALS[$globalQueueObjectName])) {
	 		 $queueClass = 'WP_' . ucfirst($type);
			 $GLOBALS[$globalQueueObjectName] = new $queueClass();
		 }
		 return $GLOBALS[$globalQueueObjectName];
	}
	
} //  End class.
