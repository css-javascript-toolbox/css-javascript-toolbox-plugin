<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php');

/**
* 
*/
class CJTReleasesField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	* @param mixed $id
	* @param mixed $classesList
	*/
	public static function getInstance($form, $name, $value, $id = null, $classesList = '', $moreIntoTag = null) {
		return new CJTReleasesField($form, $name, $value, $id, $classesList, 'text', null, $moreIntoTag);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		$this->items['']['text'] = '---  ' . cssJSToolbox::getText('State') . '  ---';
		$this->items['revision']['text'] = cssJSToolbox::getText('Revision');
		$this->items['release']['text'] = cssJSToolbox::getText('Release');	
		$this->items['beta']['text'] = cssJSToolbox::getText('Beta');	
		$this->items['alpha']['text'] = cssJSToolbox::getText('Alpha');	
		$this->items['release-candidate']['text'] = cssJSToolbox::getText('Release Candidate');	
	}
	
} // End class.