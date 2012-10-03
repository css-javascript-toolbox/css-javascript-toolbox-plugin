<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php');

/**
* 
*/
class CJTStatesField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	* @param mixed $id
	* @param mixed $classesList
	*/
	public static function getInstance($name, $value, $id = null, $classesList = '') {
		return new CJTStatesField($name, $value, $id, $classesList)	;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		$this->items[''] = '---  ' . cssJSToolbox::getText('State') . '  ---';
		$this->items['publish'] = cssJSToolbox::getText('Published');
		$this->items['trash'] = cssJSToolbox::getText('Trash');	
	}
	
} // End class.