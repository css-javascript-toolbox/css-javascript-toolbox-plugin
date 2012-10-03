<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:list.php');

/**
* 
*/
class CJTTemplatesTypesField extends CJTListField {
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	* @param mixed $id
	* @param mixed $classesList
	*/
	public static function getInstance($name, $value, $id = null, $classesList = '') {
		return new CJTTemplatesTypesField($name, $value, $id, $classesList)	;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		$this->items[''] = '---  ' . cssJSToolbox::getText('Type') . '  ---';
		$this->items['javascript'] = cssJSToolbox::getText('Javascript');
		$this->items['css'] = cssJSToolbox::getText('CSS');	
		$this->items['html'] = cssJSToolbox::getText('HTML');	
		$this->items['php'] = cssJSToolbox::getText('PHP');	
	}
	
} // End class.