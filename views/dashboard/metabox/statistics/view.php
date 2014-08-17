<?php
/**
* 
*/

// No direct access allowed.
defined('ABSPATH') or die("Access denied");

/**
* 
*/
class CJTDashboardMetaboxStatisticsView extends CJTView {

	protected $activeBlocks;
	protected $activeMetaboxBlocks;
	protected $feed;
	protected $inactiveBlocks;
	protected $inactiveMetaboxBlocks;
	protected $scriptsPackage;
	protected $templates;
	
	/**
	* put your comment there...
	* 
	* @param mixed $info
	* @return CJTInstallerNoticeView
	*/
	public function __construct($info) {
		// CJTView class!
		parent::__construct($info);
		// Enqueue scripts.
		self::enqueueStyles();
		self::enqueueScripts();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $tmpl
	*/
	public function display($tmpl = null) {
		// Initialize.
		$model = $this->getModel('statistics-metabox');
		// Prepare view vars.
		$this->activeBlocks = $model->getBlocksCount('active', 'block');
		$this->inactiveBlocks = $model->getBlocksCount('inactive', 'block');
		$this->activeMetaboxBlocks = $model->getBlocksCount('active', 'metabox');
		$this->inactiveMetaboxBlocks = $model->getBlocksCount('inactive', 'Metabox');
		$this->scriptsPackage = $model->getPackagesCount();
		$this->templates = $model->getTemplatesCount();
		$this->feed = $model->getFeed();
		// Display.
		echo $this->getTemplate($tmpl);
	}

	/**
	* put your comment there...
	* 
	*/
	public static function enqueueScripts() {
		// Use related scripts.
		self::useScripts(__CLASS__, 'views:dashboard:metabox:statistics:public:js:{CJTDashboardMetaboxStatisticsView-}default');
	}

	/**
	* put your comment there...
	* 
	*/
	public static function enqueueStyles() {
		// Use related scripts.
		self::useStyles(__CLASS__, 'views:dashboard:metabox:statistics:public:css:{CJTDashboardMetaboxStatisticsView-}default');
	}

} // End class.

// Hookable!!
CJTDashboardMetaboxStatisticsView::define('CJTDashboardMetaboxStatisticsView');