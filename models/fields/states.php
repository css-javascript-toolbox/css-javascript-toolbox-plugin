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
	public static function getInstance($form, $name, $value, $id = null, $classesList = '', $moreIntoTag = null) {
		return new CJTStatesField($form, $name, $value, $id, $classesList, 'text', null, $moreIntoTag)	;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		$this->items['']['text'] = '---  ' . cssJSToolbox::getText('State') . '  ---';
		$this->items += self::getStates();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public static function getStates() {
		$states = array();
		$states['published']['text'] = cssJSToolbox::getText('Published');
		$states['draft']['text'] = cssJSToolbox::getText('Draft');
		$states['trash']['text'] = cssJSToolbox::getText('Trash');	
		return $states;
	}
	
} // End class.