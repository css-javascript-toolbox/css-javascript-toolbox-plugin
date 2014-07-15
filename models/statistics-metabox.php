<?php
/**
* @version $ Id; ----.php 21-03-2012 03:22:10 Ahmed Said $
*/

/**
* No direct access.
*/
defined('ABSPATH') or die("Access denied");
		
/**
* 
* @author Ahmed Said
* @version 6
*/
class CJTStatisticsMetaboxModel {
	
	/**
	* 
	*/
	const CJT_LASTEST_SCRIPT_OPTION_NAME = 'CJTStatisticsMetaboxModel.latestscripts';
	
	/**
	* 
	*/
	const LATEST_SCRIPT_EXPIRES = 86400;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dbDriver;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		$this->dbDriver =& cssJSToolbox::getInstance()->getDBDriver();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $state
	* @param mixed $type
	*/
	public function getBlocksCount($state, $type) {
		$result = $this->dbDriver->select("SELECT count(*) blocksCount FROM #__cjtoolbox_blocks WHERE state = '{$state}' AND type='{$type}';", ARRAY_A);
		return $result[0]['blocksCount'];
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getLatestScripts($count) {
		// Initialize.
		$scriptsSiteDomain = cssJSToolbox::CJT_SCRTIPS_WEB_SITE_DOMAIN;
		$scriptsFeedUrl = "http://{$scriptsSiteDomain}/forums/script-packages/user-scripts/feed/";
		$latestScriptTransit = get_option(self::CJT_LASTEST_SCRIPT_OPTION_NAME, array(
			'scripts' => array(),
			'time' => 0
		));
		// Only if cache is expires read feed from server.
		if ((time() - $latestScriptTransit['time']) > self::LATEST_SCRIPT_EXPIRES) {
			// Read feed.
			// Request server => get raw XML feed
			$feed = wp_remote_get($scriptsFeedUrl);
			if (gettype($feed) !== 'WP_Error') {
				$feedContent = wp_remote_retrieve_body($feed);
				$feedDoc = new SimpleXMLElement($feedContent);
				// Read only items count specifed by $count param.
				$items = array();
				for ($currentIndex = 0; $currentIndex < $count; $currentIndex++) {
					// Copy only title and link.
					$xmlItem = $feedDoc->channel->item[$currentIndex];
					$items[] = array('title' => (string) $xmlItem->title, 'link' => (string) $xmlItem->link);
				}
				$latestScriptTransit['scripts'] =& $items;
				// Hold cache time.
				$latestScriptTransit['time'] = time();
				update_option(self::CJT_LASTEST_SCRIPT_OPTION_NAME, $latestScriptTransit);
			}
		}
		return $latestScriptTransit['scripts'];
	}

	/**
	* put your comment there...
	* 
	*/
	public function getPackagesCount() {
		$result = $this->dbDriver->select('SELECT count(*) packagesCount FROM #__cjtoolbox_packages;', ARRAY_A);
		return $result[0]['packagesCount'];
	}

	/**
	* put your comment there...
	* 
	*/
	public function getTemplatesCount() {
		$result = $this->dbDriver->select('SELECT count(*) templatesCount FROM #__cjtoolbox_templates WHERE (attributes & 1) = 0;', ARRAY_A);
		return $result[0]['templatesCount'];		
	}

} // End class.