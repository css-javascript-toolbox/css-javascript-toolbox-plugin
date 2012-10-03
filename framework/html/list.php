<?php
/**
* 
*/

// Import dependencies.
cssJSToolbox::import('framework:html:field.inc.php');

/**
* 
*/
class CJTListField extends CJTHTMLField {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $items;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($name, $value, $id = null, $classesList = '') {
		parent::__construct($name, $value, $id, $classesList);
		// Prepare items.
		$this->prepareItems();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getInput() {
		// Build HTML select.
		$list = "<select id='{$this->id}' name='{$this->name}' class='{$this->classesList}'>";
		foreach ($this->items as $value => $text) {
			$selected = ($value == $this->value) ? ' selected="selected"' : '';
			$list .= "<option value='{$value}'{$selected}>{$text}</option>"	;
		}
		$list .= '</select>';
		return $list;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function &getItems() {
		return $this->items;	
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	* @param mixed $id
	* @param mixed $classesList
	*/
	public static function getInstance($name, $value, $id = null, $classesList = '') {
		return new CJTListField($name, $value, $id, $classesList)	;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		$this->items = array();
	}
	
} // End class.
