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
	protected $mandatory;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $moreIntoTag;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $optional;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $options = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $propText = 'text';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $propValue = null;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct($form, $name, $value, $id = null, $classesList = '', $propText = null, $propValue = null, $moreIntoTag = null, $optional = null) {
		parent::__construct($form, $name, $value, $id, $classesList);
		// Initialize local vars.
		$this->propText = $propText ? $propText : 'text';
		$this->propValue = $propValue;
		$this->moreIntoTag = $moreIntoTag;
		$this->optional = $optional;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getInput($options = null) {		
		$this->options = ($options !== null) ? $options : array();
		// Prepare items.
		$this->prepareItems();
		if ($this->optional) {
			$this->items = array('' => (object) array(
				$this->propText => htmlentities($this->optional),
				 '__params__' => (object) array('className' => 'optional')))+ $this->items;
		}
		// Build HTML select.
		$listName = (isset($this->options['standard']) && ($this->options['standard'] == true))  ? "name='{$this->name}'" : '';
		$list = "<select id='{$this->id}' {$listName} class='{$this->classesList}' {$this->moreIntoTag}>";
		foreach ($this->items as $key => $item) {
			// Standrize the use of object.
			$item = (object) $item;
			// Fetch display text.
			$text = cssJSToolbox::getText($item->{$this->propText});
			// No value prop defined then use item KEY.
			$value = ($this->propValue == null) ? $key : $item->{$this->propValue};
			$selected = ($value == $this->value) ? ' selected="selected"' : '';
			if (isset($item->__params__->className)) {
				$class = "class='{$item->__params__->className}'";
			}
			else {
				$class = '';
			}
			$list .= "<option {$class} value='{$value}'{$selected}>{$text}</option>"	;
		}
		$list .= '</select>';
		// If this is the first instance to be outputed for the current form output the control field.
		$fieldKey = "{$this->form}-{$this->name}";
		if ((!isset($this->options['standard'])) && (!in_array($fieldKey, self::$instances))) {
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
	* @param mixed $type
	* @param mixed $form
	* @param mixed $name
	* @param mixed $value
	* @param mixed $id
	* @param mixed $classesList
	* @param mixed $propText
	* @param mixed $propValue
	* @param mixed $moreIntoTag
	*/
	public static function getInstance($type, $form, $name, $value, $id = null, $classesList = '', $propText = 'text', $propValue = null, $moreIntoTag = null, $optional = null) {
		/* * @ todo Code to importing file and instantiating class should be in CJTHTMLField class not here!! */
		// Import field file.
		cssJSToolbox::import("models:fields:{$type}.php");
		// Create an instance.
		$type = str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $type)));
		$className = "CJT{$type}Field";
		return new $className($form, $name, $value, $id, $classesList, $propText, $propValue, $moreIntoTag, $optional);
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
	*/
	protected function prepareItems() {
		$this->items = array();
	}
	
} // End class.
