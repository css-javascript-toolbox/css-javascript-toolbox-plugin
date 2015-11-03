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
	* @param mixed $id
	*/
	public function getBlockCode($id) {
		// Initialize.
		$code = '';
		// Initialize.
		$tblCodeFiles = new CJTBlockFilesTable(cssJSToolbox::getInstance()->getDBDriver());
		// Query Block Code Files.
		$codeFiles = $tblCodeFiles->set('blockId', $id)
								 							->fetchAll();
		// Merge all code files.
		foreach ($codeFiles as $codeFile) {
			// Wrap by Tag + Merge files.
			$code .= $codeFile['tag'] ? sprintf($codeFile['tag'], " {$codeFile['code']} ") : $codeFile['code'];
		}
		// Return final code text.
		return $code;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $blockId
	*/
	public function getExecTemplatesCode($blockId) {
		// Initialize.
		$code = '';
		// Get all HTML and PHP templates linked to the block.
		$templates = $this->getLinkedTemplates($blockId, array('php', 'html'));
		if (!empty($templates))	{
			// Instantiate template model.
			$templateModel = CJTModel::getInstance('template');
			// Concat their codes.
			foreach ($templates as $template) {
				// Fetch template record with code loaded from file and decrypted if PHP!
				$templateModel->inputs['id'] = $template->id;
				$template = $templateModel->getItem();
				// Concat
				$code .= $template->code;
			}
		}
		return $code;
	}

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
	* @param mixed $hookParams
	*/
	public function getPinsBlocks( $linksExpressionFlag, $pinPoint, $customPins, $hookParams ) 
	{
		// Extendable!
		extract( $this->onpinsblockfilters( compact( 'linksExpressionFlag', 'pinPoint', 'customPins' ) ) );
		
		// Import required libraries for CJTPinsBlockSQLView.
		require_once CJTOOLBOX_TABLES_PATH . '/pins-blocks-view.php';
		
		// Initialize new CJTPinsBlockSQLView view object.
		$dbDriver = new CJTMYSQLQueueDriver( $GLOBALS[ 'wpdb' ] );
		$view = new CJTPinsBlockSQLView( $dbDriver );
		
		// Apply filter to view.
		$view->filters( $pinPoint, $customPins );
		
		# Allow Plugins/Extensions to change block core query
		do_action( 
		
			CJTPluggableHelper::ACTION_BLOCK_QUERY_BLOCKS,
			
			$view,
			
			$hookParams
			
			);
			
		// retreiving blocks data associated with current request.
		$blocks = $this->onrequestblocks( $view->exec() );
		
		# Filter queue blocks
		$blocks = apply_filters( CJTPluggableHelper::FILTER_BLOCKS_COUPLING_MODEL_BLOCKS_QUEUE, $blocks, $hookParams );
		
		// Get links & expressions Blocks.
		// NOTE: We need only blocks not presented in $blocks var -- exclude their id.
		$view->filters( $linksExpressionFlag, array(), 'active', array_keys( $blocks ) );
		
		// We'll process all blocks inside single loop.
		$blocks = $blocks + $this->onexpressionsandlinkedblocks( $view->exec() );

		return $blocks;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $blocks
	* @param mixed $types
	*/
	public function getLinkedTemplates($blocks, $types = array('javascript', 'css')) {
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
		// Filter by blocks ids and getting the last revision of the template!
		$query['where']['blocks'] = implode(',', $blocks);
		$query['where']['attributes'] = CJTTemplateRevisionTable::FLAG_LAST_REVISION;
		// Filter by type.
		$query['where']['types'] = '"' . implode('","', $types) . '"';
		// Where clause.
		$query['where'] = "WHERE bt.blockId IN ({$query['where']['blocks']}) AND 
																	(r.attributes & {$query['where']['attributes']}) AND 
																	t.type IN ({$query['where']['types']})";
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