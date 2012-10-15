<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php');

/**
* 
*/
class CJTTemplateScopesField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	* @param mixed $id
	* @param mixed $classesList
	*/
	public static function getInstance($form, $name, $value, $id = null, $classesList = '') {
		return new CJTTemplateScopesField($form, $name, $value, $id, $classesList)	;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		$this->items['']['text'] = '---  ' . cssJSToolbox::getText('Scope') . '  ---';
		$this->items['Internal']['text'] = cssJSToolbox::getText('Internal');
		$this->items['Local']['text'] = cssJSToolbox::getText('Local');	
		$this->items['Remote']['text'] = cssJSToolbox::getText('Remote');
	}
	
} // End class.