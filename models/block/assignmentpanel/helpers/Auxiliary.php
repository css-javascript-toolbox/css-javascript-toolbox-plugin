<?php
/**
* 
*/

/**
* 
*/
class CJT_Models_Block_Assignmentpanel_Helpers_Auxiliary {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $list;

	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		// Define auxiliary list.
		$this->list = array(
			CJTBlockModel::PINS_POSTS_BLOG_INDEX =>  cssJSToolbox::getText('Blog Index'),
			CJTBlockModel::PINS_POSTS_ALL_POSTS =>  cssJSToolbox::getText('All Posts'),
			CJTBlockModel::PINS_PAGES_ALL_PAGES =>  cssJSToolbox::getText('All Pages'),
			CJTBlockModel::PINS_CATEGORIES_ALL_CATEGORIES =>  cssJSToolbox::getText('All Categories'),
			CJTBlockModel::PINS_POSTS_RECENT =>  cssJSToolbox::getText('Recent Posts'),
			CJTBlockModel::PINS_FRONTEND => cssJSToolbox::getText('Entire Website'),
			CJTBlockModel::PINS_BACKEND => cssJSToolbox::getText('Website Backend'),
			CJTBlockModel::PINS_SEARCH => cssJSToolbox::getText('Search Pages'),
			CJTBlockModel::PINS_ARCHIVE => cssJSToolbox::getText('All Archives'),
			CJTBlockModel::PINS_TAG => cssJSToolbox::getText('Tag Archives'),
			CJTBlockModel::PINS_AUTHOR => cssJSToolbox::getText('Author Archives'),
			CJTBlockModel::PINS_ATTACHMENT => cssJSToolbox::getText('Attachment Pages'),
			CJTBlockModel::PINS_404_ERROR => cssJSToolbox::getText('404 Error'),
		);
	}

	/**
	* put your comment there...
	* 
	*/
	public static function getInstance() {
		return new CJT_Models_Block_Assignmentpanel_Helpers_Auxiliary();
	}

	/**
	* put your comment there...
	* 
	*/
	public function getList() {
		return $this->list;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $pinPoint
	*/
	public function getPinsArray($pinPoint) {
		// Initialize.
		$pinsArray = array();
		$flagsList = array_keys($this->getList());
		// Get the 'state' for all the available flags.
		foreach ($flagsList as $flagValue) {
			if ($flagValue & $pinPoint) {
				// Use bitValue as ID and ON/OFF as value.
				$pinsArray[dechex($flagValue)] = true;				
			}
		}
		return $pinsArray;
	}

} // End class.
