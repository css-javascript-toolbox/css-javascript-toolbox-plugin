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
	
} //  End class.


// Hookable!
CJTCouplingModel::define('CJTCouplingModel', array('hookType' => CJTWordpressEvents::HOOK_FILTER));