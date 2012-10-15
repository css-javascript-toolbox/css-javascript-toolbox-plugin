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
	public static function getInstance($form, $name, $value, $id = null, $classesList = '') {
		return new CJTReleasesField($form, $name, $value, $id, $classesList)	;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		$this->items['']['text'] = '---  ' . cssJSToolbox::getText('Tag') . '  ---';
		$this->items['-1']['text'] = cssJSToolbox::getText('Revision');
		$this->items['1']['text'] = cssJSToolbox::getText('Release');	
	}
	
} // End class.