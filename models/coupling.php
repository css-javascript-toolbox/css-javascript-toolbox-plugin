<?php
/**
* 
*/

// Disallow direct access.
defined('ABSPATH') or die("Access denied");

// Import dependencies.
cssJSToolbox::import('tables:blocks.php');

/**
* 
*/
class CJTCouplingModel extends CJTHookableClass {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onexpressionsandlinkedblocks = array('parameters' => array('blocks'));

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $ongetorder = array('parameters' => array('order'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onquerylinkedtemplates = array('parameters' => array('query'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onpinsblockfilters = array('parameters' => array(
			'params' => array('linksExpressionFlag', 'pinPoint', 'customPins')
			)
	);
			
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $onrequestblocks = array('parameters' => array('blocks'));
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	public static $templateTypes = array('scripts' => 'javascript', 'styles' => 'css');
	
	/**
	* put your comment there...
	* 
	*/
	public function getOrder() {
		return $this->ongetorder(get_option('meta-box-order_cjtoolbox'));
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $linksExpressionFlag
	* @param mixed $pinPoint
	* @param mixed $customPins
	*/
	public function getPinsBlocks($linksExpressionFlag, $pinPoint, $customPins) {
		// Extendable!
		extract($this->onpinsblockfilters(compact('linksExpressionFlag', 'pinPoint', 'customPins')));
		// Import required libraries for CJTPinsBlockSQLView.
		require_once CJTOOLBOX_TABLES_PATH . '/pins-blocks-view.php';
		// Initialize new CJTPinsBlockSQLView view object.
		$dbDriver = new CJTMYSQLQueueDriver($GLOBALS['wpdb']);
		$view = new CJTPinsBlockSQLView($dbDriver);
		// Apply filter to view.
		$view->filters($pinPoint, $customPins);
		// retreiving blocks data associated with current request.
		$blocks = $this->onrequestblocks($view->exec());
		// Get links & expressions Blocks.
		// NOTE: We need only blocks not presented in $blocks var -- exclude their id.
		$view->filters($linksExpressionFlag, array(), 'active', array_keys($blocks));
		// We'll process all blocks inside single loop.
		$blocks = $blocks + $this->onexpressionsandlinkedblocks($view->exec());
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
		// Filering!
		$query = $this->onquerylinkedtemplates($query);
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


// Hookable!
CJTCouplingModel::define('CJTCouplingModel', array('hookType' => CJTWordpressEvents::HOOK_FILTER));