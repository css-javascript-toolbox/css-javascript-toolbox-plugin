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
	protected static $instances = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $items;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $propText;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $propValue;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($form, $name, $value, $id = null, $classesList = '', $propText = 'text', $propValue = null) {
		parent::__construct($form, $name, $value, $id, $classesList);
		// Initialize local vars.
		$this->propText = $propText;
		$this->propValue = $propValue;
		// Prepare items.
		$this->prepareItems();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getInput() {
		// Build HTML select.
		$list = "<select id='{$this->id}' class='{$this->classesList}'>";
		foreach ($this->items as $key => $item) {
			// Standrize the use of object.
			$item = (object) $item;
			// Fetch display text.
			$text = $item->{$this->propText};
			// No value prop defined then use item KEY.
			$value = ($this->propValue == null) ? $key : $item->{$this->propValue};
			$selected = ($value == $this->value) ? ' selected="selected"' : '';
			$list .= "<option value='{$value}'{$selected}>{$text}</option>"	;
		}
		$list .= '</select>';
		// If this is the first instance to be outputed for the current form output the control field.
		$fieldKey = "{$this->form}-{$this->name}";
		if (!in_array($fieldKey, self::$instances)) {
			// Output control fields.
			$list .= "<input type='hidden' name='{$this->name}' value='{$this->value}' />";
			// Mark form as instantiated!
			self::$instances[] = $fieldKey;
		}
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
	public static function getInstance($form, $name, $value, $id = null, $classesList = '') {
		return new CJTListField($form, $name, $value, $id, $classesList)	;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function prepareItems() {
		$this->items = array();
	}
	
} // End class.
